<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;
    public const CHANNEL_GATEWAY='gateway', CHANNEL_CASHIER='cashier';
    public const METHOD_CASH='cash', METHOD_CARD='card', METHOD_QRIS='qris', METHOD_EWALLET='ewallet', METHOD_TRANSFER='bank_transfer';
    public const STATUS_PENDING='pending', STATUS_PAID='paid', STATUS_FAILED='failed', STATUS_EXPIRED='expired', STATUS_CANCELLED='cancelled', STATUS_REFUNDED='refunded';
    protected $fillable = ['order_id','processed_by','payment_number','payment_channel','payment_method','provider','provider_transaction_id','amount','paid_amount','change_amount','status','fraud_status','payment_url','snap_token','provider_response','note','expired_at','paid_at','failed_at','refunded_at'];
    protected function casts(): array { return ['amount'=>'decimal:2','paid_amount'=>'decimal:2','change_amount'=>'decimal:2','provider_response'=>'array','expired_at'=>'datetime','paid_at'=>'datetime','failed_at'=>'datetime','refunded_at'=>'datetime']; }
    public function order(): BelongsTo { return $this->belongsTo(Order::class); }
    public function processedBy(): BelongsTo { return $this->belongsTo(User::class,'processed_by'); }
    public function webhooks(): HasMany { return $this->hasMany(PaymentWebhook::class); }
}
