<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProjectController extends Controller
{
    /**
     * Lista apenas os projectos do utilizador autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $projects = Project::query()
            ->where('user_id', $request->user()->id)
            ->with(['specification', 'generations'])
            ->latest()
            ->get();

        return response()->json([
            'data' => $projects,
        ]);
    }

    /**
     * Mostra detalhe de um projecto do próprio utilizador.
     */
    public function show(Request $request, Project $project): JsonResponse
    {
        if ($project->user_id !== $request->user()->id) {
            abort(403, 'Não tens permissão para aceder a este projecto.');
        }

        $project->load(['specification', 'generations']);

        return response()->json([
            'data' => $project,
        ]);
    }
}
