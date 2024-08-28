<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\SoftDeletes;

class Caterer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'name',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function servingTypes(): HasMany
    {
        return $this->hasMany(ServingType::class);
    }

    public function foodCategories(): HasMany
    {
        return $this->hasMany(FoodCategory::class);
    }

    public function foodDetails(): HasManyThrough
    {
        return $this->hasManyThrough(FoodDetail::class, FoodCategory::class);
    }

    public function foods(): HasManyThrough
    {
        return $this->hasManyThrough(
            Food::class,
            ServingType::class,
        )->with('foodDetail');
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function packages(): HasManyThrough
    {
        return $this->hasManyThrough(
            Package::class,
            Event::class,
            'caterer_id',            // Foreign key on the events table (caterer_id in events)
            'id',                    // Foreign key on the packages table (package_id, but here we're using the id)
            'id',                    // Local key on the caterers table (id of caterer)
            'id'                     // Local key on the events table (id of event, linked to the package)
        );
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }
}
