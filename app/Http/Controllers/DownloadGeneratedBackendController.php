<?php

namespace App\Http\Controllers;

use App\Models\Generation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class DownloadGeneratedBackendController extends Controller
{
    /**
     * Faz download do ZIP gerado para uma geração,
     * apenas se pertencer ao utilizador autenticado.
     */
    public function __invoke(Request $request, Generation $generation): BinaryFileResponse
    {
        $generation->load('project');

        if (!$generation->project || $generation->project->user_id !== $request->user()->id) {
            abort(403, 'Não tens permissão para descarregar este ZIP.');
        }

        $zipPath = $generation->output_path;

        if (!$zipPath || !File::exists($zipPath)) {
            abort(404, 'ZIP não encontrado para esta geração.');
        }

        $startDownload = microtime(true);

        $generation->increment('download_count');

        if (file_exists($generation->output_path)) {
            $downloadMs = round((microtime(true) - $startDownload) * 1000);

            $generation->update([
                'avg_download_ms' => $generation->avg_download_ms
                    ? round(($generation->avg_download_ms + $downloadMs) / 2)
                    : $downloadMs,
            ]);
        }

        return response()->download($zipPath);
    }
}