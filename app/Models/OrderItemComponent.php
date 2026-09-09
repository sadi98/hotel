<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItemComponent extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_item_id',
        'menu_item_id',
        'menu_name',
        'quantity_per_package',
        'total_quantity',
        'note',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'quantity_per_package' => 'integer',
            'total_quantity' => 'integer',
        ];
    }

    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(MenuItem::class);
    }
}
