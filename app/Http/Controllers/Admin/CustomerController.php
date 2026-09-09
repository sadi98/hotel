<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\TableReservation;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:150',
            ],
            'gender' => [
                'nullable',
                Rule::in([
                    'male',
                    'female',
                ]),
            ],
            'account_type' => [
                'nullable',
                Rule::in([
                    'regular',
                    'google',
                ]),
            ],
            'registration_date' => [
                'nullable',
                'date',
            ],
        ]);

        $search = trim(
            (string) ($validated['search'] ?? '')
        );

        $gender = $validated['gender'] ?? null;
        $accountType = $validated['account_type'] ?? null;
        $registrationDate = $validated['registration_date'] ?? null;

        $customers = User::query()
            ->where('role', User::ROLE_USER)
            ->withCount([
                'orders',
                'tableReservations',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(
                        function (Builder $query) use ($search): void {
                            $query
                                ->where(
                                    'name',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'username',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'email',
                                    'like',
                                    "%{$search}%"
                                )
                                ->orWhere(
                                    'phone',
                                    'like',
                                    "%{$search}%"
                                );
                        }
                    );
                }
            )
            ->when(
                $gender,
                fn(Builder $query): Builder => $query->where(
                    'gender',
                    $gender
                )
            )
            ->when(
                $accountType === 'google',
                fn(Builder $query): Builder => $query->whereNotNull(
                    'google_id'
                )
            )
            ->when(
                $accountType === 'regular',
                fn(Builder $query): Builder => $query->whereNull(
                    'google_id'
                )
            )
            ->when(
                $registrationDate,
                fn(Builder $query): Builder => $query->whereDate(
                    'created_at',
                    $registrationDate
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total' => User::query()
                ->where('role', User::ROLE_USER)
                ->count(),

            'google' => User::query()
                ->where('role', User::ROLE_USER)
                ->whereNotNull('google_id')
                ->count(),

            'verified' => User::query()
                ->where('role', User::ROLE_USER)
                ->whereNotNull('email_verified_at')
                ->count(),

            'new_this_month' => User::query()
                ->where('role', User::ROLE_USER)
                ->whereYear('created_at', now()->year)
                ->whereMonth('created_at', now()->month)
                ->count(),
        ];

        return view(
            'admin.customers.index',
            compact(
                'customers',
                'summary',
                'search',
                'gender',
                'accountType',
                'registrationDate'
            )
        );
    }

    public function show(User $user): View
    {
        $this->ensureCustomer($user);

        $orders = Order::query()
            ->where('user_id', $user->id)
            ->with([
                'restaurantTable:id,table_number,name,area',
            ])
            ->latest('ordered_at')
            ->paginate(
                10,
                ['*'],
                'orders_page'
            )
            ->withQueryString();

        $tableReservations = TableReservation::query()
            ->where('user_id', $user->id)
            ->with([
                'restaurantTable:id,table_number,name,area',
            ])
            ->latest('reservation_start')
            ->paginate(
                10,
                ['*'],
                'reservations_page'
            )
            ->withQueryString();

        $statistics = [
            'total_orders' => Order::query()
                ->where('user_id', $user->id)
                ->count(),

            'completed_orders' => Order::query()
                ->where('user_id', $user->id)
                ->where('status', 'completed')
                ->count(),

            'total_spending' => Order::query()
                ->where('user_id', $user->id)
                ->where('payment_status', 'paid')
                ->sum('grand_total'),

            'total_reservations' => TableReservation::query()
                ->where('user_id', $user->id)
                ->count(),
        ];

        return view(
            'admin.customers.show',
            compact(
                'user',
                'orders',
                'tableReservations',
                'statistics'
            )
        );
    }

    private function ensureCustomer(User $user): void
    {
        abort_unless(
            $user->isUser(),
            404
        );
    }
}
