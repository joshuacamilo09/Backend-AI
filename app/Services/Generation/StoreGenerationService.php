<?php

namespace App\Services\Generation;

use App\Models\Generation;
use App\Models\Project;
use App\Models\Specification;

class StoreGenerationService
{
    /**
     * Guarda projecto, specification e generation inicial.
     *
     * @param int $userId ID do utilizador autenticado
     * @param string $description Descrição original do utilizador
     * @param array $spec Specification devolvida pela IA
     * @return array Dados criados
     */
    public function store(int $userId, string $description, array $spec): array
    {
        // Nome do projecto:
        // se a IA já devolver project_name usamos esse,
        // senão usamos fallback.
        $projectName = $spec['project_name'] ?? 'generated-project';

        // Framework: para já sempre Laravel
        $framework = $spec['framework'] ?? 'laravel';

        // 1) Criar projecto
        $project = Project::create([
            'user_id' => $userId,
            'name' => $projectName,
            'framework' => $framework,
            'description' => $description,
        ]);

        // 2) Guardar specification JSON
        $specification = Specification::create([
            'project_id' => $project->id,
            'spec' => $spec,
        ]);

        // 3) Criar geração inicial
        $generation = Generation::create([
            'project_id' => $project->id,
            'status' => 'pending',
            'output_path' => null,
        ]);

        return [
            'project' => $project,
            'specification' => $specification,
            'generation' => $generation,
        ];
    }
}
