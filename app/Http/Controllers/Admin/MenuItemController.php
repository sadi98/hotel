<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\MenuItem;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuItemController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $categoryId = $request->integer('category_id') ?: null;
        $availability = $request->query('availability');

        $menuItems = MenuItem::query()
            ->with('category:id,name,menu_type')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->when($categoryId, fn ($query) => $query->where('category_id', $categoryId))
            ->when(in_array($availability, ['available', 'unavailable'], true),
                fn ($query) => $query->where('is_available', $availability === 'available'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(12)
            ->withQueryString();

        $categories = Category::query()->orderBy('menu_type')->orderBy('name')->get(['id', 'name', 'menu_type']);

        return view('admin.restaurant.menu-items.index', compact(
            'menuItems', 'categories', 'search', 'categoryId', 'availability'
        ));
    }

    public function create(): View
    {
        $categories = Category::query()->where('is_active', true)
            ->orderBy('menu_type')->orderBy('name')->get();

        return view('admin.restaurant.menu-items.create', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateMenuItem($request);
        $validated = $this->normalizeByCategory($validated);
        $validated['slug'] = $this->uniqueSlug($validated['name']);
        $validated['is_available'] = $request->boolean('is_available');
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $validated['image'] = $request->file('image')->store('restaurant/menu-items', 'public');
        }

        MenuItem::create($validated);

        return redirect()->route('management.menu-items.index')
            ->with('success', 'Menu berhasil ditambahkan.');
    }

    public function edit(MenuItem $menuItem): View
    {
        $categories = Category::query()->where('is_active', true)
            ->orWhereKey($menuItem->category_id)
            ->orderBy('menu_type')->orderBy('name')->get();

        return view('admin.restaurant.menu-items.edit', compact('menuItem', 'categories'));
    }

    public function update(Request $request, MenuItem $menuItem): RedirectResponse
    {
        $validated = $this->validateMenuItem($request, $menuItem);
        $validated = $this->normalizeByCategory($validated);
        $validated['slug'] = $this->uniqueSlug($validated['name'], $menuItem->id);
        $validated['is_available'] = $request->boolean('is_available');
        $validated['is_featured'] = $request->boolean('is_featured');

        if ($request->hasFile('image')) {
            $oldImage = $menuItem->image;
            $validated['image'] = $request->file('image')->store('restaurant/menu-items', 'public');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        $menuItem->update($validated);

        return redirect()->route('management.menu-items.index')
            ->with('success', 'Menu berhasil diperbarui.');
    }

    public function destroy(MenuItem $menuItem): RedirectResponse
    {
        try {
            $image = $menuItem->image;
            $menuItem->delete();
            if ($image) {
                Storage::disk('public')->delete($image);
            }

            return back()->with('success', 'Menu berhasil dihapus.');
        } catch (QueryException) {
            return back()->with('error', 'Menu tidak dapat dihapus karena masih digunakan di paket atau transaksi.');
        }
    }

    public function toggleAvailability(MenuItem $menuItem): RedirectResponse
    {
        $menuItem->update(['is_available' => ! $menuItem->is_available]);

        return back()->with('success', 'Ketersediaan menu berhasil diperbarui.');
    }

    private function validateMenuItem(Request $request, ?MenuItem $menuItem = null): array
    {
        return $request->validate([
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'sku' => ['required', 'string', 'max:50', Rule::unique('menu_items', 'sku')->ignore($menuItem?->id)],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:3000'],
            'beverage_type' => ['nullable', Rule::in(['coffee', 'non_coffee'])],
            'is_alcoholic' => ['nullable', 'boolean'],
            'price' => ['required', 'numeric', 'min:0', 'max:9999999999999.99'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'preparation_time' => ['required', 'integer', 'min:1', 'max:1440'],
            'is_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'sort_order' => ['required', 'integer', 'min:0'],
        ]);
    }

    private function normalizeByCategory(array $validated): array
    {
        $category = Category::query()->findOrFail($validated['category_id']);
        if ($category->menu_type === 'food') {
            $validated['beverage_type'] = null;
            $validated['is_alcoholic'] = null;
        } else {
            $validated['is_alcoholic'] = (bool) ($validated['is_alcoholic'] ?? false);
        }

        return $validated;
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'menu';
        $slug = $base;
        $number = 1;

        while (MenuItem::query()->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$number++;
        }

        return $slug;
    }
}
