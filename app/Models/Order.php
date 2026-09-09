<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_token',
        'order_number',
        'user_id',
        'created_by',
        'restaurant_table_id',
        'ordered_from_table_qr',
        'order_type',
        'customer_name',
        'customer_phone',
        'customer_email',
        'room_number',
        'delivery_address',
        'guest_count',
        'status',
        'payment_status',
        'payment_timing',
        'subtotal',
        'discount_amount',
        'service_charge_percentage',
        'service_charge_amount',
        'tax_percentage',
        'tax_amount',
        'grand_total',
        'customer_note',
        'internal_note',
        'cancellation_reason',
        'ordered_at',
        'scheduled_at',
        'confirmed_at',
        'completed_at',
        'cancelled_at',
    ];

    protected function casts(): array
    {
        return [
            'ordered_from_table_qr' => 'boolean',
            'guest_count' => 'integer',
            'subtotal' => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'service_charge_percentage' => 'decimal:2',
            'service_charge_amount' => 'decimal:2',
            'tax_percentage' => 'decimal:2',
            'tax_amount' => 'decimal:2',
            'grand_total' => 'decimal:2',
            'ordered_at' => 'datetime',
            'scheduled_at' => 'datetime',
            'confirmed_at' => 'datetime',
            'completed_at' => 'datetime',
            'cancelled_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'created_by'
        );
    }

    public function restaurantTable(): BelongsTo
    {
        return $this->belongsTo(RestaurantTable::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class)
            ->latest();
    }
}
