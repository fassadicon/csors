<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;

class Food extends Pivot
{
    // use SoftDeletes;

    protected $table = 'foods';

    protected $fillable = [
        'serving_type_id',
        'food_detail_id',
        'price',
        'description'
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2'
        ];
    }

    public function foodDetail(): BelongsTo
    {
        return $this->belongsTo(FoodDetail::class);
    }

    public function servingType(): BelongsTo
    {
        return $this->belongsTo(ServingType::class);
    }

    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'orderable');
    }

    public function packageItems(): MorphMany
    {
        return $this->morphMany(PackageItem::class, 'packageable');
    }
}
