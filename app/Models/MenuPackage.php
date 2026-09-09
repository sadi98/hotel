<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuPackage extends Model
{
    use HasFactory;

    protected $fillable = [
        'sku',
        'name',
        'slug',
        'description',
        'normal_price',
        'package_price',
        'serving_count',
        'minimum_order',
        'image',
        'is_available',
        'is_featured',
        'available_from',
        'available_until',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'normal_price' => 'decimal:2',
            'package_price' => 'decimal:2',
            'serving_count' => 'integer',
            'minimum_order' => 'integer',
            'is_available' => 'boolean',
            'is_featured' => 'boolean',
            'available_from' => 'datetime:H:i',
            'available_until' => 'datetime:H:i',
            'sort_order' => 'integer',
        ];
    }

    /*
     * Relasi langsung ke seluruh menu yang menjadi komponen paket.
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(
            MenuItem::class,
            'menu_package_items',
            'menu_package_id',
            'menu_item_id'
        )
            ->withPivot([
                'id',
                'quantity',
                'note',
                'sort_order',
            ])
            ->withTimestamps()
            ->orderByPivot('sort_order');
    }

    /*
     * Relasi langsung ke record tabel pivot menu_package_items.
     */
    public function packageItems(): HasMany
    {
        return $this->hasMany(MenuPackageItem::class)
            ->orderBy('sort_order');
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function scopeAvailable(Builder $query): Builder
    {
        return $query
            ->where('is_available', true)
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('available_from')
                    ->orWhereTime('available_from', '<=', now()->format('H:i:s'));
            })
            ->where(function (Builder $query): void {
                $query
                    ->whereNull('available_until')
                    ->orWhereTime('available_until', '>=', now()->format('H:i:s'));
            });
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
