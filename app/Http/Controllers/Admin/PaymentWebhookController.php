<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentWebhook;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentWebhookController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $provider = trim((string) $request->query('provider'));
        $processed = $request->query('processed');

        $webhooks = PaymentWebhook::query()
            ->with('payment:id,payment_number,order_id,status')
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('event_id', 'like', "%{$search}%")
                    ->orWhere('event_type', 'like', "%{$search}%");
            }))
            ->when($provider !== '', fn ($query) => $query->where('provider', $provider))
            ->when(in_array($processed, ['yes', 'no'], true),
                fn ($query) => $query->where('is_processed', $processed === 'yes'))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $providers = PaymentWebhook::query()->select('provider')->distinct()
            ->orderBy('provider')->pluck('provider');

        return view('admin.restaurant.payment-webhooks.index', compact(
            'webhooks', 'providers', 'search', 'provider', 'processed'
        ));
    }

    public function show(PaymentWebhook $paymentWebhook): View
    {
        $paymentWebhook->load('payment.order');
        return view('admin.restaurant.payment-webhooks.show', compact('paymentWebhook'));
    }
}
