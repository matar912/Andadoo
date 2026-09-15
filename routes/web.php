<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\OptionController as AdminOptionController;
use App\Http\Controllers\Admin\PartnerController as AdminPartnerController;
use App\Http\Controllers\Admin\PaymentController as AdminPaymentController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public & Fichiers
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

Route::get('/vehicule-photo/{path}', function (string $path) {
    $disk = Storage::disk('public');

    abort_unless($disk->exists($path), 404);

    return $disk->response($path);
})->where('path', '.*')->name('vehicles.photo');

/*
|--------------------------------------------------------------------------
| Espace Client (Authentification obligatoire)
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/vehicules', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicules/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

    Route::get('/reservations/nouvelle', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/mes-reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');
    Route::get('/reservations/{reservation}/paiement', [PaymentController::class, 'create'])->name('payments.create');
    Route::post('/reservations/{reservation}/paiement', [PaymentController::class, 'store'])->name('payments.store');

    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__.'/auth.php'; // Routes Breeze (login, register, logout client...)

/*
|--------------------------------------------------------------------------
| Portail Administrateur
|--------------------------------------------------------------------------
*/
Route::prefix('andadoo-admin')->name('admin.')->group(function () {

    // Auth Admin
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthenticatedSessionController::class, 'store']);
    });

    // Espace Administration Protégé
    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Flotte & Véhicules
        Route::get('/flotte', [AdminVehicleController::class, 'index']); // Alias de sécurité si appel /flotte
        Route::get('/vehicules', [AdminVehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/vehicules/nouveau', [AdminVehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicules', [AdminVehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehicules/{vehicle}/modifier', [AdminVehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehicules/{vehicle}', [AdminVehicleController::class, 'update'])->name('vehicles.update');
        Route::post('/vehicules/{vehicle}', [AdminVehicleController::class, 'update']); // Requis pour l'envoi de fichier photo avec FormData
        Route::delete('/vehicules/{vehicle}', [AdminVehicleController::class, 'destroy'])->name('vehicles.destroy');

        // Réservations Admin
        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::patch('/reservations/{reservation}/valider', [AdminReservationController::class, 'validate_'])->name('reservations.validate');
        Route::patch('/reservations/{reservation}/refuser', [AdminReservationController::class, 'refuse'])->name('reservations.refuse');

        // Options de réservation
        Route::get('/options', [AdminOptionController::class, 'index'])->name('options.index');
        Route::post('/options', [AdminOptionController::class, 'store'])->name('options.store');
        Route::put('/options/{option}', [AdminOptionController::class, 'update'])->name('options.update');
        Route::delete('/options/{option}', [AdminOptionController::class, 'destroy'])->name('options.destroy');

        // Partenaires commerciaux
        Route::get('/partenaires', [AdminPartnerController::class, 'index'])->name('partners.index');
        Route::post('/partenaires', [AdminPartnerController::class, 'store'])->name('partners.store');
        Route::put('/partenaires/{partner}', [AdminPartnerController::class, 'update'])->name('partners.update');
        Route::delete('/partenaires/{partner}', [AdminPartnerController::class, 'destroy'])->name('partners.destroy');

        // Vérification manuelle des paiements
        Route::get('/paiements', [AdminPaymentController::class, 'index'])->name('payments.index');
        Route::patch('/paiements/{payment}/confirmer', [AdminPaymentController::class, 'confirm'])->name('payments.confirm');
        Route::patch('/paiements/{payment}/rejeter', [AdminPaymentController::class, 'reject'])->name('payments.reject');
    });
});
