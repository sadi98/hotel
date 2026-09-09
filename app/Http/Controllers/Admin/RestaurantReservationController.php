<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\RestaurantTable;
use App\Models\TableReservation;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RestaurantReservationController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $date = $request->query('date');

        $reservations = TableReservation::query()
            ->with(['restaurantTable:id,table_number,name,area', 'user:id,name'])
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('reservation_number', 'like', "%{$search}%")
                    ->orWhere('customer_name', 'like', "%{$search}%")
                    ->orWhere('customer_phone', 'like', "%{$search}%");
            }))
            ->when(in_array($status, $this->statuses(), true), fn ($query) => $query->where('status', $status))
            ->when($date, fn ($query) => $query->whereDate('reservation_start', $date))
            ->orderByDesc('reservation_start')
            ->paginate(15)
            ->withQueryString();

        $statuses = $this->statuses();

        return view('admin.restaurant.reservations.index', compact(
            'reservations', 'statuses', 'search', 'status', 'date'
        ));
    }

    public function create(): View
    {
        $tables = RestaurantTable::query()->where('is_active', true)
            ->orderBy('area')->orderBy('table_number')->get();
        $statuses = $this->statuses();

        return view('admin.restaurant.reservations.create', compact('tables', 'statuses'));
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateReservation($request);
        $this->ensureTableIsAvailable($validated);

        $validated['access_token'] = (string) Str::uuid();
        $validated['reservation_number'] = $this->generateReservationNumber();
        $validated['user_id'] = null;

        TableReservation::create($validated);

        return redirect()->route('management.reservations.index')
            ->with('success', 'Reservasi berhasil ditambahkan.');
    }

    public function show(TableReservation $reservation): View
    {
        $reservation->load(['restaurantTable', 'user']);
        return view('admin.restaurant.reservations.show', compact('reservation'));
    }

    public function edit(TableReservation $reservation): View
    {
        $tables = RestaurantTable::query()->where('is_active', true)
            ->orWhereKey($reservation->restaurant_table_id)
            ->orderBy('area')->orderBy('table_number')->get();
        $statuses = $this->statuses();

        return view('admin.restaurant.reservations.edit', compact('reservation', 'tables', 'statuses'));
    }

    public function update(Request $request, TableReservation $reservation): RedirectResponse
    {
        // Tetap mendukung form status sederhana yang sebelumnya sudah digunakan.
        if ($request->has('status') && ! $request->has('customer_name')) {
            $validated = $request->validate([
                'status' => ['required', Rule::in($this->statuses())],
                'cancellation_reason' => ['nullable', 'required_if:status,cancelled', 'string', 'max:2000'],
            ]);
            if ($validated['status'] !== 'cancelled') {
                $validated['cancellation_reason'] = null;
            }
            $reservation->update($validated);

            return back()->with('success', 'Status reservasi berhasil diperbarui.');
        }

        $validated = $this->validateReservation($request);
        $this->ensureTableIsAvailable($validated, $reservation);
        if ($validated['status'] !== 'cancelled') {
            $validated['cancellation_reason'] = null;
        }
        $reservation->update($validated);

        return redirect()->route('management.reservations.index')
            ->with('success', 'Reservasi berhasil diperbarui.');
    }

    public function destroy(TableReservation $reservation): RedirectResponse
    {
        if (! in_array($reservation->status, ['pending', 'cancelled'], true)) {
            return back()->with('error', 'Reservasi yang sedang diproses atau sudah selesai tidak boleh dihapus.');
        }

        $reservation->delete();
        return redirect()->route('management.reservations.index')
            ->with('success', 'Reservasi berhasil dihapus.');
    }

    private function validateReservation(Request $request): array
    {
        return $request->validate([
            'restaurant_table_id' => ['required', 'integer', 'exists:restaurant_tables,id'],
            'customer_name' => ['required', 'string', 'max:150'],
            'customer_phone' => ['required', 'string', 'max:30'],
            'customer_email' => ['nullable', 'email', 'max:255'],
            'guest_count' => ['required', 'integer', 'min:1', 'max:100'],
            'reservation_start' => ['required', 'date'],
            'reservation_end' => ['required', 'date', 'after:reservation_start'],
            'status' => ['required', Rule::in($this->statuses())],
            'special_request' => ['nullable', 'string', 'max:3000'],
            'cancellation_reason' => ['nullable', 'required_if:status,cancelled', 'string', 'max:2000'],
        ]);
    }

    private function ensureTableIsAvailable(array $data, ?TableReservation $ignore = null): void
    {
        $table = RestaurantTable::query()->findOrFail($data['restaurant_table_id']);

        if ($data['guest_count'] > $table->capacity) {
            throw ValidationException::withMessages([
                'guest_count' => "Jumlah tamu melebihi kapasitas meja ({$table->capacity} orang).",
            ]);
        }

        if ($data['status'] === 'cancelled') {
            return;
        }

        $conflict = TableReservation::query()
            ->where('restaurant_table_id', $data['restaurant_table_id'])
            ->whereNotIn('status', ['cancelled', 'no_show'])
            ->when($ignore, fn ($query) => $query->whereKeyNot($ignore->id))
            ->where('reservation_start', '<', $data['reservation_end'])
            ->where('reservation_end', '>', $data['reservation_start'])
            ->exists();

        if ($conflict) {
            throw ValidationException::withMessages([
                'restaurant_table_id' => 'Meja sudah memiliki reservasi pada rentang waktu tersebut.',
            ]);
        }
    }

    private function generateReservationNumber(): string
    {
        do {
            $number = 'RSV-'.now()->format('Ymd').'-'.strtoupper(Str::random(6));
        } while (TableReservation::query()->where('reservation_number', $number)->exists());

        return $number;
    }

    private function statuses(): array
    {
        return ['pending', 'confirmed', 'seated', 'completed', 'cancelled', 'no_show'];
    }
}
