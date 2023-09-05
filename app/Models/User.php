<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
        'major',
        'bio'
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
        'major',
        'year',
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

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

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

    public function waypoints()
    {
        // TODO: Get all waypoints for all rides the user joined? Or make ride ID a parameter?
        // Use pivot table or some sort of through()?
    }
}
