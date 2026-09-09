<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymentController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $channel = $request->query('channel');
        $date = $request->query('date');

        $payments = Payment::query()
            ->with(['order:id,order_number,customer_name,grand_total', 'processedBy:id,name'])
            ->when($search !== '', fn ($query) => $query->where(function ($query) use ($search) {
                $query->where('payment_number', 'like', "%{$search}%")
                    ->orWhere('provider_transaction_id', 'like', "%{$search}%")
                    ->orWhereHas('order', fn ($order) => $order
                        ->where('order_number', 'like', "%{$search}%")
                        ->orWhere('customer_name', 'like', "%{$search}%"));
            }))
            ->when(in_array($status, ['pending', 'paid', 'failed', 'expired', 'refunded'], true),
                fn ($query) => $query->where('status', $status))
            ->when(in_array($channel, ['gateway', 'cashier'], true),
                fn ($query) => $query->where('payment_channel', $channel))
            ->when($date, fn ($query) => $query->whereDate('created_at', $date))
            ->latest()
            ->paginate(15)
            ->withQueryString();

        return view('admin.restaurant.payments.index', compact(
            'payments', 'search', 'status', 'channel', 'date'
        ));
    }

    public function show(Payment $payment): View
    {
        $payment->load([
            'order.restaurantTable',
            'order.items.components',
            'processedBy',
            'webhooks',
        ]);

        return view('admin.restaurant.payments.show', compact('payment'));
    }
}
