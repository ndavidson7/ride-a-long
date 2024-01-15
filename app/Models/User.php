<?php

namespace App\Models;

use Musonza\Chat\Traits\Messageable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class User extends Authenticatable implements MustVerifyEmail, CanResetPassword
{
    use HasFactory, Notifiable, MediaAlly, Messageable, CanResetPasswordTrait;

    /*
    |--------------------------------------------------------------------------
    | Database Settings
    |--------------------------------------------------------------------------
    */

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'password',
        'phone',
        'first_name',
        'last_name',
        'year',
        'major_id',
        'bio',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'phone',
        'email_verified_at',
        'bio',
        'id',
        'major_id',
        'year',
        'latitude',
        'longitude',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /*
    |--------------------------------------------------------------------------
    | Mutators and Accessors
    |--------------------------------------------------------------------------
    */

    /**
     * Mutate the user's password prior to storing it.
     *
     * @param string $password
     * @return void
     */
    public function setPasswordAttribute(string $password): void
    {
        $this->attributes['password'] = bcrypt($password);
    }

    public function getPhoneAttribute(string $phone): string
    {
        $ac = substr($phone, 0, 3);
        $prefix = substr($phone, 3, 3);
        $suffix = substr($phone, 6);

        return "({$ac}) {$prefix}-{$suffix}";
    }

    public function getNameAttribute(): string
    {
        return "{$this->first_name} {$this->last_name}";
    }

    public function getYearFormattedAttribute(): string|null
    {
        switch ($this->year) {
            case 1:
                return 'First';
            case 2:
                return 'Second';
            case 3:
                return 'Third';
            case 4:
                return 'Fourth';
            case 5:
                return 'Graduate/Further Studies';
            default:
                return null;
        }
    }

    public function getIsDriverAttribute(): bool
    {
        return $this->car()->exists();
    }

    public function getPfpUrlAttribute(): string|null
    {
        return $this->fetchFirstMedia()['file_url'] ?? null;
    }

    public function getParticipantDetails()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'pfp_url' => $this->pfp_url,
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function car(): HasOneThrough
    {
        return $this->hasOneThrough(Car::class, Driver::class)->withDefault();
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function rides(): BelongsToMany
    {
        return $this->belongsToMany(Ride::class)->withPivot('pickup_waypoint_id', 'dropoff_waypoint_id');
    }

    public function major(): BelongsTo
    {
        return $this->belongsTo(Major::class);
    }

    public function college(): BelongsTo
    {
        return $this->belongsTo(College::class);
    }

    public function newRideAlerts(): HasMany
    {
        return $this->hasMany(NewRideAlert::class);
    }

    public function seatOpenAlerts(): HasMany
    {
        return $this->hasMany(SeatOpenAlert::class);
    }

    // public function waypoints(): HasManyThrough
    // {
    //     return $this->hasManyThrough(Waypoint::class, );
    // }
}
