<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\ReservationController as AdminReservationController;
use App\Http\Controllers\Admin\VehicleController as AdminVehicleController;
use App\Http\Controllers\Auth\AdminAuthenticatedSessionController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ReservationController;
use App\Http\Controllers\VehicleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

/*
|--------------------------------------------------------------------------
| Public
|--------------------------------------------------------------------------
| Seule la page d'accueil est accessible sans compte : c'est la vitrine qui
| pousse le visiteur vers /login ou /register. Tout le reste de
| l'application (catalogue, reservation) exige une authentification client.
*/
Route::get('/', function () {
    return Inertia::render('Welcome');
})->name('home');

/*
|--------------------------------------------------------------------------
| Photos de vehicules
|--------------------------------------------------------------------------
| Sert les fichiers directement depuis le disque "public" via Laravel, sans
| dependre du lien symbolique de `storage:link` (peu fiable sous Windows sans
| droits administrateur). Fonctionne partout, sans configuration serveur.
*/
Route::get('/vehicule-photo/{path}', function (string $path) {
    abort_unless(\Illuminate\Support\Facades\Storage::disk('public')->exists($path), 404);

    return \Illuminate\Support\Facades\Storage::disk('public')->response($path);
})->where('path', '.*')->name('vehicles.photo');

/*
|--------------------------------------------------------------------------
| Espace client (authentification obligatoire)
|--------------------------------------------------------------------------
| Les routes /login, /register, /forgot-password, etc. sont fournies par
| Laravel Breeze (routes/auth.php, charge plus bas) et creent des comptes
| avec role = "client" par defaut (voir migration + RegisteredUserController).
*/
Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/vehicules', [VehicleController::class, 'index'])->name('vehicles.index');
    Route::get('/vehicules/{vehicle}', [VehicleController::class, 'show'])->name('vehicles.show');

    Route::get('/reservations/nouvelle', [ReservationController::class, 'create'])->name('reservations.create');
    Route::post('/reservations', [ReservationController::class, 'store'])->name('reservations.store');
    Route::get('/mes-reservations', [ReservationController::class, 'index'])->name('reservations.index');
    Route::get('/reservations/{reservation}', [ReservationController::class, 'show'])->name('reservations.show');

    // Icone de parametres du header -> fiche du client connecte.
    Route::get('/profil', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');
});

require __DIR__.'/auth.php'; // Breeze : /login /register /forgot-password /reset-password /verify-email /confirm-password /logout

/*
|--------------------------------------------------------------------------
| Portail administrateur (lien non reference + authentification dediee)
|--------------------------------------------------------------------------
| L'URL vient de config('gocar.admin_path') (voir .env: ADMIN_PANEL_PATH),
| jamais liee depuis le site public. Une fois sur le formulaire, l'admin
| passe par SA PROPRE authentification (AdminAuthenticatedSessionController),
| distincte du login client, qui refuse toute session pour role != 'admin'.
*/
Route::prefix(config('gocar.admin_path'))->name('admin.')->group(function () {
    Route::middleware('guest')->group(function () {
        Route::get('/login', [AdminAuthenticatedSessionController::class, 'create'])->name('login');
        Route::post('/login', [AdminAuthenticatedSessionController::class, 'store']);
    });

    Route::middleware(['auth', 'role:admin'])->group(function () {
        Route::post('/logout', [AdminAuthenticatedSessionController::class, 'destroy'])->name('logout');
        Route::get('/', [DashboardController::class, 'index'])->name('dashboard');

        // Seul l'admin decide de la flotte visible et de sa disponibilite.
        Route::get('/vehicules', [AdminVehicleController::class, 'index'])->name('vehicles.index');
        Route::get('/vehicules/nouveau', [AdminVehicleController::class, 'create'])->name('vehicles.create');
        Route::post('/vehicules', [AdminVehicleController::class, 'store'])->name('vehicles.store');
        Route::get('/vehicules/{vehicle}/modifier', [AdminVehicleController::class, 'edit'])->name('vehicles.edit');
        Route::put('/vehicules/{vehicle}', [AdminVehicleController::class, 'update'])->name('vehicles.update');
        Route::post('/vehicules/{vehicle}', [AdminVehicleController::class, 'update']); // upload de photo : POST direct, pas de spoofing PUT
        Route::delete('/vehicules/{vehicle}', [AdminVehicleController::class, 'destroy'])->name('vehicles.destroy');

        // Seul l'admin confirme ou refuse une demande de reservation client.
        Route::get('/reservations', [AdminReservationController::class, 'index'])->name('reservations.index');
        Route::patch('/reservations/{reservation}/valider', [AdminReservationController::class, 'validate_'])->name('reservations.validate');
        Route::patch('/reservations/{reservation}/refuser', [AdminReservationController::class, 'refuse'])->name('reservations.refuse');
    });
});
