<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Inertia\Response;

class AuthenticatedSessionController extends Controller
{
    public function create(): Response
    {
        return Inertia::render('Auth/Login', [
            'status' => session('status'),
        ]);
    }

    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Ce formulaire est celui du site public : un compte admin ne doit pas
        // pouvoir s'en servir pour ouvrir une session (il a son propre login).
        if (Auth::user()->role === 'admin') {
            Auth::logout();
            $request->session()->invalidate();

            return back()->withErrors([
                'email' => "Ce compte utilise le portail d'administration, pas cette page.",
            ]);
        }

        return redirect()->intended(route('vehicles.index', absolute: false));
    }

    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }
}
