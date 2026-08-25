<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MenuItem extends Model
{
    use HasFactory;
    public const BEVERAGE_COFFEE = 'coffee';
    public const BEVERAGE_NON_COFFEE = 'non_coffee';
    protected $fillable = ['category_id','sku','name','slug','description','beverage_type','is_alcoholic','price','image','preparation_time','is_available','is_featured','sort_order'];
    protected function casts(): array { return ['price'=>'decimal:2','is_alcoholic'=>'boolean','preparation_time'=>'integer','is_available'=>'boolean','is_featured'=>'boolean','sort_order'=>'integer']; }
    public function category(): BelongsTo { return $this->belongsTo(Category::class); }
    public function packageItems(): HasMany { return $this->hasMany(MenuPackageItem::class); }
    public function orderItems(): HasMany { return $this->hasMany(OrderItem::class); }
    public function scopeAvailable(Builder $query): Builder { return $query->where('is_available', true); }
}
