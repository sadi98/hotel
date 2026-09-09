<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TableReservation;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));

        $customers = User::query()
            ->where('role', 'user')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('name', 'like', "%{$search}%")
                    ->orWhere('username', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%");
            }))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function show(User $user): View
    {
        abort_unless($user->role === 'user', 404);

        $orders = Order::query()->where('user_id', $user->id)
            ->latest('ordered_at')->paginate(10, ['*'], 'orders_page');
        $reservations = TableReservation::query()->with('restaurantTable')
            ->where('user_id', $user->id)
            ->latest('reservation_start')->paginate(10, ['*'], 'reservations_page');

        return view('admin.customers.show', compact('user', 'orders', 'reservations'));
    }
}
