<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Driver extends Model
{
    use HasFactory;

    protected $fillable = ['uuid', 'user_id', 'license_number', 'license_expiry', 'bilingual', 'status'];

    protected function casts(): array
    {
        return ['license_expiry' => 'date', 'bilingual' => 'boolean'];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }
}
