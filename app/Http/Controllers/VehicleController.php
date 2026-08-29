<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class VehicleController extends Controller
{
    // Catalogue public : recherche multi-criteres (texte libre marque/modele,
    // categorie, transmission, places, fourchette de prix).
    //
    // Un vehicule s'affiche quel que soit son statut de disponibilite du
    // moment (en_location, maintenance...) : la disponibilite reelle depend
    // des dates choisies, verifiee au moment de la reservation
    // (Vehicle::isAvailableBetween). Seul "hors_service" (vehicule retire
    // definitivement de la flotte active par l'admin) reste cache.
    public function index(Request $request): Response
    {
        $vehicles = Vehicle::query()
            ->where('status', '!=', 'hors_service')
            ->when($request->q, function ($query, $term) {
                $query->where(function ($q) use ($term) {
                    $q->where('brand', 'like', "%{$term}%")
                        ->orWhere('model', 'like', "%{$term}%");
                });
            })
            ->when($request->category, fn ($q, $v) => $q->where('category', $v))
            ->when($request->transmission, fn ($q, $v) => $q->where('transmission', $v))
            ->when($request->seats, fn ($q, $v) => $q->where('seats', '>=', $v))
            ->when($request->price_min, fn ($q, $v) => $q->where('daily_price', '>=', $v))
            ->when($request->price_max, fn ($q, $v) => $q->where('daily_price', '<=', $v))
            ->orderBy('daily_price')
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('Vehicles/Index', [
            'vehicles' => $vehicles,
            'filters' => $request->only(['q', 'category', 'transmission', 'seats', 'price_min', 'price_max']),
        ]);
    }

    public function show(Vehicle $vehicle): Response
    {
        if ($vehicle->status === 'hors_service') {
            abort(404);
        }

        return Inertia::render('Vehicles/Show', [
            'vehicle' => $vehicle,
            'bookedRanges' => $vehicle->bookedRanges ?? [],
        ]);
    }
}
