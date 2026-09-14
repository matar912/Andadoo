<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Option;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Inertia\Response;

class OptionController extends Controller
{
    // Options facultatives proposees a la reservation (siege enfant, GPS,
    // assurance renforcee, kilometrage illimite...). Liste + creation sur
    // une seule page : le modele est trop simple pour justifier des ecrans separes.
    public function index(): Response
    {
        return Inertia::render('Admin/Options/Index', [
            'options' => Option::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'extra_price' => ['required', 'numeric', 'min:0'],
        ]);

        Option::create($data);

        return back()->with('success', 'Option ajoutee.');
    }

    public function update(Request $request, Option $option): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:100'],
            'extra_price' => ['required', 'numeric', 'min:0'],
        ]);

        $option->update($data);

        return back()->with('success', 'Option mise a jour.');
    }

    public function destroy(Option $option): RedirectResponse
    {
        // Detacher des reservations existantes avant suppression, pour ne pas
        // casser leur historique de prix (le montant total facture reste inchange).
        $option->reservations()->detach();
        $option->delete();

        return back()->with('success', 'Option supprimee.');
    }
}
