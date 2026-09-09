@php
    $isEdit = isset($order) && $order;
    $currentOrder = $isEdit ? $order : null;

    $initialItems = old(
        'items',
        $isEdit
            ? $formItems
            : [
                [
                    'item_type' => 'single',
                    'item_id' => '',
                    'quantity' => 1,
                    'note' => '',
                ],
            ],
    );

    $menuItemOptions = $menuItems
        ->map(
            fn($item) => [
                'id' => $item->id,
                'type' => 'single',
                'sku' => $item->sku,
                'name' => $item->name,
                'price' => (float) $item->price,
                'minimum_order' => 1,
            ],
        )
        ->values();

    $packageOptions = $menuPackages
        ->map(
            fn($package) => [
                'id' => $package->id,
                'type' => 'package',
                'sku' => $package->sku,
                'name' => $package->name,
                'price' => (float) $package->package_price,
                'minimum_order' => (int) $package->minimum_order,
            ],
        )
        ->values();

    $selectedOrderType = old('order_type', $currentOrder?->order_type ?? 'dine_in');

    $selectedPaymentTiming = old('payment_timing', $currentOrder?->payment_timing ?? 'pay_now');
@endphp

@if ($errors->any())
    <div class="alert alert-danger alert-dismissible fade show shadow-sm">
        <strong>Pesanan belum dapat disimpan.</strong>

        <ul class="mb-0 mt-2">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>

        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<form id="orderForm"
    action="{{ $isEdit ? route('management.orders.update', $currentOrder) : route('management.orders.store') }}"
    method="POST">
    @csrf

    @if ($isEdit)
        @method('PUT')
    @endif

    <div class="row">
        <div class="col-xl-8">
            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i data-feather="user" class="me-2"></i>
                    Informasi Pelanggan
                </div>

                <div class="card-body">
                    <div class="row gx-3">
                        <div class="col-md-6 mb-3">
                            <label for="customer_name" class="form-label">
                                Nama Pelanggan
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="customer_name" id="customer_name"
                                class="form-control @error('customer_name') is-invalid @enderror"
                                value="{{ old('customer_name', $currentOrder?->customer_name) }}"
                                maxlength="150" required>

                            @error('customer_name')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="customer_phone" class="form-label">
                                Nomor Telepon
                                <span class="text-danger">*</span>
                            </label>

                            <input type="text" name="customer_phone" id="customer_phone"
                                class="form-control @error('customer_phone') is-invalid @enderror"
                                value="{{ old('customer_phone', $currentOrder?->customer_phone) }}"
                                maxlength="30" required>

                            @error('customer_phone')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="customer_email" class="form-label">
                                Email
                            </label>

                            <input type="email" name="customer_email" id="customer_email"
                                class="form-control @error('customer_email') is-invalid @enderror"
                                value="{{ old('customer_email', $currentOrder?->customer_email) }}">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="guest_count" class="form-label">
                                Jumlah Tamu
                            </label>

                            <input type="number" name="guest_count" id="guest_count" class="form-control"
                                value="{{ old('guest_count', $currentOrder?->guest_count ?? 1) }}"
                                min="1" max="100" required>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i data-feather="map-pin" class="me-2"></i>
                    Jenis dan Tujuan Pesanan
                </div>

                <div class="card-body">
                    <div class="row gx-3">
                        <div class="col-md-6 mb-3">
                            <label for="order_type" class="form-label">
                                Jenis Pesanan
                            </label>

                            <select name="order_type" id="order_type" class="form-select" required>
                                <option value="dine_in" @selected($selectedOrderType === 'dine_in')>
                                    Dine In
                                </option>

                                <option value="delivery" @selected($selectedOrderType === 'delivery')>
                                    Delivery
                                </option>

                                <option value="room_service" @selected($selectedOrderType === 'room_service')>
                                    Room Service
                                </option>
                            </select>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="payment_timing" class="form-label">
                                Waktu Pembayaran
                            </label>

                            <select name="payment_timing" id="payment_timing" class="form-select" required>
                                <option value="pay_now" @selected($selectedPaymentTiming === 'pay_now')>
                                    Bayar Sekarang
                                </option>

                                <option value="pay_later" @selected($selectedPaymentTiming === 'pay_later')>
                                    Bayar Nanti
                                </option>
                            </select>

                            <div id="paymentHelp" class="form-text"></div>
                        </div>

                        <div id="tableField" class="col-md-6 mb-3">
                            <label for="restaurant_table_id" class="form-label">
                                Meja Restoran
                            </label>

                            <select name="restaurant_table_id" id="restaurant_table_id" class="form-select">
                                <option value="">Pilih meja</option>

                                @foreach ($tables as $table)
                                    <option value="{{ $table->id }}" @selected((string) old('restaurant_table_id', $currentOrder?->restaurant_table_id) === (string) $table->id)>
                                        {{ $table->table_number }}
                                        {{ $table->name ? '- ' . $table->name : '' }}
                                        ({{ $table->capacity }} orang)
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div id="roomField" class="col-md-6 mb-3 d-none">
                            <label for="room_number" class="form-label">
                                Nomor Kamar
                            </label>

                            <input type="text" name="room_number" id="room_number" class="form-control"
                                value="{{ old('room_number', $currentOrder?->room_number) }}"
                                maxlength="30">
                        </div>

                        <div id="deliveryField" class="col-12 mb-3 d-none">
                            <label for="delivery_address" class="form-label">
                                Alamat Pengantaran
                            </label>

                            <textarea name="delivery_address" id="delivery_address" class="form-control" rows="3">{{ old('delivery_address', $currentOrder?->delivery_address) }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="scheduled_at" class="form-label">
                                Jadwal Penyajian
                            </label>

                            <input type="datetime-local" name="scheduled_at" id="scheduled_at" class="form-control"
                                value="{{ old('scheduled_at', $currentOrder?->scheduled_at?->format('Y-m-d\TH:i')) }}">
                        </div>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <div class="d-flex justify-content-between align-items-center gap-3">
                        <div>
                            <i data-feather="coffee" class="me-2"></i>
                            Item Pesanan
                        </div>

                        <button type="button" id="addItemButton" class="btn btn-primary btn-sm">
                            <i data-feather="plus" class="me-1"></i>
                            Tambah Menu
                        </button>
                    </div>
                </div>

                <div class="card-body">
                    <div id="itemsContainer"></div>
                </div>
            </div>

            <div class="card shadow-sm mb-4">
                <div class="card-header">
                    <i data-feather="message-square" class="me-2"></i>
                    Catatan Pesanan
                </div>

                <div class="card-body">
                    <div class="row gx-3">
                        <div class="col-md-6 mb-3">
                            <label for="customer_note" class="form-label">
                                Catatan Pelanggan
                            </label>

                            <textarea name="customer_note" id="customer_note" class="form-control" rows="4" maxlength="3000">{{ old('customer_note', $currentOrder?->customer_note) }}</textarea>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="internal_note" class="form-label">
                                Catatan Internal
                            </label>

                            <textarea name="internal_note" id="internal_note" class="form-control" rows="4" maxlength="3000">{{ old('internal_note', $currentOrder?->internal_note) }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-xl-4">
            <div class="card shadow-sm mb-4 order-summary-card">
                <div class="card-header">
                    <i data-feather="file-text" class="me-2"></i>
                    Ringkasan Pesanan
                </div>

                <div class="card-body">
                    <div id="summaryItems"></div>

                    <hr>

                    <div class="mb-3">
                        <label for="discount_amount" class="form-label">
                            Diskon Nominal
                        </label>

                        <div class="input-group">
                            <span class="input-group-text">Rp</span>

                            <input type="number" name="discount_amount" id="discount_amount" class="form-control"
                                value="{{ old('discount_amount', $currentOrder?->discount_amount ?? 0) }}"
                                min="0" step="1">
                        </div>
                    </div>

                    <div class="order-total-row">
                        <span>Subtotal</span>
                        <strong id="subtotalText">Rp0</strong>
                    </div>

                    <div class="order-total-row">
                        <span>Diskon</span>
                        <strong id="discountText">- Rp0</strong>
                    </div>

                    <div class="order-total-row">
                        <span>
                            Pajak
                            ({{ $settings->is_tax_active ? number_format((float) $settings->tax_percentage, 2, ',', '.') : '0' }}%)
                        </span>

                        <strong id="taxText">Rp0</strong>
                    </div>

                    <hr>

                    <div class="order-grand-total">
                        <span>Total</span>
                        <strong id="grandTotalText">Rp0</strong>
                    </div>

                    <div class="alert alert-info small mt-4 mb-0">
                        Perhitungan akhir akan divalidasi ulang oleh server.
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" id="submitOrderButton" class="btn btn-primary w-100">
                        <i data-feather="save" class="me-2"></i>
                        {{ $isEdit ? 'Perbarui Pesanan' : 'Simpan Pesanan' }}
                    </button>

                    <a href="{{ $isEdit ? route('management.orders.show', $currentOrder) : route('management.orders.index') }}"
                        class="btn btn-light w-100 mt-2">
                        Batal
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

@push('style')
    <style>
        .order-product-row {
            padding: 1rem;
            margin-bottom: 1rem;
            border: 1px solid #e0e5ec;
            border-radius: 0.75rem;
            background: #fff;
        }

        .order-product-number {
            width: 2rem;
            height: 2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            color: #fff;
            background: #0061f2;
            font-weight: 700;
        }

        .order-summary-card {
            position: sticky;
            top: 6rem;
        }

        .order-total-row,
        .order-grand-total {
            display: flex;
            justify-content: space-between;
            gap: 1rem;
            margin-bottom: 0.75rem;
        }

        .order-total-row {
            color: #69707a;
            font-size: 0.875rem;
        }

        .order-grand-total {
            color: #363d47;
            font-size: 1.15rem;
        }

        .order-grand-total strong {
            color: #0061f2;
            font-size: 1.3rem;
        }

        @media (max-width: 1199.98px) {
            .order-summary-card {
                position: static;
            }
        }
    </style>
@endpush

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const singleMenus = @json($menuItemOptions);
            const packages = @json($packageOptions);
            const initialItems = @json($initialItems);

            const taxActive = @json((bool) $settings->is_tax_active);
            const taxPercentage = Number(
                @json((float) $settings->tax_percentage)
            );

            const form = document.getElementById('orderForm');
            const container = document.getElementById('itemsContainer');
            const addButton = document.getElementById('addItemButton');
            const discountInput = document.getElementById('discount_amount');
            const orderTypeInput = document.getElementById('order_type');
            const paymentTimingInput = document.getElementById('payment_timing');
            const paymentHelp = document.getElementById('paymentHelp');

            let items = Array.isArray(initialItems) ?
                initialItems.map(function(item) {
                    return {
                        item_type: item.item_type || 'single',
                        item_id: item.item_id || '',
                        quantity: Number(item.quantity) || 1,
                        note: item.note || '',
                    };
                }) :
                [];

            function escapeHtml(value) {
                return String(value ?? '')
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#039;');
            }

            function money(value) {
                return new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    minimumFractionDigits: 0,
                    maximumFractionDigits: 0,
                }).format(Number(value) || 0);
            }

            function getProduct(type, id) {
                const source = type === 'package' ?
                    packages :
                    singleMenus;

                return source.find(function(product) {
                    return Number(product.id) === Number(id);
                }) || null;
            }

            function buildOptions(selectedType, selectedId) {
                let html = '<option value="">Pilih menu atau paket</option>';
                html += '<optgroup label="Menu Satuan">';

                singleMenus.forEach(function(product) {
                    const selected = selectedType === 'single' &&
                        Number(selectedId) === Number(product.id);

                    html += `
                        <option
                            value="single:${product.id}"
                            ${selected ? 'selected' : ''}
                        >
                            ${escapeHtml(product.sku)} -
                            ${escapeHtml(product.name)}
                            (${escapeHtml(money(product.price))})
                        </option>
                    `;
                });

                html += '</optgroup>';
                html += '<optgroup label="Paket Restoran">';

                packages.forEach(function(product) {
                    const selected = selectedType === 'package' &&
                        Number(selectedId) === Number(product.id);

                    html += `
                        <option
                            value="package:${product.id}"
                            ${selected ? 'selected' : ''}
                        >
                            ${escapeHtml(product.sku)} -
                            ${escapeHtml(product.name)}
                            (${escapeHtml(money(product.price))})
                        </option>
                    `;
                });

                html += '</optgroup>';

                return html;
            }

            function renderItems() {
                container.innerHTML = '';

                items.forEach(function(item, index) {
                    const product = getProduct(
                        item.item_type,
                        item.item_id
                    );

                    const minimumOrder = product ?
                        Math.max(
                            1,
                            Number(product.minimum_order) || 1
                        ) :
                        1;

                    item.quantity = Math.max(
                        minimumOrder,
                        Number(item.quantity) || 1
                    );

                    const element = document.createElement('div');
                    element.className = 'order-product-row';
                    element.dataset.index = index;

                    element.innerHTML = `
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <div class="d-flex align-items-center gap-2">
                                <span class="order-product-number">
                                    ${index + 1}
                                </span>

                                <strong>Item Pesanan</strong>
                            </div>

                            <button
                                type="button"
                                class="btn btn-outline-danger btn-sm remove-item"
                            >
                                <i data-feather="trash-2"></i>
                            </button>
                        </div>

                        <div class="row gx-3">
                            <div class="col-lg-7 mb-3">
                                <label class="form-label">
                                    Menu atau Paket
                                </label>

                                <select
                                    class="form-select product-select"
                                    required
                                >
                                    ${buildOptions(
                                        item.item_type,
                                        item.item_id
                                    )}
                                </select>

                                <input
                                    type="hidden"
                                    name="items[${index}][item_type]"
                                    value="${escapeHtml(item.item_type)}"
                                >

                                <input
                                    type="hidden"
                                    name="items[${index}][item_id]"
                                    value="${escapeHtml(item.item_id)}"
                                >
                            </div>

                            <div class="col-lg-2 mb-3">
                                <label class="form-label">Jumlah</label>

                                <input
                                    type="number"
                                    name="items[${index}][quantity]"
                                    value="${item.quantity}"
                                    min="${minimumOrder}"
                                    max="999"
                                    class="form-control quantity-input"
                                    required
                                >
                            </div>

                            <div class="col-lg-3 mb-3">
                                <label class="form-label">Harga</label>

                                <div class="form-control bg-light">
                                    ${product ? money(product.price) : '-'}
                                </div>
                            </div>

                            <div class="col-12">
                                <label class="form-label">
                                    Catatan Item
                                </label>

                                <input
                                    type="text"
                                    name="items[${index}][note]"
                                    value="${escapeHtml(item.note)}"
                                    class="form-control note-input"
                                    maxlength="1000"
                                >
                            </div>
                        </div>
                    `;

                    container.appendChild(element);
                });

                if (window.feather) {
                    feather.replace();
                }

                calculate();
            }

            function calculate() {
                let subtotal = 0;
                let summaryHtml = '';

                items.forEach(function(item) {
                    const product = getProduct(
                        item.item_type,
                        item.item_id
                    );

                    if (!product) {
                        return;
                    }

                    const quantity = Math.max(
                        1,
                        Number(item.quantity) || 1
                    );

                    const lineTotal = Number(product.price) * quantity;
                    subtotal += lineTotal;

                    summaryHtml += `
                        <div class="d-flex justify-content-between gap-3 border-bottom py-2">
                            <div>
                                <div class="fw-semibold">
                                    ${escapeHtml(product.name)}
                                </div>

                                <small class="text-muted">
                                    ${quantity} × ${money(product.price)}
                                </small>
                            </div>

                            <strong>${money(lineTotal)}</strong>
                        </div>
                    `;
                });

                let discount = Math.max(
                    0,
                    Number(discountInput.value) || 0
                );

                if (discount > subtotal) {
                    discount = subtotal;
                    discountInput.value = Math.floor(subtotal);
                }

                const taxBase = Math.max(0, subtotal - discount);

                const taxAmount = taxActive ?
                    taxBase * (taxPercentage / 100) :
                    0;

                const grandTotal = taxBase + taxAmount;

                document.getElementById('summaryItems').innerHTML =
                    summaryHtml ||
                    '<div class="text-muted text-center py-3">Belum ada item.</div>';

                document.getElementById('subtotalText').textContent =
                    money(subtotal);

                document.getElementById('discountText').textContent =
                    '- ' + money(discount);

                document.getElementById('taxText').textContent =
                    money(taxAmount);

                document.getElementById('grandTotalText').textContent =
                    money(grandTotal);
            }

            function updateDestination() {
                const type = orderTypeInput.value;

                const tableField = document.getElementById('tableField');
                const roomField = document.getElementById('roomField');
                const deliveryField = document.getElementById('deliveryField');

                const tableInput = document.getElementById(
                    'restaurant_table_id'
                );

                const roomInput = document.getElementById('room_number');
                const deliveryInput = document.getElementById(
                    'delivery_address'
                );

                tableField.classList.toggle(
                    'd-none',
                    type !== 'dine_in'
                );

                roomField.classList.toggle(
                    'd-none',
                    type !== 'room_service'
                );

                deliveryField.classList.toggle(
                    'd-none',
                    type !== 'delivery'
                );

                tableInput.required = type === 'dine_in';
                roomInput.required = type === 'room_service';
                deliveryInput.required = type === 'delivery';

                const payLaterOption = paymentTimingInput.querySelector(
                    'option[value="pay_later"]'
                );

                const payLaterAllowed = [
                    'dine_in',
                    'room_service',
                ].includes(type);

                payLaterOption.disabled = !payLaterAllowed;

                if (
                    !payLaterAllowed &&
                    paymentTimingInput.value === 'pay_later'
                ) {
                    paymentTimingInput.value = 'pay_now';
                }

                paymentHelp.textContent = payLaterAllowed ?
                    'Pesanan dapat dibayar sekarang atau nanti.' :
                    'Pesanan delivery harus dibayar sekarang.';
            }

            addButton.addEventListener('click', function() {
                items.push({
                    item_type: 'single',
                    item_id: '',
                    quantity: 1,
                    note: '',
                });

                renderItems();
            });

            container.addEventListener('click', function(event) {
                const button = event.target.closest('.remove-item');

                if (!button) {
                    return;
                }

                if (items.length === 1) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Item Tidak Dapat Dihapus',
                        text: 'Pesanan harus memiliki minimal satu item.',
                    });

                    return;
                }

                const row = button.closest('.order-product-row');
                items.splice(Number(row.dataset.index), 1);
                renderItems();
            });

            container.addEventListener('change', function(event) {
                const row = event.target.closest('.order-product-row');

                if (!row) {
                    return;
                }

                const index = Number(row.dataset.index);

                if (event.target.classList.contains('product-select')) {
                    const [type, id] = event.target.value.split(':');

                    items[index].item_type = type || 'single';
                    items[index].item_id = id || '';

                    const selectedProduct = getProduct(type, id);

                    if (selectedProduct) {
                        items[index].quantity = Math.max(
                            Number(items[index].quantity) || 1,
                            Number(selectedProduct.minimum_order) || 1
                        );
                    }

                    renderItems();
                }

                if (event.target.classList.contains('quantity-input')) {
                    items[index].quantity =
                        Number(event.target.value) || 1;

                    calculate();
                }
            });

            container.addEventListener('input', function(event) {
                const row = event.target.closest('.order-product-row');

                if (!row) {
                    return;
                }

                const index = Number(row.dataset.index);

                if (event.target.classList.contains('quantity-input')) {
                    items[index].quantity =
                        Number(event.target.value) || 1;

                    calculate();
                }

                if (event.target.classList.contains('note-input')) {
                    items[index].note = event.target.value;
                }
            });

            discountInput.addEventListener('input', calculate);
            orderTypeInput.addEventListener('change', updateDestination);

            form.addEventListener('submit', function(event) {
                const validItems = items.filter(function(item) {
                    return item.item_id &&
                        getProduct(item.item_type, item.item_id);
                });

                if (validItems.length === 0) {
                    event.preventDefault();

                    Swal.fire({
                        icon: 'warning',
                        title: 'Item Pesanan Kosong',
                        text: 'Tambahkan minimal satu menu atau paket.',
                    });

                    return;
                }

                const button = document.getElementById(
                    'submitOrderButton'
                );

                button.disabled = true;
                button.innerHTML = `
                    <span class="spinner-border spinner-border-sm me-2"></span>
                    Menyimpan...
                `;
            });

            if (items.length === 0) {
                items.push({
                    item_type: 'single',
                    item_id: '',
                    quantity: 1,
                    note: '',
                });
            }

            renderItems();
            updateDestination();
        });
    </script>
@endpush
