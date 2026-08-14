<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code')->unique();

            // NULL berarti booking dilakukan sebagai guest.
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            $table->foreignId('hotel_id')
                ->constrained()
                ->restrictOnDelete();

            $table->foreignId('promotion_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
         * Data pemesan tetap disimpan sebagai snapshot.
         * Data ini diisi baik untuk guest maupun pengguna login.
         */
            $table->string('guest_name');
            $table->string('guest_email');
            $table->string('guest_phone', 30);
            $table->string('guest_identity_number')->nullable();

            $table->date('check_in_date');
            $table->date('check_out_date');
            $table->unsignedInteger('total_nights');

            $table->decimal('subtotal', 15, 2);
            $table->decimal('tax_amount', 15, 2)->default(0);
            $table->decimal('service_amount', 15, 2)->default(0);
            $table->decimal('discount_amount', 15, 2)->default(0);
            $table->decimal('grand_total', 15, 2);
            $table->char('currency', 3)->default('IDR');

            $table->string('booking_status')->default('pending');
            $table->string('payment_status')->default('unpaid');

            $table->text('special_request')->nullable();
            $table->dateTime('expires_at')->nullable();
            $table->dateTime('confirmed_at')->nullable();
            $table->dateTime('cancelled_at')->nullable();
            $table->timestamps();

            $table->index([
                'hotel_id',
                'check_in_date',
                'check_out_date',
            ]);

            $table->index('booking_status');
            $table->index('payment_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};