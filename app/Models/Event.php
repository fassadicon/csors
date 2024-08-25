<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'caterer_id',
        'name',
        'description',
    ];

    public function packages(): HasMany
    {
        return $this->hasMany(Package::class)
            ->using(EventPackage::class)
            ->withTimestamps();
    }
}
