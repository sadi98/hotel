<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    use HasFactory;
    public const TYPE_FOOD = 'food';
    public const TYPE_BEVERAGE = 'beverage';
    protected $fillable = ['name','slug','menu_type','description','image','sort_order','is_active'];
    protected function casts(): array { return ['sort_order'=>'integer','is_active'=>'boolean']; }
    public function menuItems(): HasMany { return $this->hasMany(MenuItem::class); }
}
