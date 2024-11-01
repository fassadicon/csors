<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class ReportedUser extends Model
{
    use SoftDeletes;
    use HasFactory;
    protected $guarded = [];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function caterer(): HasOne
    {
        return $this->hasOne(Caterer::class);
    }

    public function reporter(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relationship to the reported user
    public function reportedUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_user');
    }
}
