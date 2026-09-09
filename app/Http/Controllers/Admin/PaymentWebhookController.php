<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentWebhook;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PaymentWebhookController extends Controller
{
    public function index(Request $request): View
    {
        $validated = $request->validate([
            'search' => [
                'nullable',
                'string',
                'max:150',
            ],
            'provider' => [
                'nullable',
                'string',
                'max:50',
            ],
            'processing_status' => [
                'nullable',
                Rule::in([
                    'processed',
                    'unprocessed',
                ]),
            ],
            'date' => [
                'nullable',
                'date',
            ],
        ]);

        $search = trim(
            (string) ($validated['search'] ?? '')
        );

        $provider = $validated['provider'] ?? null;
        $processingStatus = $validated['processing_status'] ?? null;
        $date = $validated['date'] ?? null;

        $paymentWebhooks = PaymentWebhook::query()
            ->with([
                'payment:id,payment_number,order_id,status,amount',
                'payment.order:id,order_number,customer_name',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(function (Builder $query) use ($search): void {
                        $query
                            ->where(
                                'event_id',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'event_type',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhere(
                                'processing_message',
                                'like',
                                "%{$search}%"
                            )
                            ->orWhereHas(
                                'payment',
                                function (Builder $query) use ($search): void {
                                    $query->where(
                                        'payment_number',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            )
                            ->orWhereHas(
                                'payment.order',
                                function (Builder $query) use ($search): void {
                                    $query->where(
                                        'order_number',
                                        'like',
                                        "%{$search}%"
                                    );
                                }
                            );
                    });
                }
            )
            ->when(
                $provider,
                fn(Builder $query): Builder => $query->where(
                    'provider',
                    $provider
                )
            )
            ->when(
                $processingStatus === 'processed',
                fn(Builder $query): Builder => $query->where(
                    'is_processed',
                    true
                )
            )
            ->when(
                $processingStatus === 'unprocessed',
                fn(Builder $query): Builder => $query->where(
                    'is_processed',
                    false
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

        $providers = PaymentWebhook::query()
            ->select('provider')
            ->whereNotNull('provider')
            ->distinct()
            ->orderBy('provider')
            ->pluck('provider');

        $summary = [
            'total' => PaymentWebhook::query()->count(),

            'processed' => PaymentWebhook::query()
                ->where('is_processed', true)
                ->count(),

            'unprocessed' => PaymentWebhook::query()
                ->where('is_processed', false)
                ->count(),

            'today' => PaymentWebhook::query()
                ->whereDate('created_at', today())
                ->count(),
        ];

        return view(
            'admin.restaurant.payment-webhooks.index',
            compact(
                'paymentWebhooks',
                'providers',
                'summary',
                'search',
                'provider',
                'processingStatus',
                'date'
            )
        );
    }

    public function show(
        PaymentWebhook $paymentWebhook
    ): View {
        $paymentWebhook->load([
            'payment.processedBy',
            'payment.order.restaurantTable',
            'payment.order.createdBy',
        ]);

        return view(
            'admin.restaurant.payment-webhooks.show',
            compact('paymentWebhook')
        );
    }
}
