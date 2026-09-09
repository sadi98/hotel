<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuPackageItem extends Model
{
    use HasFactory;

    /**
     * Field yang dapat diisi.
     *
     * @var list<string>
     */
    protected $fillable = [
        'menu_package_id',
        'menu_item_id',
        'quantity',
        'note',
        'sort_order',
    ];

    /**
     * Konversi tipe data.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'menu_package_id' => 'integer',
            'menu_item_id' => 'integer',
            'quantity' => 'integer',
            'sort_order' => 'integer',
        ];
    }

    /**
     * Paket pemilik komponen.
     */
    public function menuPackage(): BelongsTo
    {
        return $this->belongsTo(
            MenuPackage::class,
            'menu_package_id'
        );
    }

    /**
     * Menu yang menjadi komponen paket.
     */
    public function menuItem(): BelongsTo
    {
        return $this->belongsTo(
            MenuItem::class,
            'menu_item_id'
        );
    }
}
