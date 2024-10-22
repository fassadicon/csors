<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class FoodDetail extends Model
{
    use SoftDeletes, LogsActivity;
    use \Znck\Eloquent\Traits\BelongsToThrough;

    protected $fillable = [
        'food_category_id',
        'name',
        'description',
    ];

    // protected $casts = [
    //     'images' => 'array',
    // ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('food detail') // Customizing the log name
        ;
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    public function servingTypes(): BelongsToMany
    {
        return $this->belongsToMany(ServingType::class, 'foods')
            ->using(Food::class)
            ->withPivot('price', 'id', 'description')
            ->withTimestamps();
    }

    public function foodCategory(): BelongsTo
    {
        return $this->belongsTo(FoodCategory::class);
    }

    public function caterer(): \Znck\Eloquent\Relations\BelongsToThrough
    {
        return $this->belongsToThrough(Caterer::class, FoodCategory::class);
    }




    public function getFirstImagePath()
    {
        if ($this->images == null) {
            return false;
        }

        $firstImage = $this->images->first();

        if ($firstImage) {
            return $firstImage->path;
        }
    }
}
