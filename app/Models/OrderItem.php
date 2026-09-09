<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OrderItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'item_type',
        'menu_item_id',
        'menu_package_id',
        'item_name',
        'unit_price',
        'quantity',
        'subtotal',
        'note',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'unit_price' => 'decimal:2',
            'quantity' => 'integer',
            'subtotal' => 'decimal:2',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }

    public function menuPackage(): BelongsTo
    {
        return $this->belongsTo(MenuPackage::class);
    }

    public function components(): HasMany
    {
        return $this->hasMany(OrderItemComponent::class);
    }

    public function isPackage(): bool
    {
        return $this->item_type === 'package';
    }

    public function isSingle(): bool
    {
        return $this->item_type === 'single';
    }
}
