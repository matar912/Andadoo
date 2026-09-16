<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'client_id', 'vehicle_id', 'driver_id', 'partner_id', 'formula',
        'with_driver', 'flight_number', 'pickup_location', 'dropoff_location',
        'start_at', 'end_at', 'status', 'total_price',
    ];

    protected function casts(): array
    {
        return [
            'with_driver' => 'boolean',
            'start_at' => 'datetime',
            'end_at' => 'datetime',
            'total_price' => 'decimal:2',
        ];
    }

    public function client()
    {
        return $this->belongsTo(User::class, 'client_id');
    }

    public function vehicle()
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function driver()
    {
        return $this->belongsTo(Driver::class);
    }

    public function partner()
    {
        return $this->belongsTo(Partner::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function options()
    {
        return $this->belongsToMany(Option::class, 'option_reservation');
    }
}
