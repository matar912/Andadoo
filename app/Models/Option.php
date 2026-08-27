<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Option extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'extra_price'];

    public function reservations()
    {
        return $this->belongsToMany(Reservation::class, 'option_reservation');
    }
}
