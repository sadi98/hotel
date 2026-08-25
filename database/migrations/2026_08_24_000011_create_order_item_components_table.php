<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('order_item_components', function (Blueprint $table) {
            $table->id()->comment('Primary key snapshot isi paket.');
            $table->foreignId('order_item_id')->comment('Order item paket.')->constrained()->cascadeOnDelete();
            $table->foreignId('menu_item_id')->nullable()->comment('Referensi menu asal.')->constrained('menu_items')->nullOnDelete();
            $table->string('menu_name', 150)->comment('Snapshot nama komponen.');
            $table->unsignedInteger('quantity_per_package')->comment('Jumlah komponen per paket.');
            $table->unsignedInteger('total_quantity')->comment('Total komponen untuk seluruh paket.');
            $table->text('note')->nullable()->comment('Catatan komponen.');
            $table->string('status', 30)->default('pending')->index()->comment('Status pengerjaan komponen.');
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('order_item_components'); }
};
