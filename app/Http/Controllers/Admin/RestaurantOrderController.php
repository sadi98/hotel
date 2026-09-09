<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuPackage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\RestaurantSetting;
use App\Models\RestaurantTable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RestaurantOrderController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $paymentStatus = $request->query('payment_status');
        $orderType = $request->query('order_type');
        $date = $request->query('date');

        $orders = Order::query()
            ->with([
                'restaurantTable:id,table_number,name,area',
                'user:id,name',
                'createdBy:id,name',
            ])
            ->when(
                $search !== '',
                function (Builder $query) use ($search): void {
                    $query->where(function (Builder $query) use ($search): void {
                        $query
                            ->where('order_number', 'like', "%{$search}%")
                            ->orWhere('customer_name', 'like', "%{$search}%")
                            ->orWhere('customer_phone', 'like', "%{$search}%")
                            ->orWhere('room_number', 'like', "%{$search}%");
                    });
                }
            )
            ->when(
                in_array($status, $this->statuses(), true),
                fn(Builder $query): Builder => $query->where('status', $status)
            )
            ->when(
                in_array($paymentStatus, $this->paymentStatuses(), true),
                fn(Builder $query): Builder => $query->where(
                    'payment_status',
                    $paymentStatus
                )
            )
            ->when(
                in_array($orderType, $this->orderTypes(), true),
                fn(Builder $query): Builder => $query->where(
                    'order_type',
                    $orderType
                )
            )
            ->when(
                $date,
                fn(Builder $query): Builder => $query->whereDate(
                    'ordered_at',
                    $date
                )
            )
            ->latest('ordered_at')
            ->paginate(15)
            ->withQueryString();

        $statuses = $this->statuses();

        return view(
            'admin.restaurant.orders.index',
            compact(
                'orders',
                'statuses',
                'search',
                'status',
                'paymentStatus',
                'orderType',
                'date'
            )
        );
    }

    public function create(): View
    {
        return view(
            'admin.restaurant.orders.create',
            $this->formData()
        );
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateOrder($request);
        $this->validateDestination($validated);
        $this->validatePaymentTiming($validated);

        $order = DB::transaction(function () use ($validated): Order {
            $calculation = $this->calculateOrder(
                $validated['items'],
                (float) ($validated['discount_amount'] ?? 0)
            );

            $order = Order::query()->create([
                'access_token' => (string) Str::uuid(),
                'order_number' => $this->generateOrderNumber(),
                'user_id' => null,
                'created_by' => auth()->id(),
                'restaurant_table_id' => $validated['order_type'] === 'dine_in'
                    ? $validated['restaurant_table_id']
                    : null,
                'ordered_from_table_qr' => false,
                'order_type' => $validated['order_type'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'room_number' => $validated['order_type'] === 'room_service'
                    ? $validated['room_number']
                    : null,
                'delivery_address' => $validated['order_type'] === 'delivery'
                    ? $validated['delivery_address']
                    : null,
                'guest_count' => $validated['guest_count'],
                'status' => 'pending',
                'payment_status' => 'unpaid',
                'payment_timing' => $validated['payment_timing'],
                'subtotal' => $calculation['subtotal'],
                'discount_amount' => $calculation['discount_amount'],
                'service_charge_percentage' => 0,
                'service_charge_amount' => 0,
                'tax_percentage' => $calculation['tax_percentage'],
                'tax_amount' => $calculation['tax_amount'],
                'grand_total' => $calculation['grand_total'],
                'customer_note' => $validated['customer_note'] ?? null,
                'internal_note' => $validated['internal_note'] ?? null,
                'ordered_at' => now(),
                'scheduled_at' => $validated['scheduled_at'] ?? null,
            ]);

            $this->saveOrderItems(
                $order,
                $calculation['items']
            );

            return $order;
        });

        return redirect()
            ->route('management.orders.show', $order)
            ->with('success', 'Pesanan berhasil dibuat.');
    }

    public function show(Order $order): View
    {
        $order->load([
            'restaurantTable',
            'user',
            'createdBy',
            'items.menuItem',
            'items.menuPackage',
            'items.components.menuItem',
            'payments.processedBy',
        ]);

        $statuses = $this->statuses();
        $itemStatuses = $this->itemStatuses();

        return view(
            'admin.restaurant.orders.show',
            compact(
                'order',
                'statuses',
                'itemStatuses'
            )
        );
    }

    public function edit(Order $order): View|RedirectResponse
    {
        if (!$this->canEdit($order)) {
            return redirect()
                ->route('management.orders.show', $order)
                ->with(
                    'error',
                    'Pesanan yang lunas, selesai, atau dibatalkan tidak dapat diedit.'
                );
        }

        $order->load('items');

        $formItems = $order->items
            ->map(function (OrderItem $item): array {
                return [
                    'item_type' => $item->item_type,
                    'item_id' => $item->item_type === 'package'
                        ? $item->menu_package_id
                        : $item->menu_item_id,
                    'quantity' => $item->quantity,
                    'note' => $item->note,
                ];
            })
            ->values()
            ->all();

        return view(
            'admin.restaurant.orders.edit',
            array_merge(
                $this->formData(),
                compact('order', 'formItems')
            )
        );
    }

    public function update(
        Request $request,
        Order $order
    ): RedirectResponse {
        if (!$this->canEdit($order)) {
            return redirect()
                ->route('management.orders.show', $order)
                ->with(
                    'error',
                    'Pesanan yang lunas, selesai, atau dibatalkan tidak dapat diedit.'
                );
        }

        $validated = $this->validateOrder($request);
        $this->validateDestination($validated);
        $this->validatePaymentTiming($validated);

        DB::transaction(function () use ($validated, $order): void {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if (!$this->canEdit($lockedOrder)) {
                throw ValidationException::withMessages([
                    'order' => 'Status pesanan berubah dan tidak dapat diedit.',
                ]);
            }

            $calculation = $this->calculateOrder(
                $validated['items'],
                (float) ($validated['discount_amount'] ?? 0)
            );

            $lockedOrder->update([
                'restaurant_table_id' => $validated['order_type'] === 'dine_in'
                    ? $validated['restaurant_table_id']
                    : null,
                'order_type' => $validated['order_type'],
                'customer_name' => $validated['customer_name'],
                'customer_phone' => $validated['customer_phone'],
                'customer_email' => $validated['customer_email'] ?? null,
                'room_number' => $validated['order_type'] === 'room_service'
                    ? $validated['room_number']
                    : null,
                'delivery_address' => $validated['order_type'] === 'delivery'
                    ? $validated['delivery_address']
                    : null,
                'guest_count' => $validated['guest_count'],
                'payment_timing' => $validated['payment_timing'],
                'subtotal' => $calculation['subtotal'],
                'discount_amount' => $calculation['discount_amount'],
                'service_charge_percentage' => 0,
                'service_charge_amount' => 0,
                'tax_percentage' => $calculation['tax_percentage'],
                'tax_amount' => $calculation['tax_amount'],
                'grand_total' => $calculation['grand_total'],
                'customer_note' => $validated['customer_note'] ?? null,
                'internal_note' => $validated['internal_note'] ?? null,
                'scheduled_at' => $validated['scheduled_at'] ?? null,
            ]);

            $lockedOrder->items()->delete();

            $this->saveOrderItems(
                $lockedOrder,
                $calculation['items']
            );
        });

        return redirect()
            ->route('management.orders.show', $order)
            ->with('success', 'Pesanan berhasil diperbarui.');
    }

    public function updateStatus(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in($this->statuses()),
            ],
            'internal_note' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'cancellation_reason' => [
                'nullable',
                'required_if:status,cancelled',
                'string',
                'max:2000',
            ],
        ]);

        if (
            in_array($order->status, ['completed', 'cancelled'], true)
            && $validated['status'] !== $order->status
        ) {
            return back()->with(
                'error',
                'Pesanan selesai atau dibatalkan tidak dapat dikembalikan.'
            );
        }

        $data = [
            'status' => $validated['status'],
            'internal_note' => $validated['internal_note']
                ?? $order->internal_note,
            'cancellation_reason' => $validated['status'] === 'cancelled'
                ? $validated['cancellation_reason']
                : null,
        ];

        if (
            $validated['status'] === 'confirmed'
            && !$order->confirmed_at
        ) {
            $data['confirmed_at'] = now();
        }

        if ($validated['status'] === 'completed') {
            $data['completed_at'] = now();
        }

        if ($validated['status'] === 'cancelled') {
            $data['cancelled_at'] = now();
        }

        $order->update($data);

        return back()->with(
            'success',
            'Status pesanan berhasil diperbarui.'
        );
    }

    public function updateItemStatus(
        Request $request,
        Order $order,
        OrderItem $orderItem
    ): RedirectResponse {
        abort_unless(
            (int) $orderItem->order_id === (int) $order->id,
            404
        );

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in($this->itemStatuses()),
            ],
        ]);

        DB::transaction(function () use (
            $orderItem,
            $validated
        ): void {
            $orderItem->update([
                'status' => $validated['status'],
            ]);

            if ($orderItem->item_type === 'package') {
                $orderItem->components()->update([
                    'status' => $validated['status'],
                ]);
            }
        });

        return back()->with(
            'success',
            'Status item berhasil diperbarui.'
        );
    }

    public function pay(
        Request $request,
        Order $order
    ): RedirectResponse {
        $validated = $request->validate([
            'payment_method' => [
                'required',
                Rule::in([
                    'cash',
                    'card',
                    'qris',
                    'ewallet',
                    'transfer',
                ]),
            ],
            'paid_amount' => [
                'required',
                'numeric',
                'min:0',
            ],
        ]);

        DB::transaction(function () use (
            $validated,
            $order
        ): void {
            $lockedOrder = Order::query()
                ->lockForUpdate()
                ->findOrFail($order->id);

            if ($lockedOrder->payment_status === 'paid') {
                throw ValidationException::withMessages([
                    'payment' => 'Pesanan ini sudah lunas.',
                ]);
            }

            if ($lockedOrder->status === 'cancelled') {
                throw ValidationException::withMessages([
                    'payment' => 'Pesanan yang dibatalkan tidak dapat dibayar.',
                ]);
            }

            $grandTotal = (float) $lockedOrder->grand_total;
            $paidAmount = (float) $validated['paid_amount'];

            if ($paidAmount < $grandTotal) {
                throw ValidationException::withMessages([
                    'paid_amount' => 'Nominal pembayaran kurang dari total tagihan.',
                ]);
            }

            Payment::query()->create([
                'order_id' => $lockedOrder->id,
                'processed_by' => auth()->id(),
                'payment_number' => $this->generatePaymentNumber(),
                'payment_channel' => 'cashier',
                'payment_method' => $validated['payment_method'],
                'provider' => 'manual',
                'provider_transaction_id' => null,
                'amount' => $grandTotal,
                'paid_amount' => $paidAmount,
                'change_amount' => max(
                    0,
                    $paidAmount - $grandTotal
                ),
                'status' => 'paid',
                'fraud_status' => null,
                'payment_url' => null,
                'snap_token' => null,
                'provider_response' => null,
                'note' => 'Pembayaran dicatat melalui kasir.',
                'expired_at' => null,
                'paid_at' => now(),
                'failed_at' => null,
                'refunded_at' => null,
            ]);

            $lockedOrder->update([
                'payment_status' => 'paid',
            ]);
        });

        return back()->with(
            'success',
            'Pembayaran kasir berhasil disimpan.'
        );
    }

    private function validateOrder(Request $request): array
    {
        return $request->validate([
            'customer_name' => [
                'required',
                'string',
                'max:150',
            ],
            'customer_phone' => [
                'required',
                'string',
                'max:30',
            ],
            'customer_email' => [
                'nullable',
                'email',
                'max:255',
            ],
            'order_type' => [
                'required',
                Rule::in($this->orderTypes()),
            ],
            'restaurant_table_id' => [
                'nullable',
                'integer',
                'exists:restaurant_tables,id',
            ],
            'room_number' => [
                'nullable',
                'string',
                'max:30',
            ],
            'delivery_address' => [
                'nullable',
                'string',
                'max:2000',
            ],
            'guest_count' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],
            'payment_timing' => [
                'required',
                Rule::in(['pay_now', 'pay_later']),
            ],
            'discount_amount' => [
                'nullable',
                'numeric',
                'min:0',
            ],
            'customer_note' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'internal_note' => [
                'nullable',
                'string',
                'max:3000',
            ],
            'scheduled_at' => [
                'nullable',
                'date',
            ],
            'items' => [
                'required',
                'array',
                'min:1',
            ],
            'items.*.item_type' => [
                'required',
                Rule::in(['single', 'package']),
            ],
            'items.*.item_id' => [
                'required',
                'integer',
                'min:1',
            ],
            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
                'max:999',
            ],
            'items.*.note' => [
                'nullable',
                'string',
                'max:1000',
            ],
        ]);
    }

    private function validateDestination(array $validated): void
    {
        $errors = [];

        if (
            $validated['order_type'] === 'dine_in'
            && empty($validated['restaurant_table_id'])
        ) {
            $errors['restaurant_table_id'] =
                'Meja restoran wajib dipilih untuk dine-in.';
        }

        if (
            $validated['order_type'] === 'room_service'
            && empty($validated['room_number'])
        ) {
            $errors['room_number'] =
                'Nomor kamar wajib diisi untuk room service.';
        }

        if (
            $validated['order_type'] === 'delivery'
            && empty($validated['delivery_address'])
        ) {
            $errors['delivery_address'] =
                'Alamat pengantaran wajib diisi untuk delivery.';
        }

        if ($errors !== []) {
            throw ValidationException::withMessages($errors);
        }
    }

    private function validatePaymentTiming(array $validated): void
    {
        if (
            $validated['order_type'] === 'delivery'
            && $validated['payment_timing'] === 'pay_later'
        ) {
            throw ValidationException::withMessages([
                'payment_timing' =>
                'Pesanan delivery harus menggunakan pembayaran sekarang.',
            ]);
        }
    }

    private function calculateOrder(
        array $requestedItems,
        float $discountAmount
    ): array {
        $menuItemIds = collect($requestedItems)
            ->where('item_type', 'single')
            ->pluck('item_id')
            ->unique()
            ->values();

        $packageIds = collect($requestedItems)
            ->where('item_type', 'package')
            ->pluck('item_id')
            ->unique()
            ->values();

        $menuItems = MenuItem::query()
            ->whereIn('id', $menuItemIds)
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        $packages = MenuPackage::query()
            ->with('items')
            ->whereIn('id', $packageIds)
            ->where('is_available', true)
            ->get()
            ->keyBy('id');

        $items = [];
        $subtotal = 0;

        foreach ($requestedItems as $requestedItem) {
            $type = $requestedItem['item_type'];
            $itemId = (int) $requestedItem['item_id'];
            $quantity = (int) $requestedItem['quantity'];

            if ($type === 'single') {
                $menuItem = $menuItems->get($itemId);

                if (!$menuItem) {
                    throw ValidationException::withMessages([
                        'items' =>
                        "Menu dengan ID {$itemId} tidak tersedia.",
                    ]);
                }

                $unitPrice = (float) $menuItem->price;
                $lineSubtotal = $unitPrice * $quantity;

                $items[] = [
                    'item_type' => 'single',
                    'menu_item_id' => $menuItem->id,
                    'menu_package_id' => null,
                    'item_name' => $menuItem->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'subtotal' => $lineSubtotal,
                    'note' => $requestedItem['note'] ?? null,
                    'components' => [],
                ];

                $subtotal += $lineSubtotal;

                continue;
            }

            $package = $packages->get($itemId);

            if (!$package) {
                throw ValidationException::withMessages([
                    'items' =>
                    "Paket dengan ID {$itemId} tidak tersedia.",
                ]);
            }

            if ($quantity < (int) $package->minimum_order) {
                throw ValidationException::withMessages([
                    'items' =>
                    "Minimal pemesanan paket {$package->name} adalah {$package->minimum_order}.",
                ]);
            }

            $unitPrice = (float) $package->package_price;
            $lineSubtotal = $unitPrice * $quantity;

            $components = $package->items
                ->map(function (MenuItem $menuItem) use ($quantity): array {
                    $quantityPerPackage = (int) $menuItem->pivot->quantity;

                    return [
                        'menu_item_id' => $menuItem->id,
                        'menu_name' => $menuItem->name,
                        'quantity_per_package' => $quantityPerPackage,
                        'total_quantity' => $quantityPerPackage * $quantity,
                        'note' => $menuItem->pivot->note,
                        'status' => 'pending',
                    ];
                })
                ->values()
                ->all();

            $items[] = [
                'item_type' => 'package',
                'menu_item_id' => null,
                'menu_package_id' => $package->id,
                'item_name' => $package->name,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $lineSubtotal,
                'note' => $requestedItem['note'] ?? null,
                'components' => $components,
            ];

            $subtotal += $lineSubtotal;
        }

        if ($discountAmount > $subtotal) {
            throw ValidationException::withMessages([
                'discount_amount' =>
                'Diskon tidak boleh melebihi subtotal pesanan.',
            ]);
        }

        $settings = RestaurantSetting::current();

        $taxPercentage = $settings->is_tax_active
            ? (float) $settings->tax_percentage
            : 0;

        $taxBase = max(0, $subtotal - $discountAmount);
        $taxAmount = $taxBase * ($taxPercentage / 100);
        $grandTotal = $taxBase + $taxAmount;

        return [
            'items' => $items,
            'subtotal' => round($subtotal, 2),
            'discount_amount' => round($discountAmount, 2),
            'tax_percentage' => round($taxPercentage, 2),
            'tax_amount' => round($taxAmount, 2),
            'grand_total' => round($grandTotal, 2),
        ];
    }

    private function saveOrderItems(
        Order $order,
        array $items
    ): void {
        foreach ($items as $itemData) {
            $components = $itemData['components'];
            unset($itemData['components']);

            $orderItem = $order->items()->create(
                array_merge(
                    $itemData,
                    ['status' => 'pending']
                )
            );

            if ($components !== []) {
                $orderItem->components()->createMany($components);
            }
        }
    }

    private function formData(): array
    {
        return [
            'menuItems' => MenuItem::query()
                ->where('is_available', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'menuPackages' => MenuPackage::query()
                ->where('is_available', true)
                ->orderBy('sort_order')
                ->orderBy('name')
                ->get(),
            'tables' => RestaurantTable::query()
                ->where('is_active', true)
                ->orderBy('table_number')
                ->get(),
            'settings' => RestaurantSetting::current(),
        ];
    }

    private function canEdit(Order $order): bool
    {
        return $order->payment_status !== 'paid'
            && !in_array(
                $order->status,
                ['completed', 'cancelled'],
                true
            );
    }

    private function generateOrderNumber(): string
    {
        do {
            $number = 'ORD-'
                . now()->format('YmdHis')
                . '-'
                . strtoupper(Str::random(4));
        } while (
            Order::query()
            ->where('order_number', $number)
            ->exists()
        );

        return $number;
    }

    private function generatePaymentNumber(): string
    {
        do {
            $number = 'PAY-'
                . now()->format('YmdHis')
                . '-'
                . strtoupper(Str::random(4));
        } while (
            Payment::query()
            ->where('payment_number', $number)
            ->exists()
        );

        return $number;
    }

    private function orderTypes(): array
    {
        return [
            'dine_in',
            'delivery',
            'room_service',
        ];
    }

    private function statuses(): array
    {
        return [
            'pending',
            'confirmed',
            'preparing',
            'ready',
            'served',
            'completed',
            'cancelled',
        ];
    }

    private function itemStatuses(): array
    {
        return [
            'pending',
            'preparing',
            'ready',
            'served',
            'cancelled',
        ];
    }

    private function paymentStatuses(): array
    {
        return [
            'unpaid',
            'pending',
            'paid',
            'failed',
            'refunded',
        ];
    }
}
