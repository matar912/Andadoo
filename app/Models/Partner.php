<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
    use HasFactory;

    // Partenaire commercial (distribution/promotion) - jamais un apporteur de vehicule.
    protected $fillable = ['uuid', 'name', 'type', 'contact_email', 'contact_phone'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
