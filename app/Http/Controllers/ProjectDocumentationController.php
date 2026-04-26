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
        // Gerar conteúdo Markdown com base nos dados atuais do projeto.
        $content = $generator->generate($project);

        /**
         * updateOrCreate evita duplicar documentação.
         * Se já existir, atualiza.
         * Se não existir, cria.
         */
        $documentation = $project->documentation()->updateOrCreate(
            ['project_id' => $project->id],
            [
                'content' => $content,
                'format' => 'markdown',
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

    return $pdf->download($fileName);
}
}
