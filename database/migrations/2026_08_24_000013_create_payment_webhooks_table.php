<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payment_webhooks', function (Blueprint $table) {
            $table->id()->comment('Primary key log webhook.');
            $table->foreignId('payment_id')->nullable()->comment('Payment terkait.')->constrained()->nullOnDelete();
            $table->string('provider', 50)->comment('Nama provider.');
            $table->string('event_id')->comment('Identitas unik event.');
            $table->string('event_type', 100)->nullable()->comment('Jenis event provider.');
            $table->json('payload')->comment('Payload webhook asli.');
            $table->boolean('is_processed')->default(false)->comment('Event sudah diproses.');
            $table->text('processing_message')->nullable()->comment('Hasil pemrosesan.');
            $table->timestamp('processed_at')->nullable()->comment('Waktu selesai diproses.');
            $table->timestamps();
            $table->unique(['provider', 'event_id'], 'provider_event_unique');
        });
    }
    public function down(): void { Schema::dropIfExists('payment_webhooks'); }
};
