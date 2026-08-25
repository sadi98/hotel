<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CartItem extends Model
{
    use HasFactory;
    public const TYPE_SINGLE='single', TYPE_PACKAGE='package';
    protected $fillable = ['cart_id','item_type','menu_item_id','menu_package_id','quantity','note'];
    protected function casts(): array { return ['quantity'=>'integer']; }
    public function cart(): BelongsTo { return $this->belongsTo(Cart::class); }
    public function menuItem(): BelongsTo { return $this->belongsTo(MenuItem::class); }
    public function menuPackage(): BelongsTo { return $this->belongsTo(MenuPackage::class); }
    public function getNameAttribute(): string { return $this->item_type===self::TYPE_SINGLE ? ($this->menuItem?->name ?? 'Unavailable menu') : ($this->menuPackage?->name ?? 'Unavailable package'); }
    public function getPriceAttribute(): float { return $this->item_type===self::TYPE_SINGLE ? (float)($this->menuItem?->price ?? 0) : (float)($this->menuPackage?->package_price ?? 0); }
    public function getSubtotalAttribute(): float { return $this->price * $this->quantity; }
}
