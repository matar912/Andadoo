<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Partner;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class PartnerController extends Controller
{
    // Partenaires commerciaux (agences de voyage, hotels, compagnies
    // aeriennes) : jamais des apporteurs de vehicules, uniquement un lien de
    // distribution/attribution pour suivre d'ou vient une reservation.
    public function index(): Response
    {
        return Inertia::render('Admin/Partners/Index', [
            'partners' => Partner::withCount('reservations')->orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['uuid'] = (string) Str::uuid();

        Partner::create($data);

        return back()->with('success', 'Partenaire ajoute.');
    }

    public function update(Request $request, Partner $partner): RedirectResponse
    {
        $partner->update($this->validated($request));

        return back()->with('success', 'Partenaire mis a jour.');
    }

    public function destroy(Partner $partner): RedirectResponse
    {
        if ($partner->reservations()->exists()) {
            return back()->with('error', 'Impossible de supprimer : des reservations sont liees a ce partenaire.');
        }

        $partner->delete();

        return back()->with('success', 'Partenaire supprime.');
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'type' => ['required', 'in:agence_voyage,hotel,compagnie_aerienne,autre'],
            'contact_email' => ['nullable', 'email', 'max:150'],
            'contact_phone' => ['nullable', 'string', 'max:30'],
        ]);
    }
}
