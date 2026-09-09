<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menjalankan migration.
     */
    public function up(): void
    {
        Schema::create('restaurant_settings', function (Blueprint $table) {
            $table->id()
                ->comment('Primary key pengaturan restoran.');

            $table->string('restaurant_name', 150)
                ->default('Kayu Manis Restaurant')
                ->comment('Nama restoran yang digunakan pada halaman management.');

            $table->string('currency_code', 10)
                ->default('IDR')
                ->comment('Kode mata uang yang digunakan.');

            $table->string('currency_symbol', 10)
                ->default('Rp')
                ->comment('Simbol mata uang yang ditampilkan.');

            $table->boolean('is_service_charge_active')
                ->default(false)
                ->comment('Menentukan apakah service charge diterapkan.');

            $table->decimal('service_charge_percentage', 5, 2)
                ->default(0)
                ->comment('Persentase service charge.');

            $table->boolean('is_tax_active')
                ->default(false)
                ->comment('Menentukan apakah pajak diterapkan.');

            $table->decimal('tax_percentage', 5, 2)
                ->default(0)
                ->comment('Persentase pajak restoran.');

            $table->decimal('maximum_discount_percentage', 5, 2)
                ->default(100)
                ->comment('Batas maksimal diskon dibanding subtotal.');

            $table->boolean('allow_dine_in_pay_later')
                ->default(true)
                ->comment('Mengizinkan pesanan dine-in dibayar nanti.');

            $table->boolean('allow_delivery_pay_later')
                ->default(false)
                ->comment('Mengizinkan pesanan delivery dibayar nanti.');

            $table->boolean('allow_room_service_pay_later')
                ->default(true)
                ->comment('Mengizinkan room service dibayar nanti.');

            $table->string('order_number_prefix', 20)
                ->default('ORD')
                ->comment('Awalan nomor pesanan.');

            $table->string('payment_number_prefix', 20)
                ->default('PAY')
                ->comment('Awalan nomor pembayaran.');

            $table->string('reservation_number_prefix', 20)
                ->default('RSV')
                ->comment('Awalan nomor reservasi.');

            $table->unsignedInteger('default_reservation_duration')
                ->default(120)
                ->comment('Durasi default reservasi dalam menit.');

            $table->unsignedInteger('default_preparation_time')
                ->default(15)
                ->comment('Estimasi persiapan default dalam menit.');

            $table->timestamps();
        });

        /*
        |--------------------------------------------------------------------------
        | DEFAULT SETTING
        |--------------------------------------------------------------------------
        |
        | Membuat satu pengaturan awal agar halaman pemesanan langsung dapat
        | digunakan setelah migrate:fresh.
        |
        */

        DB::table('restaurant_settings')->insert([
            'id' => 1,
            'restaurant_name' => 'Kayu Manis Restaurant',
            'currency_code' => 'IDR',
            'currency_symbol' => 'Rp',
            'is_service_charge_active' => false,
            'service_charge_percentage' => 0,
            'is_tax_active' => false,
            'tax_percentage' => 0,
            'maximum_discount_percentage' => 100,
            'allow_dine_in_pay_later' => true,
            'allow_delivery_pay_later' => false,
            'allow_room_service_pay_later' => true,
            'order_number_prefix' => 'ORD',
            'payment_number_prefix' => 'PAY',
            'reservation_number_prefix' => 'RSV',
            'default_reservation_duration' => 120,
            'default_preparation_time' => 15,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Membatalkan migration.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurant_settings');
    }
};
