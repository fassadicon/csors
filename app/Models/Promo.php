<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Promo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'caterer_id',
        'type',
        'name',
        'value',
        'start_date',
        'end_date',
        'minimum'
    ];

    public function caterer() : BelongsTo {
        return $this->belongsTo(Caterer::class);
    }
}
