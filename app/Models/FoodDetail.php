<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodDetail extends Model
{
    use SoftDeletes;

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
}
