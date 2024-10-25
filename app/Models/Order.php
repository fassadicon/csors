<?php

namespace App\Models;

use App\Enums\OrderStatus;
use App\Enums\PaymentStatus;
use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class Order extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'caterer_id',
        'recipient',
        'promo_id',
        'deducted_amount',
        'delivery_amount',
        'recipient',
        'location',
        'remarks',
        'start',
        'end',
        'total_amount',
        'final_amount',
        'payment_status',
        'order_status',
        'decline_reason',
    ];

    protected function casts(): array
    {
        return [
            'payment_status' => PaymentStatus::class,
            'order_status' => OrderStatus::class,
            'total_amount' => 'decimal:2',
            'start' => 'datetime',
            'end' => 'datetime',
            'created_at' => 'datetime'
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('order') // Customizing the log name
        ;
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
        return $this->hasMany(OrderItem::class)->withTrashed();
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function feedback(): HasOne
    {
        return $this->hasOne(Feedback::class, foreignKey: 'order_id');
    }

    public function cancellationRequest(): HasOne
    {
        return $this->hasOne(CancellationRequest::class);
    }
}
