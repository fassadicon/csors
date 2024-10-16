<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Payment extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'order_id',
        'type',
        'method',
        'amount',
        'reference_no',
        'remarks',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('payment') // Customizing the log name
        ;
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // BelongsToThroughUser
}
