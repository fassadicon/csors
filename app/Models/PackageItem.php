<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PackageItem extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'packageable_type',
        'packageable_id',
        'package_id',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('package item') // Customizing the log name
        ;
    }

    public function packageable(): MorphTo
    {
        return $this->morphTo()->withTrashed();
    }
}
