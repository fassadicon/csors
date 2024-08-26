<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class FoodDetail extends Model
{
    use SoftDeletes;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'food_category_id',
        'name',
        'description',
        'image_path'
    ];

    public function servingTypes(): BelongsToMany
    {
        return $this->belongsToMany(ServingType::class, 'foods')
            ->using(Food::class)
            ->withPivot('price')
            ->withTimestamps();
    }

    public function foodCategory(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class);
    }

    public function caterer() : \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Caterer::class, FoodCategory::class);
    }
}
