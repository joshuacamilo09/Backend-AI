<?php

namespace App\Http\Controllers;

use App\Services\AI\AIInterpreter;
use App\Services\Generation\LaravelProjectGenerator;
use App\Services\Generation\StoreGenerationService;
use App\Services\Generation\ZipExporter;
use Illuminate\Http\Request;

class BackendGenerationController extends Controller
{
    /**
     * Recebe a descrição do utilizador,
     * pede ao Gemini uma specification,
     * guarda os dados na base de dados,
     * gera um projeto Laravel real,
     * cria um ZIP
     * e devolve os metadados da geração.
     */
    public function store(
        Request $request,
        AIInterpreter $interpreter,
        StoreGenerationService $storeService,
        LaravelProjectGenerator $projectGenerator,
        ZipExporter $zipExporter
    ) {
        if (!$request->user()) {
            return response()->json([
                'message' => 'Não autenticado.',
            ], 401);
        }

        $validated = $request->validate([
            'description' => ['required', 'string', 'min:10'],
        ]);

        $description = $validated['description'];

        try {
            // 1) Interpretar descrição com IA
            $spec = $interpreter->parse($description);

            // 2) Guardar projeto/specification/generation na base de dados
            $result = $storeService->store($request->user()->id, $description, $spec);

            $project = $result['project'];
            $generation = $result['generation'];

            // 3) Gerar projeto Laravel real
            $projectPath = $projectGenerator->generate($spec, $generation);

            // 4) Criar ZIP
            $zipPath = $zipExporter->export($projectPath);

            // 5) Atualizar geração como concluída
            $generation->update([
                'status' => 'completed',
                'output_path' => $zipPath,
            ]);

            $generation = $generation->fresh();

            return response()->json([
                'message' => 'Backend gerado com sucesso.',
                'project' => $project,
                'generation' => [
                    'id' => $generation->id,
                    'project_id' => $generation->project_id,
                    'status' => $generation->status,
                    'output_path' => $generation->output_path,
                    'created_at' => $generation->created_at,
                    'updated_at' => $generation->updated_at,
                    'download_url' => route('generations.download', $generation),
                ],
                'project_path' => $projectPath,
                'zip_path' => $zipPath,
            ], 201);

        } catch (\Throwable $e) {
            return response()->json([
                'message' => 'Erro ao gerar backend.',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
