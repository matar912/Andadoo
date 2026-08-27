<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vehicle extends Model
{
    use HasFactory;

    protected $fillable = [
        'uuid', 'brand', 'model', 'year', 'plate_number', 'category',
        'seats', 'transmission', 'daily_price', 'status', 'photo_path', 'description',
    ];

    protected function casts(): array
    {
        return ['daily_price' => 'decimal:2'];
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function maintenanceLogs()
    {
        return $this->hasMany(MaintenanceLog::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'disponible');
    }

    // Verifie qu'aucune reservation active (en attente ou confirmee) de ce
    // vehicule ne chevauche la periode demandee. C'est ce controle, et non
    // le statut global du vehicule, qui determine la disponibilite reelle
    // pour des dates precises : un vehicule "disponible" peut deja etre pris
    // du 12 au 15, tout en restant reservable du 20 au 25.
    public function isAvailableBetween(string $start, string $end, ?int $ignoreReservationId = null): bool
    {
        return ! $this->reservations()
            ->whereIn('status', ['en_attente', 'confirmee', 'en_cours'])
            ->when($ignoreReservationId, fn ($q, $id) => $q->where('id', '!=', $id))
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start)
            ->exists();
    }

    // Periodes deja occupees, pour affichage cote client avant meme qu'il choisisse ses dates.
    public function bookedRanges()
    {
        return $this->reservations()
            ->whereIn('status', ['en_attente', 'confirmee', 'en_cours'])
            ->where('end_at', '>=', now())
            ->orderBy('start_at')
            ->get(['start_at', 'end_at'])
            ->map(fn ($r) => ['start' => $r->start_at->toDateString(), 'end' => $r->end_at->toDateString()]);
    }
}
