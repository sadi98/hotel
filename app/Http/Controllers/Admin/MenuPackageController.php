<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuPackage;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class MenuPackageController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $availability = $request->query('availability');

        $menuPackages = MenuPackage::query()
            ->withCount('items')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('sku', 'like', "%{$search}%");
            }))
            ->when(in_array($availability, ['available', 'unavailable'], true),
                fn ($query) => $query->where('is_available', $availability === 'available'))
            ->orderBy('sort_order')
            ->orderBy('name')
            ->paginate(10)
            ->withQueryString();

        return view('admin.restaurant.menu-packages.index', compact('menuPackages', 'search', 'availability'));
    }

    public function create(): View
    {
        $menuItems = MenuItem::query()->with('category:id,name,menu_type')
            ->where('is_available', true)->orderBy('name')->get();

        return view('admin.restaurant.menu-packages.create', compact('menuItems'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validatePackage($request);

        DB::transaction(function () use ($request, $validated) {
            $data = $this->packageData($request, $validated);
            $data['slug'] = $this->uniqueSlug($data['name']);

            if ($request->hasFile('image')) {
                $data['image'] = $request->file('image')->store('restaurant/menu-packages', 'public');
            }

            $package = MenuPackage::create($data);
            $this->syncItems($package, $validated['items']);
        });

        return redirect()->route('management.menu-packages.index')
            ->with('success', 'Paket menu berhasil ditambahkan.');
    }

    public function edit(MenuPackage $menuPackage): View
    {
        $menuItems = MenuItem::query()->with('category:id,name,menu_type')
            ->orderBy('name')->get();
        $selectedItems = DB::table('menu_package_items')
            ->where('menu_package_id', $menuPackage->id)
            ->orderBy('sort_order')
            ->get()
            ->keyBy('menu_item_id');

        return view('admin.restaurant.menu-packages.edit', compact(
            'menuPackage', 'menuItems', 'selectedItems'
        ));
    }

    public function update(Request $request, MenuPackage $menuPackage): RedirectResponse
    {
        $validated = $this->validatePackage($request, $menuPackage);
        $oldImage = $menuPackage->image;
        $newImage = null;

        DB::transaction(function () use ($request, $validated, $menuPackage, &$newImage) {
            $data = $this->packageData($request, $validated);
            $data['slug'] = $this->uniqueSlug($data['name'], $menuPackage->id);

            if ($request->hasFile('image')) {
                $newImage = $request->file('image')->store('restaurant/menu-packages', 'public');
                $data['image'] = $newImage;
            }

            $menuPackage->update($data);
            $this->syncItems($menuPackage, $validated['items']);
        });

        if ($newImage && $oldImage) {
            Storage::disk('public')->delete($oldImage);
        }

        return redirect()->route('management.menu-packages.index')
            ->with('success', 'Paket menu berhasil diperbarui.');
    }

    public function destroy(MenuPackage $menuPackage): RedirectResponse
    {
        $image = $menuPackage->image;
        $menuPackage->delete();

        if ($image) {
            Storage::disk('public')->delete($image);
        }

        return back()->with('success', 'Paket menu berhasil dihapus.');
    }

    public function toggleAvailability(MenuPackage $menuPackage): RedirectResponse
    {
        $menuPackage->update(['is_available' => ! $menuPackage->is_available]);

        return back()->with('success', 'Ketersediaan paket berhasil diperbarui.');
    }

    private function validatePackage(Request $request, ?MenuPackage $menuPackage = null): array
    {
        return $request->validate([
            'sku' => ['required', 'string', 'max:50', Rule::unique('menu_packages', 'sku')->ignore($menuPackage?->id)],
            'name' => ['required', 'string', 'max:150'],
            'description' => ['nullable', 'string', 'max:3000'],
            'normal_price' => ['required', 'numeric', 'min:0'],
            'package_price' => ['required', 'numeric', 'min:0', 'lte:normal_price'],
            'serving_count' => ['required', 'integer', 'min:1'],
            'minimum_order' => ['required', 'integer', 'min:1'],
            'image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:3072'],
            'is_available' => ['nullable', 'boolean'],
            'is_featured' => ['nullable', 'boolean'],
            'available_from' => ['nullable', 'date'],
            'available_until' => ['nullable', 'date', 'after:available_from'],
            'sort_order' => ['required', 'integer', 'min:0'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.menu_item_id' => ['required', 'integer', 'distinct', 'exists:menu_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1', 'max:999'],
            'items.*.note' => ['nullable', 'string', 'max:1000'],
            'items.*.sort_order' => ['nullable', 'integer', 'min:0'],
        ]);
    }

    private function packageData(Request $request, array $validated): array
    {
        unset($validated['items'], $validated['image']);
        $validated['is_available'] = $request->boolean('is_available');
        $validated['is_featured'] = $request->boolean('is_featured');

        return $validated;
    }

    private function syncItems(MenuPackage $package, array $items): void
    {
        DB::table('menu_package_items')->where('menu_package_id', $package->id)->delete();

        $now = now();
        DB::table('menu_package_items')->insert(array_map(
            fn (array $item, int $index) => [
                'menu_package_id' => $package->id,
                'menu_item_id' => $item['menu_item_id'],
                'quantity' => $item['quantity'],
                'note' => $item['note'] ?? null,
                'sort_order' => $item['sort_order'] ?? $index,
                'created_at' => $now,
                'updated_at' => $now,
            ],
            $items,
            array_keys($items)
        ));
    }

    private function uniqueSlug(string $name, ?int $ignoreId = null): string
    {
        $base = Str::slug($name) ?: 'paket';
        $slug = $base;
        $number = 1;

        while (MenuPackage::query()->when($ignoreId, fn ($query) => $query->whereKeyNot($ignoreId))
            ->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$number++;
        }

        return $slug;
    }
}
