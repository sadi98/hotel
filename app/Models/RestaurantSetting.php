<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RestaurantSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'restaurant_name',
        'currency_code',
        'currency_symbol',
        'is_service_charge_active',
        'service_charge_percentage',
        'is_tax_active',
        'tax_percentage',
        'maximum_discount_percentage',
        'allow_pay_later_dine_in',
        'allow_pay_later_delivery',
        'allow_pay_later_room_service',
        'order_number_prefix',
        'payment_number_prefix',
        'reservation_number_prefix',
        'default_reservation_duration',
        'default_preparation_time',
    ];

    protected function casts(): array
    {
        return [
            'is_service_charge_active' => 'boolean',
            'service_charge_percentage' => 'decimal:2',
            'is_tax_active' => 'boolean',
            'tax_percentage' => 'decimal:2',
            'maximum_discount_percentage' => 'decimal:2',
            'allow_pay_later_dine_in' => 'boolean',
            'allow_pay_later_delivery' => 'boolean',
            'allow_pay_later_room_service' => 'boolean',
            'default_reservation_duration' => 'integer',
            'default_preparation_time' => 'integer',
        ];
    }

    public static function current(): self
    {
        return static::query()->firstOrCreate(
            ['id' => 1],
            [
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
            ]
        );
    }

    public function activeServiceChargePercentage(): float
    {
        if (!$this->is_service_charge_active) {
            return 0;
        }

        return (float) $this->service_charge_percentage;
    }

    public function activeTaxPercentage(): float
    {
        if (!$this->is_tax_active) {
            return 0;
        }

        return (float) $this->tax_percentage;
    }

    public function allowsPayLater(string $orderType): bool
    {
        return match ($orderType) {
            'dine_in' => $this->allow_pay_later_dine_in,
            'delivery' => $this->allow_pay_later_delivery,
            'room_service' => $this->allow_pay_later_room_service,
            default => false,
        };
    }
}
