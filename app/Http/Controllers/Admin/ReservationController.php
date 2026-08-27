<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ReservationController extends Controller
{
    // Aucune reservation client n'est confirmee automatiquement : elle nait
    // "en_attente" et c'est l'admin qui la valide ou la refuse ici, en
    // connaissance de la disponibilite reelle du vehicule et du chauffeur.
    public function index(): Response
    {
        return Inertia::render('Admin/Reservations/Index', [
            'reservations' => Reservation::with(['client', 'vehicle'])
                ->orderByRaw("CASE WHEN status = 'en_attente' THEN 0 ELSE 1 END")
                ->latest()
                ->paginate(15),
        ]);
    }

    public function validate_(Reservation $reservation): RedirectResponse
    {
        abort_unless($reservation->status === 'en_attente', 422, 'Cette reservation a deja ete traitee.');

        // On ne touche plus au statut global du vehicule : la disponibilite
        // reelle se recalcule par periode (voir Vehicle::isAvailableBetween),
        // pas via un simple statut qui bloquerait toutes les dates futures.
        $reservation->update(['status' => 'confirmee']);

        return back()->with('success', 'Reservation validee.');
    }

    public function refuse(Reservation $reservation): RedirectResponse
    {
        abort_unless($reservation->status === 'en_attente', 422, 'Cette reservation a deja ete traitee.');

        $reservation->update(['status' => 'annulee']);

        return back()->with('success', 'Reservation refusee.');
    }
}
