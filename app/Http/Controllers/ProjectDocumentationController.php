<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Services\Documentation\ProjectDocumentationGenerator;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use League\CommonMark\CommonMarkConverter;


class ProjectDocumentationController extends Controller
{
    /**
     * Mostra a documentação já gerada para um projeto.
     */
    public function show(Project $project)
    {
        $documentation = $project->documentation;

        if (!$documentation) {
            return response()->json([
                'message' => 'Documentation has not been generated yet.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'data' => $documentation,
        ]);
    }

    /**
     * Gera ou atualiza a documentação Markdown de um projeto.
     */
    public function generate(
        Project $project,
        ProjectDocumentationGenerator $generator
    ) {
        // Início da medição do tempo de geração da documentação.
        $startedAt = microtime(true);

        // Gera o conteúdo Markdown da documentação.
        $content = $generator->generate($project);

        // Calcula o tempo total em milissegundos.
        $durationMs = round((microtime(true) - $startedAt) * 1000);

        // Atualiza a documentação existente ou cria uma nova.
        $documentation = $project->documentation()->updateOrCreate(
            ['project_id' => $project->id],
            [
                'content' => $content,
                'format' => 'markdown',
                'duration_ms' => $durationMs,
            ]
        );

        return response()->json([
            'message' => 'Documentation generated successfully.',
            'data' => $documentation,
        ]);
    }

    /**
     * Faz download da documentação como PDF.
     */
    /**
     * Faz download da documentação como PDF bonito,
     * convertendo Markdown para HTML antes de gerar o PDF.
     */
    public function downloadPdf(Project $project)
    {
        $documentation = $project->documentation;

        if (!$documentation) {
            return response()->json([
                'message' => 'Documentation has not been generated yet.',
            ], 404);
        }

        // Converter Markdown para HTML
        $converter = new CommonMarkConverter([
            'html_input' => 'strip',
            'allow_unsafe_links' => false,
        ]);

        $htmlContent = $converter->convert($documentation->content)->getContent();

        $fileName = str($project->name)
            ->kebab()
            ->append('-documentation.pdf')
            ->toString();

        $pdf = Pdf::loadView('pdf.project-documentation', [
            'project' => $project,
            'documentation' => $documentation,
            'htmlContent' => $htmlContent,
        ])->setPaper('a4');

        $documentation->increment('download_count');

        return $pdf->download($fileName);
    }
}