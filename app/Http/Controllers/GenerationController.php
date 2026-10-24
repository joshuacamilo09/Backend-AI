<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class GenerationController extends Controller
{
    /**
     * Lista apenas as gerações pertencentes aos projectos do utilizador autenticado.
     */
    public function index(Request $request): JsonResponse
    {
        $generations = Generation::query()
            ->whereHas('project', function ($query) use ($request) {
                $query->where('user_id', $request->user()->id);
            })
            ->with('project')
            ->latest()
            ->get()
            ->map(function (Generation $generation) {
                return $this->transformGeneration($generation);
            });

        return response()->json([
            'data' => $generations,
        ]);
    }

    /**
     * Mostra detalhe de uma geração do próprio utilizador.
     */
    public function show(Request $request, Generation $generation): JsonResponse
    {
        $generation->load('project');

        if (!$generation->project || $generation->project->user_id !== $request->user()->id) {
            abort(403, 'Não tens permissão para aceder a esta geração.');
        }

        return response()->json([
            'data' => $this->transformGeneration($generation),
        ]);
    }

    /**
     * Normaliza a resposta e já devolve URL de download.
     */
    protected function transformGeneration(Generation $generation): array
    {
        return [
            'id' => $generation->id,
            'project_id' => $generation->project_id,
            'status' => $generation->status,
            'output_path' => $generation->output_path,
            'created_at' => $generation->created_at,
            'updated_at' => $generation->updated_at,
            'project' => $generation->project,
            'download_url' => $generation->output_path
                ? route('generations.download', $generation)
                : null,
        ];
    }
}
