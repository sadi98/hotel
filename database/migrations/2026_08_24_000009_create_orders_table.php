<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id()->comment('Primary key order.');
            $table->uuid('access_token')->unique()->comment('Token akses aman order guest.');
            $table->string('order_number', 50)->unique()->comment('Nomor order publik.');
            $table->foreignId('user_id')->nullable()->comment('User pemesan; null untuk guest.')->constrained('users')->nullOnDelete();
            $table->foreignId('created_by')->nullable()->comment('Staff pembuat order kasir.')->constrained('users')->nullOnDelete();
            $table->foreignId('restaurant_table_id')->nullable()->comment('Meja dine-in.')->constrained('restaurant_tables')->nullOnDelete();
            $table->boolean('ordered_from_table_qr')->default(false)->comment('Order berasal dari QR meja valid.');
            $table->string('order_type', 30)->index()->comment('dine_in, delivery, atau room_service.');
            $table->string('customer_name', 150)->comment('Nama pelanggan.');
            $table->string('customer_phone', 30)->comment('Telepon pelanggan.');
            $table->string('customer_email')->nullable()->comment('Email pelanggan.');
            $table->string('room_number', 30)->nullable()->comment('Nomor kamar untuk room service.');
            $table->text('delivery_address')->nullable()->comment('Alamat untuk delivery.');
            $table->unsignedInteger('guest_count')->default(1)->comment('Jumlah tamu.');
            $table->string('status', 30)->default('pending')->index()->comment('Status proses order.');
            $table->string('payment_status', 30)->default('unpaid')->index()->comment('Status pembayaran order.');
            $table->string('payment_timing', 20)->default('pay_now')->comment('pay_now atau pay_later.');
            $table->decimal('subtotal', 15, 2)->default(0)->comment('Total item sebelum biaya.');
            $table->decimal('discount_amount', 15, 2)->default(0)->comment('Nominal diskon.');
            $table->decimal('service_charge_percentage', 5, 2)->default(0)->comment('Persentase service charge snapshot.');
            $table->decimal('service_charge_amount', 15, 2)->default(0)->comment('Nominal service charge.');
            $table->decimal('tax_percentage', 5, 2)->default(0)->comment('Persentase pajak snapshot.');
            $table->decimal('tax_amount', 15, 2)->default(0)->comment('Nominal pajak.');
            $table->decimal('grand_total', 15, 2)->default(0)->comment('Total akhir.');
            $table->text('customer_note')->nullable()->comment('Catatan pelanggan.');
            $table->text('internal_note')->nullable()->comment('Catatan staff.');
            $table->text('cancellation_reason')->nullable()->comment('Alasan pembatalan.');
            $table->timestamp('ordered_at')->nullable()->comment('Waktu order dibuat.');
            $table->timestamp('scheduled_at')->nullable()->comment('Jadwal penyajian/pengantaran.');
            $table->timestamp('confirmed_at')->nullable()->comment('Waktu order dikonfirmasi.');
            $table->timestamp('completed_at')->nullable()->comment('Waktu order selesai.');
            $table->timestamp('cancelled_at')->nullable()->comment('Waktu order dibatalkan.');
            $table->timestamps();
            $table->index(['restaurant_table_id', 'status']);
        });
    }
    public function down(): void { Schema::dropIfExists('orders'); }
};
