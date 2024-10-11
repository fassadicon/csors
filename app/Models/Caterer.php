<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Caterer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
        'email',
        'phone_number',
        'name',
        'about',
        'logo_path',
        'requirements_path',
        'is_verified',
    ];

    // protected $casts = [
    //     'images' => 'array',
    // ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function servingTypes(): HasMany
    {
        return $this->hasMany(ServingType::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(order::class);
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

    public function packages()
    {
        return Package::whereHas('events', function ($query) {
            $query->where('caterer_id', $this->id);
        })->distinct();
    }

    public function utilities(): HasMany
    {
        return $this->hasMany(Utility::class);
    }

    public function promos(): HasMany
    {
        return $this->hasMany(Promo::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }
}
