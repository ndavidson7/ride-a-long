<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ride extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'driver_id',
        'start_time',
        'orig_addr',
        'dest_addr',
        'seats_total',
        'description'
    ];

    public function seatsOpen(): int
    {
        // return $this->seats_total - $this->requests()->count();
        return 2;
    }
}
