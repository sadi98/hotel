<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Payment;
use App\Models\TableReservation;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $statistics = [
            'orders_today' => Order::query()
                ->whereDate('ordered_at', today())
                ->count(),

            'pending_orders' => Order::query()
                ->whereIn('status', [
                    'pending',
                    'confirmed',
                    'preparing',
                    'ready',
                ])
                ->count(),

            'revenue_today' => Payment::query()
                ->where('status', 'paid')
                ->whereDate('paid_at', today())
                ->sum('amount'),

            'reservations_today' => TableReservation::query()
                ->whereDate('reservation_start', today())
                ->whereNotIn('status', [
                    'cancelled',
                    'no_show',
                ])
                ->count(),

            'customers' => User::query()
                ->where('role', User::ROLE_USER)
                ->count(),

            'unavailable_menu' => MenuItem::query()
                ->where('is_available', false)
                ->count(),
        ];

        $recentOrders = Order::query()
            ->with([
                'restaurantTable:id,table_number',
            ])
            ->latest('ordered_at')
            ->limit(8)
            ->get();

        $upcomingReservations = TableReservation::query()
            ->with([
                'restaurantTable:id,table_number,name',
            ])
            ->whereIn('status', [
                'pending',
                'confirmed',
            ])
            ->where('reservation_start', '>=', now())
            ->orderBy('reservation_start')
            ->limit(6)
            ->get();

        $orderStatusLabels = [
            'Pending',
            'Confirmed',
            'Preparing',
            'Ready',
            'Served',
            'Completed',
            'Cancelled',
        ];

        $orderStatusValues = collect([
            'pending',
            'confirmed',
            'preparing',
            'ready',
            'served',
            'completed',
            'cancelled',
        ])->map(
            fn(string $status): int => Order::query()
                ->where('status', $status)
                ->count()
        )->values();

        $revenueLabels = [];
        $revenueValues = [];

        for ($month = 5; $month >= 0; $month--) {
            $date = now()->subMonths($month);

            $revenueLabels[] = $date->translatedFormat('M Y');

            $revenueValues[] = (float) Payment::query()
                ->where('status', 'paid')
                ->whereYear('paid_at', $date->year)
                ->whereMonth('paid_at', $date->month)
                ->sum('amount');
        }

        return view(
            'admin.dashboard.index',
            compact(
                'statistics',
                'recentOrders',
                'upcomingReservations',
                'orderStatusLabels',
                'orderStatusValues',
                'revenueLabels',
                'revenueValues'
            )
        );
    }
}
