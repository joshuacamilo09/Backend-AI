<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

//gere a sessão autenticada de cada utilizador
class AuthenticatedSessionController extends Controller
{
    /*
       Display the login view e retorna uma view blade,(uma extensão de html que tem um layout de autenticação)
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

        $request->session()->regenerate(); //depois do login, regenero o id da sessão pra impedir que alguém reutilize uma sessão antiga ou pré-definida antes da autenticação.

        // Atualiza métricas de atividade para analytics.
        // Isto permite calcular utilizadores novos vs utilizadores recorrentes.
        $request->user()->update([
            'last_login_at' => now(),
            'last_activity_at' => now(),
            'login_count' => $request->user()->login_count + 1,
        ]);

        //redirecciona o utilizador para a página que ele quer aceder antes de ser mandado pro login. esse absolute gera uma url relativa ao inves da url absoluta.
        return redirect()->intended(route('dashboard', absolute: false));
    }

    /*
      Destroy an authenticated session.
    */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate(); //remove/invalida a sessão atual.

        $request->session()->regenerateToken(); //gera um novo token csrf pra evitar reutilização.

        return redirect('/');
    }
}
