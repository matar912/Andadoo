<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;

class PaymentController extends Controller
{
    // Paiement "declaratif" : le client indique qu'il a paye (avec une
    // reference si mobile money) et l'admin confirme manuellement depuis le
    // back-office. Seuls les moyens mobile money sont proposes (Wave, Orange
    // Money, Free Money) - pas de carte bancaire / Stripe dans ce projet.
    public function create(Request $request, Reservation $reservation)
    {
        abort_unless($reservation->client_id === $request->user()->id, 403);
        abort_unless(in_array($reservation->status, ['confirmee', 'en_cours']), 422, 'Cette reservation ne peut pas encore etre payee.');

        $existingPayment = $reservation->payments()->where('status', 'reussi')->exists();

        return Inertia::render('Reservations/Payment', [
            'reservation' => $reservation->load('vehicle'),
            'alreadyPaid' => $existingPayment,
            'pendingPayment' => $reservation->payments()->where('status', 'en_attente')->latest()->first(),
            'mobileMoneyNumbers' => [
                'wave' => config('andadoo.payment_numbers.wave'),
                'orange_money' => config('andadoo.payment_numbers.orange_money'),
                'free_money' => config('andadoo.payment_numbers.free_money'),
            ],
        ]);
    }

    public function store(Request $request, Reservation $reservation)
    {
        abort_unless($reservation->client_id === $request->user()->id, 403);

        $data = $request->validate([
            'method' => ['required', 'in:wave,orange_money,free_money'],
            'transaction_ref' => ['nullable', 'string', 'max:100'],
        ]);

        $reservation->payments()->create([
            'uuid' => (string) Str::uuid(),
            'amount' => $reservation->total_price,
            'method' => $data['method'],
            'status' => 'en_attente',
            'transaction_ref' => $data['transaction_ref'] ?? null,
        ]);

        return redirect()
            ->route('reservations.show', $reservation)
            ->with('success', 'Merci ! Votre paiement est en cours de vérification par notre équipe.');
    }
}
