<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Activitylog\Traits\LogsActivity;

class Event extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'caterer_id',
        'name',
        'description',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('event') // Customizing the log name
        ;
    }

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
