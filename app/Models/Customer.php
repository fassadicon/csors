<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'user_id',
    ];

    public function user() : BelongsTo {
        return $this->belongsTo(User::class);
    }

    public function orders() : HasMany {
        return $this->hasMany(Order::class);
    }
}
