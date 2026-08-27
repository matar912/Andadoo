<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;
use Inertia\Inertia;

class VehicleController extends Controller
{
    // Catalogue public : recherche par categorie, dates et transmission.
    public function index(Request $request)
    {
        $vehicles = Vehicle::query()
            ->available()
            ->when($request->category, fn ($q, $v) => $q->where('category', $v))
            ->when($request->seats, fn ($q, $v) => $q->where('seats', '>=', $v))
            ->orderBy('daily_price')
            ->paginate(9)
            ->withQueryString();

        return Inertia::render('Vehicles/Index', [
            'vehicles' => $vehicles,
            'filters' => $request->only(['category', 'seats']),
        ]);
    }

    public function show(Vehicle $vehicle)
    {
        return Inertia::render('Vehicles/Show', [
            'vehicle' => $vehicle,
        ]);
    }
}
