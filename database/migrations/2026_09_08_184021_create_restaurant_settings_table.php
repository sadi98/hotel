<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('restaurant_settings', function (Blueprint $table) {
            $table->id();

            $table->string('restaurant_name', 150)
                ->default('Restaurant');

            $table->string('currency_code', 10)
                ->default('IDR');

            $table->string('currency_symbol', 10)
                ->default('Rp');

            $table->boolean('is_service_charge_active')
                ->default(true);

            $table->decimal('service_charge_percentage', 5, 2)
                ->default(5);

            $table->boolean('is_tax_active')
                ->default(true);

            $table->decimal('tax_percentage', 5, 2)
                ->default(11);

            $table->decimal('maximum_discount_percentage', 5, 2)
                ->default(100);

            $table->boolean('allow_pay_later_dine_in')
                ->default(true);

            $table->boolean('allow_pay_later_delivery')
                ->default(false);

            $table->boolean('allow_pay_later_room_service')
                ->default(true);

            $table->string('order_number_prefix', 20)
                ->default('ORD');

            $table->string('payment_number_prefix', 20)
                ->default('PAY');

            $table->string('reservation_number_prefix', 20)
                ->default('RSV');

            $table->unsignedInteger('default_reservation_duration')
                ->default(120)
                ->comment('Durasi reservasi dalam menit.');

            $table->unsignedInteger('default_preparation_time')
                ->default(30)
                ->comment('Waktu persiapan pesanan dalam menit.');

            $table->timestamps();
        });

        DB::table('restaurant_settings')->insert([
            'restaurant_name' => config('app.name', 'Restaurant'),
            'currency_code' => 'IDR',
            'currency_symbol' => 'Rp',
            'is_service_charge_active' => true,
            'service_charge_percentage' => 5,
            'is_tax_active' => true,
            'tax_percentage' => 11,
            'maximum_discount_percentage' => 100,
            'allow_pay_later_dine_in' => true,
            'allow_pay_later_delivery' => false,
            'allow_pay_later_room_service' => true,
            'order_number_prefix' => 'ORD',
            'payment_number_prefix' => 'PAY',
            'reservation_number_prefix' => 'RSV',
            'default_reservation_duration' => 120,
            'default_preparation_time' => 30,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('restaurant_settings');
    }
};
