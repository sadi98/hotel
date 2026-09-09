<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    use HasFactory;

    /**
     * Field yang dapat diisi melalui create atau update.
     *
     * @var list<string>
     */
    protected $fillable = [
        'restaurant_name',
        'currency_code',
        'currency_symbol',
        'is_service_charge_active',
        'service_charge_percentage',
        'is_tax_active',
        'tax_percentage',
        'maximum_discount_percentage',
        'allow_dine_in_pay_later',
        'allow_delivery_pay_later',
        'allow_room_service_pay_later',
        'order_number_prefix',
        'payment_number_prefix',
        'reservation_number_prefix',
        'default_reservation_duration',
        'default_preparation_time',
    ];

    /**
     * Konversi tipe data otomatis.
     *
     * @var array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_service_charge_active' => 'boolean',
            'service_charge_percentage' => 'decimal:2',
            'is_tax_active' => 'boolean',
            'tax_percentage' => 'decimal:2',
            'maximum_discount_percentage' => 'decimal:2',
            'allow_dine_in_pay_later' => 'boolean',
            'allow_delivery_pay_later' => 'boolean',
            'allow_room_service_pay_later' => 'boolean',
            'default_reservation_duration' => 'integer',
            'default_preparation_time' => 'integer',
        ];
    }

    /**
     * Mengambil pengaturan restoran.
     *
     * Jika data belum tersedia, sistem otomatis membuat pengaturan default.
     */
    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
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
            ]
        );
    }

    /**
     * Mendapatkan service charge aktif.
     */
    public function activeServiceChargePercentage(): float
    {
        if (! $this->is_service_charge_active) {
            return 0;
        }

        return (float) $this->service_charge_percentage;
    }

    /**
     * Mendapatkan pajak aktif.
     */
    public function activeTaxPercentage(): float
    {
        if (! $this->is_tax_active) {
            return 0;
        }

        return (float) $this->tax_percentage;
    }

    /**
     * Memeriksa apakah bayar nanti diizinkan.
     */
    public function allowsPayLater(string $orderType): bool
    {
        return match ($orderType) {
            'dine_in' => $this->allow_dine_in_pay_later,
            'delivery' => $this->allow_delivery_pay_later,
            'room_service' => $this->allow_room_service_pay_later,
            default => false,
        };
    }
}
