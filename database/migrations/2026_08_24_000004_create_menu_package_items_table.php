<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('menu_package_items', function (Blueprint $table) {
            $table->id()->comment('Primary key komponen paket.');
            $table->foreignId('menu_package_id')->comment('Paket pemilik komponen.')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->comment('Menu dalam paket.')->constrained()->restrictOnDelete();
            $table->unsignedInteger('quantity')->default(1)->comment('Jumlah menu per satu paket.');
            $table->text('note')->nullable()->comment('Catatan komponen paket.');
            $table->unsignedInteger('sort_order')->default(0)->comment('Urutan komponen.');
            $table->timestamps();
            $table->unique(['menu_package_id', 'menu_item_id'], 'package_menu_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('menu_package_items');
    }
};
