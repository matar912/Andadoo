<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class VehicleController extends Controller
{
    /**
     * Liste des véhicules pour l'administration.
     */
    public function index()
    {
        $vehicles = Vehicle::withCount('reservations')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return Inertia::render('Admin/Vehicles/Index', [
            'vehicles' => $vehicles,
        ]);
    }

    /**
     * Afficher le formulaire de création.
     */
    public function create()
    {
        return Inertia::render('Admin/Vehicles/Form');
    }

    /**
     * Enregistrer un nouveau véhicule avec sa photo dans Supabase.
     */
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
            'photo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240', // Max 10 Mo
        ]);

        if ($request->hasFile('photo')) {
            // Téléversement explicite vers Supabase Storage sur le disque 's3'
            $validated['photo_path'] = $request->file('photo')->store('vehicles', 's3');
        }

        Vehicle::create($validated);

        return redirect()->back()->with('success', 'Véhicule ajouté avec succès à la flotte.');
    }

    /**
     * Afficher le formulaire d'édition.
     */
    public function edit(Vehicle $vehicle)
    {
        return Inertia::render('Admin/Vehicles/Form', [
            'vehicle' => $vehicle,
        ]);
    }

    /**
     * Mettre à jour un véhicule existant et sa photo.
     */
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
            'photo'        => 'nullable|image|mimes:jpeg,png,jpg,webp|max:10240', // Max 10 Mo
        ]);

        if ($request->hasFile('photo')) {
            // 1. Supprimer l'ancienne photo sur Supabase S3 si elle existe et n'est pas une URL externe
            if ($vehicle->photo_path && !filter_var($vehicle->photo_path, FILTER_VALIDATE_URL)) {
                Storage::disk('s3')->delete($vehicle->photo_path);
            }

            // 2. Enregistrer la nouvelle photo sur Supabase S3
            $validated['photo_path'] = $request->file('photo')->store('vehicles', 's3');
        }

        $vehicle->update($validated);

        return redirect()->back()->with('success', 'Véhicule mis à jour avec succès.');
    }

    /**
     * Supprimer ou retirer un véhicule.
     */
    public function destroy(Vehicle $vehicle)
    {
        // Si le véhicule est lié à des réservations, on le passe en hors service
        if ($vehicle->reservations()->exists()) {
            $vehicle->update(['status' => 'hors_service']);
            return redirect()->back()->with('success', 'Véhicule retiré de la flotte active.');
        }

        // Supprimer la photo sur Supabase S3 avant de supprimer le véhicule
        if ($vehicle->photo_path && !filter_var($vehicle->photo_path, FILTER_VALIDATE_URL)) {
            Storage::disk('s3')->delete($vehicle->photo_path);
        }

        $vehicle->delete();

        return redirect()->back()->with('success', 'Véhicule supprimé définitivement.');
    }
}
