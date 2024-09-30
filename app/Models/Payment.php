<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Payment extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'order_id',
        'status',
        'type',
        'method',
        'amount',
        'reference_no',
        'remarks',
    ];

    public function order() : BelongsTo {
        return $this->belongsTo(Order::class);
    }

    // BelongsToThroughUser
}
