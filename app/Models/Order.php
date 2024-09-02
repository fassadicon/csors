<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'caterer_id',
        'promo_id',
        'deducted_amount',
        'location',
        'remarks',
        'start',
        'end',
        'total_amount',
        'payment_status',
        'order_status',
    ];

    protected function casts(): array
    {
        return [
            'payment_status' => PaymentStatus::class,
            'order_status' => OrderStatus::class,
            'total_amount' => 'decimal:2'
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caterer(): BelongsTo
    {
        return $this->belongsTo(Caterer::class);
    }

    public function promo(): BelongsTo
    {
        return $this->belongsTo(Promo::class);
    }

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }
}
