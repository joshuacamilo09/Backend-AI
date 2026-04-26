<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /*
       Display the login view.
    */
    public function create(): View
    {
        return view('auth.login');
    }

    /*
      Handle an incoming authentication request.
    */
    public function store(LoginRequest $request): RedirectResponse
    {
        $request->authenticate();

        $request->session()->regenerate();

        // Atualiza métricas de atividade para analytics.
        // Isto permite calcular utilizadores novos vs utilizadores recorrentes.
        $request->user()->update([
            'last_login_at' => now(),
            'last_activity_at' => now(),
            'login_count' => $request->user()->login_count + 1,
        ]);

        return redirect()->intended(route('dashboard', absolute: false));
    }

    /*
      Destroy an authenticated session.
    */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}