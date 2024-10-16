<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Spatie\Activitylog\Traits\LogsActivity;

class OrderItem extends Model
{
    use SoftDeletes, LogsActivity;

    protected $fillable = [
        'orderable_type',
        'orderable_id',
        'quantity',
        'amount'
    ];

    protected function casts(): array
    {
        return [
            'amount' => 'decimal:2'
        ];
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('order item') // Customizing the log name
        ;
    }

    public function orderable(): MorphTo
    {
        return $this->morphTo();
    }
}
