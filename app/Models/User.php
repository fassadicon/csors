<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Filament\Panel;
use Spatie\Activitylog\LogOptions;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Filament\Models\Contracts\FilamentUser;
use Spatie\Activitylog\Traits\LogsActivity;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Activitylog\Traits\CausesActivity;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use BezhanSalleh\FilamentShield\Traits\HasPanelShield;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;
    use HasRoles, HasPanelShield;
    use SoftDeletes;
    use LogsActivity, CausesActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'last_name',
        'first_name',
        'middle_name',
        'ext_name',
        'phone_number',
        'verification_image_path',
        'is_verified',
        'is_customer',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasAnyRole(['caterer', 'superadmin']);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logAll() // Logging only the specified attributes - roles->*->name
            ->useLogName('user') // Customizing the log name
        ;
    }


    public function caterer(): HasOne
    {
        return $this->hasOne(Caterer::class);
    }

    public function feedbacks(): HasMany
    {
        return $this->hasMany(Feedback::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function images(): MorphMany
    {
        return $this->morphMany(Image::class, 'imageable');
    }

    // public function servingTypes(): HasManyThrough
    // {
    //     return $this->hasManyThrough(ServingType::class, Caterer::class);
    // }

    // public function cancellationRequests(): HasManyThrough
    // {
    //    orders->cancellationRequests
    // }

    // public function payments(): HasManyThrough
    // {
    //    orders->payments
    // }

    // CHECK IF CUSTOMER IS REPORTED LAST 15 Days
    public function isReported(): HasOne
    {
        $daysAgo = Carbon::now()->subDays(15);

        return $this->hasOne(ReportedUser::class, 'reported_user')
            ->where('created_at', '>=', $daysAgo);
    }
}
