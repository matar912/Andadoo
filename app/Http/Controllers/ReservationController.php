<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;

class ReservationController extends Controller
{
    // Formulaire de reservation : formule diaspora/tourisme mise en avant,
    // numero de vol optionnel pour anticiper l'arrivee. Les periodes deja
    // occupees par une autre reservation sont affichees a titre indicatif.
    public function create(Request $request)
    {
        $vehicle = Vehicle::findOrFail($request->vehicle_id);

        return Inertia::render('Reservations/Create', [
            'vehicle' => $vehicle,
            'bookedRanges' => $vehicle->bookedRanges(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'vehicle_id' => ['required', 'exists:vehicles,id'],
            'formula' => ['required', 'in:transfert_simple,transfert_plus_location,longue_duree,location_locale'],
            'with_driver' => ['boolean'],
            'flight_number' => ['nullable', 'string', 'max:20'],
            'pickup_location' => ['required', 'string', 'max:255'],
            'dropoff_location' => ['nullable', 'string', 'max:255'],
            'start_at' => ['required', 'date', 'after_or_equal:today'],
            'end_at' => ['required', 'date', 'after:start_at'],
        ]);

        $vehicle = Vehicle::findOrFail($data['vehicle_id']);

        // La disponibilite reelle se verifie sur la periode demandee, pas sur
        // un statut global : un vehicule deja pris du 12 au 15 reste
        // reservable du 20 au 25.
        if (! $vehicle->isAvailableBetween($data['start_at'], $data['end_at'])) {
            throw ValidationException::withMessages([
                'start_at' => 'Ce véhicule est déjà réservé sur une partie de cette période. Choisissez d\'autres dates.',
            ]);
        }

        $days = max(1, now()->parse($data['start_at'])->diffInDays($data['end_at']));

        $reservation = Reservation::create([
            ...$data,
            'uuid' => (string) Str::uuid(),
            'client_id' => $request->user()->id,
            'status' => 'en_attente',
            'total_price' => $vehicle->daily_price * $days,
        ]);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Votre reservation a bien ete enregistree.');
    }

    public function index(Request $request)
    {
        return Inertia::render('Reservations/Index', [
            'reservations' => Reservation::with('vehicle')
                ->where('client_id', $request->user()->id)
                ->latest()
                ->paginate(10),
        ]);
    }

    public function show(Request $request, Reservation $reservation)
    {
        // Un client ne doit voir que ses propres reservations.
        abort_unless($reservation->client_id === $request->user()->id, 403);

        $reservation->load(['vehicle', 'driver.user', 'payments']);

        return Inertia::render('Reservations/Show', [
            'reservation' => $reservation,
        ]);
    }
}
