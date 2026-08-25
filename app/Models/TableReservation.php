<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TableReservation extends Model
{
    use HasFactory;
    public const STATUS_PENDING='pending', STATUS_CONFIRMED='confirmed', STATUS_SEATED='seated', STATUS_COMPLETED='completed', STATUS_CANCELLED='cancelled', STATUS_NO_SHOW='no_show';
    protected $fillable = ['access_token','reservation_number','user_id','restaurant_table_id','customer_name','customer_phone','customer_email','guest_count','reservation_start','reservation_end','status','special_request','cancellation_reason'];
    protected function casts(): array { return ['guest_count'=>'integer','reservation_start'=>'datetime','reservation_end'=>'datetime']; }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function restaurantTable(): BelongsTo { return $this->belongsTo(RestaurantTable::class); }
}
