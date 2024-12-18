<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServingType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'caterer_id',
        'name',
        'description',
    ];

    // protected $casts = [
    //     'images' => 'array',
    // ];

    public function foodDetails(): BelongsToMany
    {
        return $this->belongsToMany(FoodDetail::class, 'foods')
            ->using(Food::class)
            ->withPivot('price', 'id', 'description')
            ->withTimestamps();
    }

    public function caterer(): BelongsTo
    {
        return $this->belongsTo(Caterer::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
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
