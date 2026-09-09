<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class RestaurantPaymentController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:150',
            ],
            'status' => [
                'nullable',
                Rule::in($this->statuses()),
            ],
            'payment_channel' => [
                'nullable',
                Rule::in($this->channels()),
            ],
            'payment_method' => [
                'nullable',
                Rule::in($this->methods()),
            ],
            'date' => [
                'nullable',
                'date',
            ],
        ]);

        $search = trim(
            (string) ($validated['search'] ?? '')
        );

        $status = $validated['status'] ?? null;
        $paymentChannel = $validated['payment_channel'] ?? null;
        $paymentMethod = $validated['payment_method'] ?? null;
        $date = $validated['date'] ?? null;

        $payments = Payment::query()
            ->with([
                'order:id,order_number,customer_name,customer_phone,order_type,grand_total,payment_status',
                'processedBy:id,name,username',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(function (Builder $query) use ($search): void {
                        $query
                            ->where(
                                'payment_number',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'provider_transaction_id',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'order',
                                function (Builder $query) use ($search): void {
                                    $query
                                        ->where(
                                            'order_number',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'customer_name',
                                            'like',
                                            "%{$search}%"
                                        )
                                        ->orWhere(
                                            'customer_phone',
                                            'like',
                                            "%{$search}%"
                                        );
                                }
                            );
                    });
                }
            )
            ->when(
                $status,
                fn(Builder $query): Builder => $query->where(
                    'status',
                    $status
                )
            )
            ->when(
                $paymentChannel,
                fn(Builder $query): Builder => $query->where(
                    'payment_channel',
                    $paymentChannel
                )
            )
            ->when(
                $paymentMethod,
                fn(Builder $query): Builder => $query->where(
                    'payment_method',
                    $paymentMethod
                )
            )
            ->when(
                $date,
                fn(Builder $query): Builder => $query->whereDate(
                    'created_at',
                    $date
                )
            )
            ->latest()
            ->paginate(15)
            ->withQueryString();

        $summary = [
            'total' => Payment::query()->count(),
            'paid' => Payment::query()
                ->where('status', 'paid')
                ->count(),
            'pending' => Payment::query()
                ->where('status', 'pending')
                ->count(),
            'paid_amount' => Payment::query()
                ->where('status', 'paid')
                ->sum('amount'),
        ];

        $statuses = $this->statuses();
        $channels = $this->channels();
        $methods = $this->methods();

        return view(
            'admin.restaurant.payments.index',
            compact(
                'payments',
                'summary',
                'statuses',
                'channels',
                'methods',
                'search',
                'status',
                'paymentChannel',
                'paymentMethod',
                'date'
            )
        );
    }

    public function show(Payment $payment): View
    {
        $payment->load([
            'order.restaurantTable',
            'order.user',
            'order.createdBy',
            'order.items.components',
            'processedBy',
            'webhooks',
        ]);

        return view(
            'admin.restaurant.payments.show',
            compact('payment')
        );
    }

    private function statuses(): array
    {
        return [
            'pending',
            'paid',
            'failed',
            'expired',
            'refunded',
        ];
    }

    private function channels(): array
    {
        return [
            'cashier',
            'gateway',
        ];
    }

    private function methods(): array
    {
        return [
            'cash',
            'card',
            'qris',
            'ewallet',
            'transfer',
        ];
    }
}
