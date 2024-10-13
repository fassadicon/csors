<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Feedback extends Model
{
    use SoftDeletes;
    protected $table = 'feedbacks';
    protected $fillable = [
        'caterer_id',
        'user_id',
        'rating',
        'comment'
    ];

    public function users(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caterers(): BelongsTo
    {
        return $this->belongsTo(Caterer::class);
    }
}
