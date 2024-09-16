<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Event extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'caterer_id',
        'name',
        'description',
    ];

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class, 'event_package')
            ->using(EventPackage::class)
            ->withTimestamps();
    }

    public function caterer(): BelongsTo
    {
        return $this->belongsTo(Caterer::class);
    }
}
