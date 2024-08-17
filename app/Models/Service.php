<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'caterer_id',
        'event_id',
        'serving_type_id',
        'name',
        'price',
        'description',
    ];

    public function event() : BelongsTo {
        return $this->belongsTo(Event::class);
    }

    public function servingType() : BelongsTo {
        return $this->belongsTo(ServingType::class);
    }

    public function orders() : HasMany {
        return $this->hasMany(Order::class);
    }
}
