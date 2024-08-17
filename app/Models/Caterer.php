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
        'name',
        'description',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Service::class, 'caterer_id', 'service_id', 'id', 'id');
    }

    public function services(): HasMany
    {
        return $this->hasMany(Service::class);
    }

    public function events(): HasMany
    {
        return $this->hasMany(Event::class);
    }

    public function servingTypes(): HasMany
    {
        return $this->hasMany(ServingType::class);
    }
}
