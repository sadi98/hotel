<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuPackage extends Model
{
    use HasFactory;
    protected $fillable = ['sku','name','slug','description','normal_price','package_price','serving_count','minimum_order','image','is_available','is_featured','available_from','available_until','sort_order'];
    protected function casts(): array { return ['normal_price'=>'decimal:2','package_price'=>'decimal:2','serving_count'=>'integer','minimum_order'=>'integer','is_available'=>'boolean','is_featured'=>'boolean','available_from'=>'datetime','available_until'=>'datetime','sort_order'=>'integer']; }
    public function items(): HasMany { return $this->hasMany(MenuPackageItem::class)->orderBy('sort_order'); }
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class); }
    public function scopeAvailable(Builder $query): Builder { return $query->where('is_available', true)->where(fn($q)=>$q->whereNull('available_from')->orWhere('available_from','<=',now()))->where(fn($q)=>$q->whereNull('available_until')->orWhere('available_until','>=',now())); }
}
