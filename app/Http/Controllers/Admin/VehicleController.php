<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Vehicle;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Inertia\Inertia;
use Inertia\Response;

class VehicleController extends Controller
{
    // C'est ICI, et uniquement ici, que la flotte visible cote client est
    // decidee : ajout d'un vehicule, prix, photo, et surtout son "status"
    // qui determine s'il apparait comme disponible a la reservation.
    public function index(): Response
    {
        return Inertia::render('Admin/Vehicles/Index', [
            'vehicles' => Vehicle::withCount('reservations')->orderBy('brand')->paginate(12),
        ]);
    }

    public function create(): Response
    {
        return Inertia::render('Admin/Vehicles/Form', ['vehicle' => null]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['uuid'] = (string) Str::uuid();

        if ($request->hasFile('photo')) {
            $data['photo_path'] = $request->file('photo')->store('vehicles', config('filesystems.default'));
        }

        Vehicle::create($data);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicule ajoute a la flotte.');
    }

    public function edit(Vehicle $vehicle): Response
    {
        return Inertia::render('Admin/Vehicles/Form', ['vehicle' => $vehicle]);
    }

    public function update(Request $request, Vehicle $vehicle): RedirectResponse
    {
        $data = $this->validated($request, $vehicle->id);

        if ($request->hasFile('photo')) {
            // On remplace : l'ancienne photo est supprimee du disque pour ne pas
            // accumuler des fichiers orphelins.
            if ($vehicle->photo_path) {
                Storage::disk(config('filesystems.default'))->delete($vehicle->photo_path);
            }
            $data['photo_path'] = $request->file('photo')->store('vehicles', config('filesystems.default'));
        }

        $vehicle->update($data);

        return redirect()->route('admin.vehicles.index')->with('success', 'Vehicule mis a jour.');
    }

    public function destroy(Vehicle $vehicle): RedirectResponse
    {
        // Retirer plutot que supprimer si des reservations existent deja,
        // pour ne pas casser l'historique - simple garde-fou.
        if ($vehicle->reservations()->exists()) {
            $vehicle->update(['status' => 'hors_service']);

            return back()->with('success', 'Vehicule retire de la flotte active (historique conserve).');
        }

        if ($vehicle->photo_path) {
            Storage::disk(config('filesystems.default'))->delete($vehicle->photo_path);
        }

        $vehicle->delete();

        return back()->with('success', 'Vehicule supprime.');
    }

    private function validated(Request $request, ?int $ignoreId = null): array
    {
        $data = $request->validate([
            'brand' => ['required', 'string', 'max:100'],
            'model' => ['required', 'string', 'max:100'],
            'year' => ['required', 'integer', 'min:1990', 'max:'.(date('Y') + 1)],
            'plate_number' => ['required', 'string', 'max:20', Rule::unique('vehicles')->ignore($ignoreId)],
            'category' => ['required', 'in:berline,suv,4x4,minibus,citadine'],
            'seats' => ['required', 'integer', 'min:1', 'max:30'],
            'transmission' => ['required', 'in:manuelle,automatique'],
            'daily_price' => ['required', 'numeric', 'min:0'],
            'status' => ['required', 'in:disponible,en_location,maintenance,hors_service'],
            'description' => ['nullable', 'string'],
            // "image" verifie le type reel du fichier (pas juste l'extension) ;
            // 4096 Ko = 4 Mo max, suffisant pour une photo de vehicule web.
            'photo' => ['nullable', 'image', 'max:4096'],
        ]);

        unset($data['photo']); // gere separement via $request->file('photo'), pas fillable tel quel

        return $data;
    }
}
