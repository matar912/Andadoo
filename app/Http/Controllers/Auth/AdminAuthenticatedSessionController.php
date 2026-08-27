<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class AdminAuthenticatedSessionController extends Controller
{
    // Ecran de connexion propre a l'administration, jamais lie depuis le
    // site public. L'URL elle-meme (voir config/gocar.php) fait office de
    // premiere barriere avant meme d'arriver sur ce formulaire.
    public function create(): Response
    {
        return Inertia::render('Admin/Login', [
            'adminPath' => config('gocar.admin_path'),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required', 'string'],
        ]);

        $request->session()->regenerateToken();

        if (! Auth::attempt($credentials, $request->boolean('remember'))) {
            throw ValidationException::withMessages([
                'email' => 'Identifiants incorrects.',
            ]);
        }

        // Un compte non-admin ne doit jamais obtenir de session via ce
        // formulaire, meme avec un mot de passe valide.
        if (Auth::user()->role !== 'admin') {
            Auth::logout();
            $request->session()->invalidate();

            throw ValidationException::withMessages([
                'email' => "Ce compte n'a pas accès au portail d'administration.",
            ]);
        }

        $request->session()->regenerate();

        return redirect()->route('admin.dashboard');
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('admin.login');
    }
}
