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

    // 1. OBLIGATOIRE : Force Laravel à inclure photo_url dans les données envoyées à Inertia / Vue
    protected $appends = ['photo_url'];

    protected function casts(): array
    {
        return ['daily_price' => 'decimal:2'];
    }

    // 2. OBLIGATOIRE : Construit l'URL complète vers Supabase Storage
    public function getPhotoUrlAttribute(): ?string
    {
        if (! $this->photo_path) {
            return null;
        }

        // Si c'est une URL externe complète (ex: Unsplash)
        if (filter_var($this->photo_path, FILTER_VALIDATE_URL)) {
            return $this->photo_path;
        }

        $supabaseUrl = env('SUPABASE_URL', 'https://kjcsubbrvcpfuyeyfjwa.supabase.co');
        $bucket = env('AWS_BUCKET', 'images');
        $path = ltrim($this->photo_path, '/');

        return "{$supabaseUrl}/storage/v1/object/public/{$bucket}/{$path}";
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

    public function isAvailableBetween(string $start, string $end, ?int $ignoreReservationId = null): bool
    {
        return ! $this->reservations()
            ->whereIn('status', ['en_attente', 'confirmee', 'en_cours'])
            ->when($ignoreReservationId, fn ($q, $id) => $q->where('id', '!=', $id))
            ->where('start_at', '<', $end)
            ->where('end_at', '>', $start)
            ->exists();
    }

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
