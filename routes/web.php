<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
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
    abort_unless(Storage::disk('public')->exists($path), 404);

    return Storage::disk('public')->response($path);
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
Route::prefix(config('andadoo.admin_path'))->name('admin.')->group(function () {

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
    });
});
