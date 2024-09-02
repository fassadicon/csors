<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'orderable_type',
        'orderable_id',
        'quantity',
        'amount'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2'
        ];
    }

    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }
}
