<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cart_items', function (Blueprint $table) {
            $table->id()->comment('Primary key item cart.');
            $table->foreignId('cart_id')->comment('Cart pemilik item.')->constrained()->cascadeOnDelete();
            $table->string('item_type', 20)->index()->comment('single atau package.');
            $table->foreignId('menu_item_id')->nullable()->comment('Menu satuan.')->constrained('menu_items')->cascadeOnDelete();
            $table->foreignId('menu_package_id')->nullable()->comment('Paket menu.')->constrained('menu_packages')->cascadeOnDelete();
            $table->unsignedInteger('quantity')->default(1)->comment('Jumlah yang dipesan.');
            $table->text('note')->nullable()->comment('Catatan pelanggan.');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('cart_items'); }
};
