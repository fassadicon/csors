<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use App\Enums\CancellationRequestStatus;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class CancellationRequest extends Model
{
    use SoftDeletes, LogsActivity;

    protected $table = 'cancellation_requests';

    protected $fillable = [
        'order_id',
        'status',
        'reason',
        'response',
    ];

    protected function casts(): array
    {
        return [
            'status' => CancellationRequestStatus::class,
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    // BelongsToThroughUser

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('cancellation request') // Customizing the log name
        ;
    }
}
