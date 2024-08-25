<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class FoodCategory extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'caterer_id',
        'name',
        'description'
    ];

    public function foodDetails(): HasMany
    {
        return $this->hasMany(FoodDetail::class);
    }

    public function caterer() : BelongsTo {
        return $this->belongsTo(Caterer::class);
    }
}
