<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class PaymentController extends Controller
{
    // File de verification manuelle : chaque paiement declare par un client
    // (carte, Wave, Orange Money, Free Money) reste "en_attente" tant que
    // l'admin n'a pas verifie la reception reelle des fonds et confirme ici.
    public function index(): Response
    {
        return Inertia::render('Admin/Payments/Index', [
            'payments' => Payment::with(['reservation.client', 'reservation.vehicle'])
                ->orderByRaw("CASE WHEN status = 'en_attente' THEN 0 ELSE 1 END")
                ->latest()
                ->paginate(15),
        ]);
    }

    public function confirm(Payment $payment): RedirectResponse
    {
        abort_unless($payment->status === 'en_attente', 422, 'Ce paiement a deja ete traite.');

        $payment->update(['status' => 'reussi', 'paid_at' => now()]);

        return back()->with('success', 'Paiement confirme.');
    }

    public function reject(Payment $payment): RedirectResponse
    {
        abort_unless($payment->status === 'en_attente', 422, 'Ce paiement a deja ete traite.');

        $payment->update(['status' => 'echoue']);

        return back()->with('success', 'Paiement rejete.');
    }
}
