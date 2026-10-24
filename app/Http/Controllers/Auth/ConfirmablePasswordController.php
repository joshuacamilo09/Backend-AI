<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ConfirmablePasswordController extends Controller
{
    /*
      Show the confirm password view.
    */
    public function show(): View
    {
        return view('auth.confirm-password');
    }

    /*
      Confirm the user's password. Recebe uma instancia do request como parametro.
    */
    public function store(Request $request): RedirectResponse
    {
        if (! Auth::guard('web')->validate([
            'email' => $request->user()->email,
            'password' => $request->password,
            //aqui estamos a verificar se a senha é valida com esse metoodo validate do auth facade.
        ])) {
            throw ValidationException::withMessages([
                'password' => __('auth.password'),
            ]);
        }
        //se nn der erro, atualiza a sessao do user com a data e hora de confirmacao e redreciona o user pra pagina de dashbord.
        $request->session()->put('auth.password_confirmed_at', time());

        return redirect()->intended(route('dashboard', absolute: false));
    }
}