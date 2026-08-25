<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id()->comment('Primary key detail order.');
            $table->foreignId('order_id')->comment('Order pemilik item.')->constrained()->cascadeOnDelete();
            $table->string('item_type', 20)->index()->comment('single atau package.');
            $table->foreignId('menu_item_id')->nullable()->comment('Referensi menu satuan.')->constrained('menu_items')->nullOnDelete();
            $table->foreignId('menu_package_id')->nullable()->comment('Referensi paket.')->constrained('menu_packages')->nullOnDelete();
            $table->string('item_name', 150)->comment('Snapshot nama item.');
            $table->decimal('unit_price', 15, 2)->comment('Snapshot harga satuan.');
            $table->unsignedInteger('quantity')->comment('Jumlah item.');
            $table->decimal('subtotal', 15, 2)->comment('Harga dikali jumlah.');
            $table->text('note')->nullable()->comment('Catatan item.');
            $table->string('status', 30)->default('pending')->index()->comment('Status pengerjaan item.');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('order_items'); }
};
