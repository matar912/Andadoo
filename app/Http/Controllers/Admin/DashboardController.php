<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\Vehicle;
use Inertia\Inertia;

class DashboardController extends Controller
{
    // Vue de pilotage interne : flotte propre + reservations, sans notion
    // de "partenaire proprietaire" puisque tous les vehicules sont a GO'CAR.
    public function index()
    {
        return Inertia::render('Admin/Dashboard', [
            'stats' => [
                'vehicles_total' => Vehicle::count(),
                'vehicles_available' => Vehicle::available()->count(),
                'reservations_pending' => Reservation::where('status', 'en_attente')->count(),
                'reservations_active' => Reservation::where('status', 'en_cours')->count(),
                'revenue_month' => Reservation::whereMonth('created_at', now()->month)->sum('total_price'),
            ],
            'recent_reservations' => Reservation::with(['client', 'vehicle'])
                ->latest()
                ->take(8)
                ->get(),
        ]);
    }
}
