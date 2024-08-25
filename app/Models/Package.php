<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Package extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'price',
        'image_path'
    ];

    public function events(): BelongsToMany
    {
        return $this->belongsToMany(Event::class)
            ->using(EventPackage::class)
            ->withTimestamps();
    }
}
