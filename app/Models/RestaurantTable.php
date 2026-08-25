<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RestaurantTable extends Model
{
    use HasFactory;
    protected $fillable = ['table_number','qr_token','name','area','capacity','is_active','description'];
    protected function casts(): array { return ['capacity'=>'integer','is_active'=>'boolean']; }
    public function orders(): HasMany { return $this->hasMany(Order::class); }
    public function reservations(): HasMany { return $this->hasMany(TableReservation::class); }
    public function scopeActive(Builder $query): Builder { return $query->where('is_active', true); }
}
