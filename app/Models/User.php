<?php

namespace App\Models;

use Musonza\Chat\Traits\Messageable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Contracts\Auth\CanResetPassword;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Auth\Passwords\CanResetPassword as CanResetPasswordTrait;

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
        'first_name',
        'last_name',
        'college_id',
        'email',
    ];

    protected $appends = [
        'name',
        'pfp_url',
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

    public function phone(): Attribute
    {
        return Attribute::make(
            get: fn (string $value) => phone($value, 'US')->formatForMobileDialingInCountry('US'),
            set: fn (string $value) => phone($value, 'US')->formatE164()
        );
    }

    public function name(): Attribute
    {
        return Attribute::make(
            get: fn ($value, array $attributes) => "{$attributes['first_name']} {$attributes['last_name']}"
        );
    }

    public function yearFormatted(): Attribute
    {
        return Attribute::make(
            get: fn (int $value) => match ($value) {
                1 => 'First',
                2 => 'Second',
                3 => 'Third',
                4 => 'Fourth',
                5 => 'Graduate/Further Studies',
                default => null,
            }
        );
    }

    public function isDriver(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->car()->exists()
        );
    }

    public function pfpUrl(): Attribute
    {
        return Attribute::make(
            get: fn () => $this->fetchFirstMedia()['file_url'] ?? null
        );
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
