<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\MenuPackage;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\OrderItemComponent;
use App\Models\Payment;
use App\Models\RestaurantSetting;
use App\Models\RestaurantTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class RestaurantOrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan.
     */
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search'));
        $status = $request->query('status');
        $paymentStatus = $request->query('payment_status');
        $orderType = $request->query('order_type');
        $date = $request->query('date');

        $orders = Order::query()
            ->with([
                'restaurantTable:id,table_number',
                'user:id,name',
                'createdBy:id,name',
            ])
            ->when(
                $search !== '',
                fn($query) => $query->where(
                    function ($query) use ($search) {
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
                            )
                            ->orWhere(
                                'room_number',
                                'like',
                                "%{$search}%"
                            );
                    }
                )
            )
            ->when(
                in_array($status, $this->statuses(), true),
                fn($query) => $query->where('status', $status)
            )
            ->when(
                in_array(
                    $paymentStatus,
                    [
                        'unpaid',
                        'pending',
                        'paid',
                        'failed',
                        'refunded',
                    ],
                    true
                ),
                fn($query) => $query->where(
                    'payment_status',
                    $paymentStatus
                )
            )
            ->when(
                in_array(
                    $orderType,
                    [
                        'dine_in',
                        'delivery',
                        'room_service',
                    ],
                    true
                ),
                fn($query) => $query->where(
                    'order_type',
                    $orderType
                )
            )
            ->when(
                $date,
                fn($query) => $query->whereDate(
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

    /**
     * Menampilkan form pesanan kasir.
     */
    public function create(): View
    {
        return view(
            'admin.restaurant.orders.create',
            $this->orderFormData()
        );
    }

    /**
     * Menyimpan pesanan baru dari Admin atau Staff.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateOrder($request);

        $settings = RestaurantSetting::current();

        $this->validateOrderDestination(
            $validated,
            $settings
        );

        $order = DB::transaction(
            function () use ($validated, $settings) {
                $calculated = $this->calculateOrder(
                    $validated['items'],
                    (float) $validated['discount_amount'],
                    $settings
                );

                $order = Order::create([
                    'access_token' => (string) Str::uuid(),

                    'order_number' => $this->generateOrderNumber(
                        $settings
                    ),

                    'user_id' => null,

                    'created_by' => auth()->id(),

                    'restaurant_table_id' =>
                    $validated['order_type'] === 'dine_in'
                        ? $validated['restaurant_table_id']
                        : null,

                    'ordered_from_table_qr' => false,

                    'order_type' => $validated['order_type'],

                    'customer_name' => $validated['customer_name'],

                    'customer_phone' => $validated['customer_phone'],

                    'customer_email' =>
                    $validated['customer_email'] ?? null,

                    'room_number' =>
                    $validated['order_type'] === 'room_service'
                        ? $validated['room_number']
                        : null,

                    'delivery_address' =>
                    $validated['order_type'] === 'delivery'
                        ? $validated['delivery_address']
                        : null,

                    'guest_count' => $validated['guest_count'],

                    'status' => 'pending',

                    'payment_status' => 'unpaid',

                    'payment_timing' =>
                    $validated['payment_timing'],

                    'subtotal' => $calculated['subtotal'],

                    'discount_amount' =>
                    $calculated['discount_amount'],

                    'service_charge_percentage' =>
                    $calculated['service_charge_percentage'],

                    'service_charge_amount' =>
                    $calculated['service_charge_amount'],

                    'tax_percentage' =>
                    $calculated['tax_percentage'],

                    'tax_amount' =>
                    $calculated['tax_amount'],

                    'grand_total' =>
                    $calculated['grand_total'],

                    'customer_note' =>
                    $validated['customer_note'] ?? null,

                    'internal_note' =>
                    $validated['internal_note'] ?? null,

                    'ordered_at' => now(),

                    'scheduled_at' =>
                    $validated['scheduled_at'] ?? null,
                ]);

                $this->saveOrderItems(
                    $order,
                    $calculated['items']
                );

                return $order;
            }
        );

        return redirect()
            ->route('management.orders.show', $order)
            ->with(
                'success',
                'Pesanan kasir berhasil dibuat.'
            );
    }

    /**
     * Menampilkan detail pesanan.
     */
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

    /**
     * Menampilkan form edit pesanan.
     */
    public function edit(Order $order): View|RedirectResponse
    {
        if ($order->payment_status === 'paid') {
            return redirect()
                ->route('management.orders.show', $order)
                ->with(
                    'error',
                    'Pesanan yang sudah lunas tidak dapat diubah.'
                );
        }

        if (
            in_array(
                $order->status,
                ['completed', 'cancelled'],
                true
            )
        ) {
            return redirect()
                ->route('management.orders.show', $order)
                ->with(
                    'error',
                    'Pesanan yang selesai atau dibatalkan tidak dapat diubah.'
                );
        }

        $order->load([
            'items',
        ]);

        $formItems = $order->items
            ->map(function (OrderItem $item) {
                return [
                    'item_type' => $item->item_type,

                    'item_id' =>
                    $item->item_type === 'package'
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
                $this->orderFormData(),
                [
                    'order' => $order,
                    'formItems' => $formItems,
                ]
            )
        );
    }

    /**
     * Memperbarui pesanan beserta item.
     */
    public function update(
        Request $request,
        Order $order
    ): RedirectResponse {
        if ($order->payment_status === 'paid') {
            return back()->with(
                'error',
                'Pesanan yang sudah lunas tidak dapat diubah.'
            );
        }

        if (
            in_array(
                $order->status,
                ['completed', 'cancelled'],
                true
            )
        ) {
            return back()->with(
                'error',
                'Pesanan yang selesai atau dibatalkan tidak dapat diubah.'
            );
        }

        $validated = $this->validateOrder($request);

        $settings = RestaurantSetting::current();

        $this->validateOrderDestination(
            $validated,
            $settings
        );

        DB::transaction(
            function () use (
                $validated,
                $settings,
                $order
            ) {
                $calculated = $this->calculateOrder(
                    $validated['items'],
                    (float) $validated['discount_amount'],
                    $settings
                );

                $order->update([
                    'restaurant_table_id' =>
                    $validated['order_type'] === 'dine_in'
                        ? $validated['restaurant_table_id']
                        : null,

                    'ordered_from_table_qr' => false,

                    'order_type' => $validated['order_type'],

                    'customer_name' => $validated['customer_name'],

                    'customer_phone' => $validated['customer_phone'],

                    'customer_email' =>
                    $validated['customer_email'] ?? null,

                    'room_number' =>
                    $validated['order_type'] === 'room_service'
                        ? $validated['room_number']
                        : null,

                    'delivery_address' =>
                    $validated['order_type'] === 'delivery'
                        ? $validated['delivery_address']
                        : null,

                    'guest_count' => $validated['guest_count'],

                    'payment_timing' =>
                    $validated['payment_timing'],

                    'subtotal' => $calculated['subtotal'],

                    'discount_amount' =>
                    $calculated['discount_amount'],

                    'service_charge_percentage' =>
                    $calculated['service_charge_percentage'],

                    'service_charge_amount' =>
                    $calculated['service_charge_amount'],

                    'tax_percentage' =>
                    $calculated['tax_percentage'],

                    'tax_amount' =>
                    $calculated['tax_amount'],

                    'grand_total' =>
                    $calculated['grand_total'],

                    'customer_note' =>
                    $validated['customer_note'] ?? null,

                    'internal_note' =>
                    $validated['internal_note'] ?? null,

                    'scheduled_at' =>
                    $validated['scheduled_at'] ?? null,
                ]);

                OrderItem::query()
                    ->where('order_id', $order->id)
                    ->delete();

                $this->saveOrderItems(
                    $order,
                    $calculated['items']
                );
            }
        );

        return redirect()
            ->route('management.orders.show', $order)
            ->with(
                'success',
                'Pesanan berhasil diperbarui.'
            );
    }

    /**
     * Memperbarui status pesanan.
     */
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
            in_array(
                $order->status,
                ['completed', 'cancelled'],
                true
            )
            && $validated['status'] !== $order->status
        ) {
            return back()->with(
                'error',
                'Order yang selesai atau dibatalkan tidak dapat dikembalikan ke status sebelumnya.'
            );
        }

        $timestamps = [
            'confirmed' => 'confirmed_at',
            'completed' => 'completed_at',
            'cancelled' => 'cancelled_at',
        ];

        $data = [
            'status' => $validated['status'],

            'internal_note' =>
            $validated['internal_note']
                ?? $order->internal_note,

            'cancellation_reason' =>
            $validated['status'] === 'cancelled'
                ? $validated['cancellation_reason']
                : null,
        ];

        if (isset($timestamps[$validated['status']])) {
            $data[$timestamps[$validated['status']]] = now();
        }

        $order->update($data);

        return back()->with(
            'success',
            'Status pesanan berhasil diperbarui.'
        );
    }

    /**
     * Memperbarui status satu item pesanan.
     */
    public function updateItemStatus(
        Request $request,
        Order $order,
        OrderItem $orderItem
    ): RedirectResponse {
        if ($orderItem->order_id !== $order->id) {
            abort(404);
        }

        $validated = $request->validate([
            'status' => [
                'required',
                Rule::in($this->itemStatuses()),
            ],
        ]);

        DB::transaction(
            function () use ($orderItem, $validated) {
                $orderItem->update([
                    'status' => $validated['status'],
                ]);

                if ($orderItem->item_type === 'package') {
                    $orderItem
                        ->components()
                        ->update([
                            'status' => $validated['status'],
                        ]);
                }
            }
        );

        return back()->with(
            'success',
            'Status item berhasil diperbarui.'
        );
    }

    /**
     * Menyimpan pembayaran kasir.
     */
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

        if ($order->payment_status === 'paid') {
            return back()->with(
                'error',
                'Pesanan ini sudah lunas.'
            );
        }

        if ($order->status === 'cancelled') {
            return back()->with(
                'error',
                'Pesanan yang dibatalkan tidak dapat dibayar.'
            );
        }

        if (
            (float) $validated['paid_amount']
            < (float) $order->grand_total
        ) {
            throw ValidationException::withMessages([
                'paid_amount' =>
                'Nominal pembayaran kurang dari total tagihan.',
            ]);
        }

        $settings = RestaurantSetting::current();

        DB::transaction(
            function () use (
                $validated,
                $order,
                $settings
            ) {
                $paidAmount =
                    (float) $validated['paid_amount'];

                $amount =
                    (float) $order->grand_total;

                Payment::create([
                    'order_id' => $order->id,

                    'processed_by' => auth()->id(),

                    'payment_number' =>
                    $this->generatePaymentNumber(
                        $settings
                    ),

                    'payment_channel' => 'cashier',

                    'payment_method' =>
                    $validated['payment_method'],

                    'provider' => 'manual',

                    'amount' => $amount,

                    'paid_amount' => $paidAmount,

                    'change_amount' => max(
                        0,
                        $paidAmount - $amount
                    ),

                    'status' => 'paid',

                    'paid_at' => now(),
                ]);

                $order->update([
                    'payment_status' => 'paid',
                ]);
            }
        );

        return back()->with(
            'success',
            'Pembayaran kasir berhasil disimpan.'
        );
    }

    /**
     * Validasi form pesanan.
     */
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
                Rule::in([
                    'dine_in',
                    'delivery',
                    'room_service',
                ]),
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
                'max:3000',
            ],

            'guest_count' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],

            'payment_timing' => [
                'required',
                Rule::in([
                    'pay_now',
                    'pay_later',
                ]),
            ],

            'discount_amount' => [
                'required',
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
                Rule::in([
                    'single',
                    'package',
                ]),
            ],

            'items.*.item_id' => [
                'required',
                'integer',
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
                'max:2000',
            ],
        ]);
    }

    /**
     * Validasi tujuan dan metode bayar nanti.
     */
    private function validateOrderDestination(
        array $validated,
        RestaurantSetting $settings
    ): void {
        if (
            $validated['order_type'] === 'dine_in'
            && empty($validated['restaurant_table_id'])
        ) {
            throw ValidationException::withMessages([
                'restaurant_table_id' =>
                'Meja wajib dipilih untuk pesanan dine-in.',
            ]);
        }

        if (
            $validated['order_type'] === 'room_service'
            && empty($validated['room_number'])
        ) {
            throw ValidationException::withMessages([
                'room_number' =>
                'Nomor kamar wajib diisi untuk room service.',
            ]);
        }

        if (
            $validated['order_type'] === 'delivery'
            && empty($validated['delivery_address'])
        ) {
            throw ValidationException::withMessages([
                'delivery_address' =>
                'Alamat pengiriman wajib diisi untuk delivery.',
            ]);
        }

        if (
            $validated['payment_timing'] === 'pay_later'
            && ! $settings->allowsPayLater(
                $validated['order_type']
            )
        ) {
            throw ValidationException::withMessages([
                'payment_timing' =>
                'Bayar nanti tidak diizinkan untuk jenis pesanan ini.',
            ]);
        }

        if (
            $validated['order_type'] === 'dine_in'
            && ! empty($validated['restaurant_table_id'])
        ) {
            $table = RestaurantTable::query()
                ->whereKey(
                    $validated['restaurant_table_id']
                )
                ->where('is_active', true)
                ->first();

            if (! $table) {
                throw ValidationException::withMessages([
                    'restaurant_table_id' =>
                    'Meja tidak tersedia atau sudah dinonaktifkan.',
                ]);
            }

            if (
                (int) $validated['guest_count']
                > (int) $table->capacity
            ) {
                throw ValidationException::withMessages([
                    'guest_count' =>
                    "Jumlah tamu melebihi kapasitas meja {$table->capacity} orang.",
                ]);
            }
        }
    }

    /**
     * Mengambil dan menghitung seluruh item.
     */
    private function calculateOrder(
        array $requestedItems,
        float $discountAmount,
        RestaurantSetting $settings
    ): array {
        $calculatedItems = [];
        $subtotal = 0;

        foreach ($requestedItems as $index => $requestedItem) {
            $itemType = $requestedItem['item_type'];
            $itemId = (int) $requestedItem['item_id'];
            $quantity = (int) $requestedItem['quantity'];

            if ($itemType === 'single') {
                $menuItem = MenuItem::query()
                    ->whereKey($itemId)
                    ->where('is_available', true)
                    ->first();

                if (! $menuItem) {
                    throw ValidationException::withMessages([
                        "items.{$index}.item_id" =>
                        'Menu tidak ditemukan atau sedang tidak tersedia.',
                    ]);
                }

                $unitPrice = (float) $menuItem->price;
                $itemSubtotal = round(
                    $unitPrice * $quantity,
                    2
                );

                $calculatedItems[] = [
                    'item_type' => 'single',
                    'menu_item_id' => $menuItem->id,
                    'menu_package_id' => null,
                    'item_name' => $menuItem->name,
                    'unit_price' => $unitPrice,
                    'quantity' => $quantity,
                    'subtotal' => $itemSubtotal,
                    'note' => $requestedItem['note'] ?? null,
                    'components' => [],
                ];

                $subtotal += $itemSubtotal;

                continue;
            }

            $menuPackage = MenuPackage::query()
                ->with('items')
                ->whereKey($itemId)
                ->where('is_available', true)
                ->where(
                    function ($query) {
                        $query
                            ->whereNull('available_from')
                            ->orWhere(
                                'available_from',
                                '<=',
                                now()
                            );
                    }
                )
                ->where(
                    function ($query) {
                        $query
                            ->whereNull('available_until')
                            ->orWhere(
                                'available_until',
                                '>=',
                                now()
                            );
                    }
                )
                ->first();

            if (! $menuPackage) {
                throw ValidationException::withMessages([
                    "items.{$index}.item_id" =>
                    'Paket tidak ditemukan atau sedang tidak tersedia.',
                ]);
            }

            if ($quantity < $menuPackage->minimum_order) {
                throw ValidationException::withMessages([
                    "items.{$index}.quantity" =>
                    "Minimum pemesanan paket ini adalah {$menuPackage->minimum_order}.",
                ]);
            }

            if ($menuPackage->items->isEmpty()) {
                throw ValidationException::withMessages([
                    "items.{$index}.item_id" =>
                    'Paket belum memiliki komponen menu.',
                ]);
            }

            $unitPrice =
                (float) $menuPackage->package_price;

            $itemSubtotal = round(
                $unitPrice * $quantity,
                2
            );

            $components = $menuPackage->items
                ->map(function (
                    MenuItem $component
                ) use ($quantity) {
                    $quantityPerPackage =
                        (int) $component->pivot->quantity;

                    return [
                        'menu_item_id' => $component->id,

                        'menu_name' => $component->name,

                        'quantity_per_package' =>
                        $quantityPerPackage,

                        'total_quantity' =>
                        $quantityPerPackage * $quantity,

                        'note' =>
                        $component->pivot->note ?? null,
                    ];
                })
                ->values()
                ->all();

            $calculatedItems[] = [
                'item_type' => 'package',
                'menu_item_id' => null,
                'menu_package_id' => $menuPackage->id,
                'item_name' => $menuPackage->name,
                'unit_price' => $unitPrice,
                'quantity' => $quantity,
                'subtotal' => $itemSubtotal,
                'note' => $requestedItem['note'] ?? null,
                'components' => $components,
            ];

            $subtotal += $itemSubtotal;
        }

        $subtotal = round($subtotal, 2);

        if ($discountAmount > $subtotal) {
            throw ValidationException::withMessages([
                'discount_amount' =>
                'Diskon tidak boleh melebihi subtotal.',
            ]);
        }

        $maximumDiscount = round(
            $subtotal
                * (
                    (float) $settings
                        ->maximum_discount_percentage
                    / 100
                ),
            2
        );

        if ($discountAmount > $maximumDiscount) {
            throw ValidationException::withMessages([
                'discount_amount' =>
                'Diskon melebihi batas maksimal '
                    . $settings->maximum_discount_percentage
                    . '%.',
            ]);
        }

        $discountAmount = round(
            $discountAmount,
            2
        );

        $afterDiscount = max(
            0,
            $subtotal - $discountAmount
        );

        $serviceChargePercentage =
            $settings->activeServiceChargePercentage();

        $serviceChargeAmount = round(
            $afterDiscount
                * ($serviceChargePercentage / 100),
            2
        );

        $taxPercentage =
            $settings->activeTaxPercentage();

        $taxBase =
            $afterDiscount + $serviceChargeAmount;

        $taxAmount = round(
            $taxBase * ($taxPercentage / 100),
            2
        );

        $grandTotal = round(
            $afterDiscount
                + $serviceChargeAmount
                + $taxAmount,
            2
        );

        return [
            'items' => $calculatedItems,
            'subtotal' => $subtotal,
            'discount_amount' => $discountAmount,
            'service_charge_percentage' =>
            $serviceChargePercentage,
            'service_charge_amount' =>
            $serviceChargeAmount,
            'tax_percentage' => $taxPercentage,
            'tax_amount' => $taxAmount,
            'grand_total' => $grandTotal,
        ];
    }

    /**
     * Menyimpan snapshot item dan komponen paket.
     */
    private function saveOrderItems(
        Order $order,
        array $calculatedItems
    ): void {
        foreach ($calculatedItems as $calculatedItem) {
            $orderItem = OrderItem::create([
                'order_id' => $order->id,

                'item_type' =>
                $calculatedItem['item_type'],

                'menu_item_id' =>
                $calculatedItem['menu_item_id'],

                'menu_package_id' =>
                $calculatedItem['menu_package_id'],

                'item_name' =>
                $calculatedItem['item_name'],

                'unit_price' =>
                $calculatedItem['unit_price'],

                'quantity' =>
                $calculatedItem['quantity'],

                'subtotal' =>
                $calculatedItem['subtotal'],

                'note' =>
                $calculatedItem['note'],

                'status' => 'pending',
            ]);

            foreach (
                $calculatedItem['components']
                as $component
            ) {
                OrderItemComponent::create([
                    'order_item_id' => $orderItem->id,

                    'menu_item_id' =>
                    $component['menu_item_id'],

                    'menu_name' =>
                    $component['menu_name'],

                    'quantity_per_package' =>
                    $component['quantity_per_package'],

                    'total_quantity' =>
                    $component['total_quantity'],

                    'note' => $component['note'],

                    'status' => 'pending',
                ]);
            }
        }
    }

    /**
     * Data yang dibutuhkan form create dan edit.
     */
    private function orderFormData(): array
    {
        $menuItems = MenuItem::query()
            ->with('category:id,name,menu_type')
            ->where('is_available', true)
            ->orderBy('name')
            ->get();

        $menuPackages = MenuPackage::query()
            ->with('items:id,name')
            ->where('is_available', true)
            ->where(
                function ($query) {
                    $query
                        ->whereNull('available_from')
                        ->orWhere(
                            'available_from',
                            '<=',
                            now()
                        );
                }
            )
            ->where(
                function ($query) {
                    $query
                        ->whereNull('available_until')
                        ->orWhere(
                            'available_until',
                            '>=',
                            now()
                        );
                }
            )
            ->orderBy('name')
            ->get();

        $tables = RestaurantTable::query()
            ->where('is_active', true)
            ->orderBy('area')
            ->orderBy('table_number')
            ->get();

        $settings = RestaurantSetting::current();

        return compact(
            'menuItems',
            'menuPackages',
            'tables',
            'settings'
        );
    }

    /**
     * Membuat nomor order unik.
     */
    private function generateOrderNumber(
        RestaurantSetting $settings
    ): string {
        do {
            $number =
                strtoupper($settings->order_number_prefix)
                . '-'
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

    /**
     * Membuat nomor pembayaran unik.
     */
    private function generatePaymentNumber(
        RestaurantSetting $settings
    ): string {
        do {
            $number =
                strtoupper($settings->payment_number_prefix)
                . '-'
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

    /**
     * Status pesanan.
     */
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

    /**
     * Status item pesanan.
     */
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
}
