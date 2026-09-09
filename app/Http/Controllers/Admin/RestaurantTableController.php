<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RestaurantTableController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $area = trim((string) $request->query('area'));

        $tables = RestaurantTable::query()
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('table_number', 'like', "%{$search}%")
                    ->orWhere('name', 'like', "%{$search}%");
            }))
            ->when($area !== '', fn ($query) => $query->where('area', $area))
            ->orderBy('area')->orderBy('table_number')
            ->paginate(12)->withQueryString();

        $areas = RestaurantTable::query()->select('area')->distinct()->orderBy('area')->pluck('area');

        return view('admin.restaurant.tables.index', compact('tables', 'areas', 'search', 'area'));
    }

    public function create(): View
    {
        return view('admin.restaurant.tables.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateTable($request);
        $validated['qr_token'] = (string) Str::uuid();
        $validated['is_active'] = $request->boolean('is_active');

        RestaurantTable::create($validated);

        return redirect()->route('management.tables.index')
            ->with('success', 'Meja restoran berhasil ditambahkan.');
    }

    public function edit(RestaurantTable $restaurantTable): View
    {
        return view('admin.restaurant.tables.edit', compact('restaurantTable'));
    }

    public function update(Request $request, RestaurantTable $restaurantTable): RedirectResponse
    {
        $validated = $this->validateTable($request, $restaurantTable);
        $validated['is_active'] = $request->boolean('is_active');
        $restaurantTable->update($validated);

        return redirect()->route('management.tables.index')
            ->with('success', 'Meja restoran berhasil diperbarui.');
    }

    public function destroy(RestaurantTable $restaurantTable): RedirectResponse
    {
        try {
            $restaurantTable->delete();
            return back()->with('success', 'Meja restoran berhasil dihapus.');
        } catch (QueryException) {
            return back()->with('error', 'Meja tidak dapat dihapus karena memiliki reservasi atau transaksi. Nonaktifkan meja sebagai gantinya.');
        }
    }

    public function toggleStatus(RestaurantTable $restaurantTable): RedirectResponse
    {
        $restaurantTable->update(['is_active' => ! $restaurantTable->is_active]);
        return back()->with('success', 'Status meja berhasil diperbarui.');
    }

    public function regenerateQr(RestaurantTable $restaurantTable): RedirectResponse
    {
        $restaurantTable->update(['qr_token' => (string) Str::uuid()]);
        return back()->with('success', 'Token QR meja berhasil dibuat ulang. QR lama sudah tidak berlaku.');
    }

    private function validateTable(Request $request, ?RestaurantTable $restaurantTable = null): array
    {
        return $request->validate([
            'table_number' => ['required', 'string', 'max:30', Rule::unique('restaurant_tables', 'table_number')->ignore($restaurantTable?->id)],
            'name' => ['nullable', 'string', 'max:100'],
            'area' => ['required', 'string', 'max:50'],
            'capacity' => ['required', 'integer', 'min:1', 'max:100'],
            'is_active' => ['nullable', 'boolean'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);
    }
}
