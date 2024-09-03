<?php

namespace App\Models;

use App\Enums\CancellationRequestStatus;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class CancellationRequest extends Model
{
    use SoftDeletes;

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

    public function order() : BelongsTo {
        return $this->belongsTo(Order::class);
    }

    // BelongsToThroughUser
}
