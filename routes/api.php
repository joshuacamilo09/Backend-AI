<?php

use App\Services\AI\AIInterpreter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/**
 * Rota de debug:
 * serve para testar apenas o parser da IA
 * sem guardar nada na base de dados.
 */
Route::post('/ai/parse', function (Request $request, AIInterpreter $interpreter) {
    $request->validate([
        'description' => ['required', 'string', 'min:10'],
    ]);

    $spec = $interpreter->parse($request->input('description'));

    return response()->json($spec);
});
