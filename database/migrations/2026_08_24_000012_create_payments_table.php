<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id()->comment('Primary key pembayaran.');
            $table->foreignId('order_id')->comment('Order yang dibayar.')->constrained()->cascadeOnDelete();
            $table->foreignId('processed_by')->nullable()->comment('Staff pemroses pembayaran kasir.')->constrained('users')->nullOnDelete();
            $table->string('payment_number', 50)->unique()->comment('Nomor pembayaran dan Midtrans order_id.');
            $table->string('payment_channel', 30)->index()->comment('gateway atau cashier.');
            $table->string('payment_method', 50)->nullable()->comment('cash, card, qris, ewallet, transfer.');
            $table->string('provider', 50)->nullable()->comment('midtrans atau manual.');
            $table->string('provider_transaction_id')->nullable()->index()->comment('ID transaksi provider.');
            $table->decimal('amount', 15, 2)->comment('Nominal tagihan.');
            $table->decimal('paid_amount', 15, 2)->nullable()->comment('Nominal diterima kasir.');
            $table->decimal('change_amount', 15, 2)->default(0)->comment('Nominal kembalian.');
            $table->string('status', 30)->default('pending')->index()->comment('Status pembayaran.');
            $table->string('fraud_status', 30)->nullable()->comment('Status fraud Midtrans.');
            $table->text('payment_url')->nullable()->comment('URL pembayaran provider jika tersedia.');
            $table->text('snap_token')->nullable()->comment('Token popup Midtrans Snap.');
            $table->json('provider_response')->nullable()->comment('Respons terakhir provider.');
            $table->text('note')->nullable()->comment('Catatan pembayaran.');
            $table->timestamp('expired_at')->nullable()->comment('Waktu kedaluwarsa.');
            $table->timestamp('paid_at')->nullable()->comment('Waktu berhasil dibayar.');
            $table->timestamp('failed_at')->nullable()->comment('Waktu gagal.');
            $table->timestamp('refunded_at')->nullable()->comment('Waktu refund.');
            $table->timestamps();
            $table->index(['order_id', 'status']);
        });
    }
    public function down(): void { Schema::dropIfExists('payments'); }
};
