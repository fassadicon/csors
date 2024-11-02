<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisabledDate extends Model
{
    protected $fillable = ['date', 'caterer_id', 'remarks'];
    protected $table = 'disabled_dates';

    public function caterer(): BelongsTo
    {
        return $this->belongsTo(Caterer::class);
    }
}
