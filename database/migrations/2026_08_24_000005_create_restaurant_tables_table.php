<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('restaurant_tables', function (Blueprint $table) {
            $table->id()->comment('Primary key meja.');
            $table->string('table_number', 30)->unique()->comment('Nomor unik meja.');
            $table->uuid('qr_token')->unique()->comment('Token rahasia QR meja.');
            $table->string('name', 100)->nullable()->comment('Nama meja.');
            $table->string('area', 50)->index()->comment('Area meja.');
            $table->unsignedInteger('capacity')->default(2)->comment('Kapasitas tamu.');
            $table->boolean('is_active')->default(true)->comment('Meja aktif digunakan.');
            $table->text('description')->nullable()->comment('Keterangan meja.');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_tables');
    }
};
