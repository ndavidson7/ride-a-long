<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

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
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    public function emergencyContacts(): HasMany
    {
        return $this->hasMany(EmergencyContact::class);
    }

    public function requests(): HasMany
    {
        return $this->hasMany(Request::class);
    }

    public function responses(): HasMany
    {
        return $this->hasMany(Response::class);
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
}
