<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->id()->comment('Primary key menu.');
            $table->foreignId('category_id')->comment('Kategori menu.')->constrained()->restrictOnDelete();
            $table->string('sku', 50)->unique()->comment('Kode unik operasional menu.');
            $table->string('name', 150)->comment('Nama makanan atau minuman.');
            $table->string('slug', 180)->unique()->comment('Slug menu untuk URL.');
            $table->text('description')->nullable()->comment('Deskripsi menu.');
            $table->string('beverage_type', 30)->nullable()->index()->comment('coffee/non_coffee, null untuk makanan.');
            $table->boolean('is_alcoholic')->nullable()->index()->comment('Kandungan alkohol; null untuk makanan.');
            $table->decimal('price', 15, 2)->comment('Harga jual satuan.');
            $table->string('image')->nullable()->comment('Path gambar menu.');
            $table->unsignedInteger('preparation_time')->default(15)->comment('Estimasi pembuatan dalam menit.');
            $table->boolean('is_available')->default(true)->comment('Menu tersedia untuk dipesan.');
            $table->boolean('is_featured')->default(false)->comment('Menu rekomendasi.');
            $table->unsignedInteger('sort_order')->default(0)->comment('Urutan tampilan.');
            $table->timestamps();
            $table->index(['category_id', 'is_available']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_items');
    }
};
