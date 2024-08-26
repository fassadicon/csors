<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class ServingType extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'caterer_id',
        'name',
        'description',
        'image_path',
    ];


    public function foodDetails(): BelongsToMany
    {
        return $this->belongsToMany(FoodDetail::class, 'foods')
            ->using(Food::class)
            ->withPivot('price')
            ->withTimestamps();
    }

    public function caterer(): BelongsTo
    {
        return $this->belongsTo(Caterer::class);
    }
}
