<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('table_reservations', function (Blueprint $table) {
            $table->id()->comment('Primary key reservasi.');
            $table->uuid('access_token')->unique()->comment('Token akses aman reservasi guest.');
            $table->string('reservation_number', 50)->unique()->comment('Nomor reservasi publik.');
            $table->foreignId('user_id')->nullable()->comment('User pemesan; null untuk guest.')->constrained('users')->nullOnDelete();
            $table->foreignId('restaurant_table_id')->comment('Meja yang dipesan.')->constrained('restaurant_tables')->restrictOnDelete();
            $table->string('customer_name', 150)->comment('Nama pemesan.');
            $table->string('customer_phone', 30)->comment('Telepon pemesan.');
            $table->string('customer_email')->nullable()->comment('Email pemesan.');
            $table->unsignedInteger('guest_count')->comment('Jumlah tamu.');
            $table->dateTime('reservation_start')->comment('Waktu mulai reservasi.');
            $table->dateTime('reservation_end')->comment('Waktu selesai reservasi.');
            $table->string('status', 30)->default('pending')->index()->comment('Status reservasi.');
            $table->text('special_request')->nullable()->comment('Permintaan khusus.');
            $table->text('cancellation_reason')->nullable()->comment('Alasan pembatalan.');
            $table->timestamps();
            $table->index(['restaurant_table_id', 'reservation_start', 'reservation_end'], 'table_reservation_schedule_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('table_reservations');
    }
};
