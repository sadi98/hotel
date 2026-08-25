<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu_packages', function (Blueprint $table) {
            $table->id()->comment('Primary key paket.');
            $table->string('sku', 50)->unique()->comment('Kode unik paket.');
            $table->string('name', 150)->comment('Nama paket.');
            $table->string('slug', 180)->unique()->comment('Slug paket untuk URL.');
            $table->text('description')->nullable()->comment('Deskripsi paket.');
            $table->decimal('normal_price', 15, 2)->comment('Total harga jika komponen dibeli satuan.');
            $table->decimal('package_price', 15, 2)->comment('Harga jual paket.');
            $table->unsignedInteger('serving_count')->default(1)->comment('Jumlah orang per paket.');
            $table->unsignedInteger('minimum_order')->default(1)->comment('Minimum jumlah pemesanan.');
            $table->string('image')->nullable()->comment('Path gambar paket.');
            $table->boolean('is_available')->default(true)->comment('Paket tersedia untuk dipesan.');
            $table->boolean('is_featured')->default(false)->comment('Paket rekomendasi.');
            $table->timestamp('available_from')->nullable()->comment('Awal masa berlaku.');
            $table->timestamp('available_until')->nullable()->comment('Akhir masa berlaku.');
            $table->unsignedInteger('sort_order')->default(0)->comment('Urutan tampilan.');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_packages');
    }
};
