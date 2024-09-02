<?php

namespace App\Models;

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

    public function order() : BelongsTo {
        return $this->belongsTo(Order::class);
    }

    // BelongsToThroughUser
}
