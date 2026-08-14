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
        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->foreignId('room_type_id')
                ->constrained()
                ->restrictOnDelete();

            /*
         * Boleh NULL jika nomor kamar fisik belum ditentukan.
         */
            $table->foreignId('room_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            /*
         * Snapshot nama dan harga kamar pada saat booking.
         */
            $table->string('room_name');
            $table->unsignedInteger('adults')->default(1);
            $table->unsignedInteger('children')->default(0);
            $table->decimal('price_per_night', 15, 2);
            $table->unsignedInteger('total_nights');
            $table->decimal('subtotal', 15, 2);
            $table->timestamps();

            $table->index(['room_id', 'booking_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_items');
    }
};