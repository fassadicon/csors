<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Promo extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'caterer_id',
        'type',
        'name',
        'value',
        'start_date',
        'end_date',
        'minimum'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('promo') // Customizing the log name
        ;
    }

    public function caterer(): BelongsTo
    {
        return $this->belongsTo(Caterer::class);
    }
}
