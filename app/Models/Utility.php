<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Utility extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'caterer_id',
        'name',
        'description',
        'image_path',
        'price',
    ];

    public function caterer(): BelongsTo
    {
        return $this->belongsTo(Caterer::class);
    }

    public function orderItems(): MorphMany
    {
        return $this->morphMany(OrderItem::class, 'orderable');
    }
}
