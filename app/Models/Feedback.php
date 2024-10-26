<?php

namespace App\Models;

use Spatie\Activitylog\LogOptions;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Feedback extends Model
{
    use SoftDeletes, LogsActivity;
    protected $table = 'feedbacks';
    protected $fillable = [
        'order_id',
        'rating',
        'comment'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll()
            ->useLogName('feedback') // Customizing the log name
        ;
    }

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class, foreignKey: 'user_id');
    }

    public function caterers(): BelongsTo
    {
        return $this->belongsTo(Caterer::class, foreignKey: 'caterer_id');
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
