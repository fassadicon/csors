<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class Caterer extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'user_id',
        'downpayment',
        'email',
        'phone_number',
        'name',
        'about',
        'logo_path',
        'qr_path',
        'requirements_path',
        'is_verified',
    ];

    // protected $casts = [
    //     'images' => 'array',
    // ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('caterer') // Customizing the log name
        ;
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

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
        return $this->hasMany(Order::class);
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

    public function feedbacksThrough(): HasManyThrough
    {
        return $this->hasManyThrough(Feedback::class, Order::class, 'caterer_id', 'order_id', 'id', 'id');
    }
}
