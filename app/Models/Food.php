<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Food extends Pivot
{
    use SoftDeletes;

    protected $table = 'foods';

    protected $fillable = [
        'serving_type_id',
        'food_detail_id',
        'price'
    ];

    public function foodDetail(): BelongsTo
    {
        return $this->belongsTo(FoodDetail::class);
    }

    public function servingType(): BelongsTo
    {
        return $this->belongsTo(ServingType::class);
    }
}
