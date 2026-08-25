<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuPackageItem extends Model
{
    use HasFactory;
    protected $fillable = ['menu_package_id','menu_item_id','quantity','note','sort_order'];
    protected function casts(): array { return ['quantity'=>'integer','sort_order'=>'integer']; }
    public function menuPackage(): BelongsTo { return $this->belongsTo(MenuPackage::class); }
    public function menuItem(): BelongsTo { return $this->belongsTo(MenuItem::class); }
}
