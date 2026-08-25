<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('categories', function (Blueprint $table) {
            $table->id()->comment('Primary key kategori.');
            $table->string('name', 100)->comment('Nama kategori menu.');
            $table->string('slug', 120)->unique()->comment('Slug kategori untuk URL.');
            $table->string('menu_type', 20)->index()->comment('Jenis utama: food atau beverage.');
            $table->text('description')->nullable()->comment('Penjelasan kategori.');
            $table->string('image')->nullable()->comment('Path gambar kategori.');
            $table->unsignedInteger('sort_order')->default(0)->comment('Urutan tampilan.');
            $table->boolean('is_active')->default(true)->comment('Status kategori aktif.');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categories');
    }
};
