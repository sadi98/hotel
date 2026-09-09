<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'order_id',
        'processed_by',
        'payment_number',
        'payment_channel',
        'payment_method',
        'provider',
        'provider_transaction_id',
        'amount',
        'paid_amount',
        'change_amount',
        'status',
        'fraud_status',
        'payment_url',
        'snap_token',
        'provider_response',
        'note',
        'expired_at',
        'paid_at',
        'failed_at',
        'refunded_at',
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2',
            'paid_amount' => 'decimal:2',
            'change_amount' => 'decimal:2',
            'provider_response' => 'array',
            'expired_at' => 'datetime',
            'paid_at' => 'datetime',
            'failed_at' => 'datetime',
            'refunded_at' => 'datetime',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(
            User::class,
            'processed_by'
        );
    }

    public function webhooks(): HasMany
    {
        return $this->hasMany(PaymentWebhook::class)
            ->latest();
    }

    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function isRefunded(): bool
    {
        return $this->status === 'refunded';
    }
}
