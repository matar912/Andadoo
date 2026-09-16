<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VehicleController extends Controller
{
    public function index()
    {
        $vehicles = Vehicle::withCount('reservations')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Vehicles/Index', [
            'vehicles' => $vehicles,
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Vehicles/Form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'brand'        => 'required|string|max:255',
            'model'        => 'required|string|max:255',
            'year'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'plate_number' => 'required|string|unique:vehicles,plate_number',
            'category'     => 'required|string',
            'seats'        => 'required|integer|min:1',
            'transmission' => 'required|string',
            'daily_price'  => 'required|numeric|min:0',
            'status'       => 'required|string',
            'description'  => 'nullable|string',
            // HEIC/HEIF ajoutes : par defaut, un iPhone capture ses photos
            // dans ce format, non couvert par la validation "image" seule.
            'photo'        => 'nullable|mimes:jpeg,png,jpg,webp,heic,heif|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo_path'] = $request->file('photo')->store('vehicles', 'supabase');
        }

        Vehicle::create($validated);

        return redirect()->back()->with('success', 'Véhicule ajouté avec succès à la flotte.');
    }

    public function edit(Vehicle $vehicle)
    {
        return Inertia::render('Admin/Vehicles/Form', [
            'vehicle' => $vehicle,
        ]);
    }

    public function update(Request $request, Vehicle $vehicle)
    {
        $validated = $request->validate([
            'brand'        => 'required|string|max:255',
            'model'        => 'required|string|max:255',
            'year'         => 'required|integer|min:1900|max:' . (date('Y') + 1),
            'plate_number' => 'required|string|unique:vehicles,plate_number,' . $vehicle->id,
            'category'     => 'required|string',
            'seats'        => 'required|integer|min:1',
            'transmission' => 'required|string',
            'daily_price'  => 'required|numeric|min:0',
            'status'       => 'required|string',
            'description'  => 'nullable|string',
            'photo'        => 'nullable|mimes:jpeg,png,jpg,webp,heic,heif|max:10240',
        ]);

        if ($request->hasFile('photo')) {
            if ($vehicle->photo_path && !filter_var($vehicle->photo_path, FILTER_VALIDATE_URL)) {
                Storage::disk('supabase')->delete($vehicle->photo_path);
            }
            $validated['photo_path'] = $request->file('photo')->store('vehicles', 'supabase');
        }

        $vehicle->update($validated);

        return redirect()->back()->with('success', 'Véhicule mis à jour avec succès.');
    }

    public function destroy(Vehicle $vehicle)
    {
        if ($vehicle->reservations()->exists()) {
            $vehicle->update(['status' => 'hors_service']);
            return redirect()->back()->with('success', 'Véhicule retiré de la flotte active.');
        }

        if ($vehicle->photo_path && !filter_var($vehicle->photo_path, FILTER_VALIDATE_URL)) {
            Storage::disk('s3')->delete($vehicle->photo_path);
        }

        $vehicle->delete();

        return redirect()->back()->with('success', 'Véhicule supprimé définitivement.');
    }
}
