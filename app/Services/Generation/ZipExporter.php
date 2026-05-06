<?php

namespace App\Services\Generation;

use Illuminate\Support\Facades\File;
use ZipArchive;

class ZipExporter
{
    /**
     * Cria um ZIP de uma pasta inteira.
     */
    public function export(string $sourcePath): string
    {
        $zipPath = $sourcePath . '.zip';

        // Se já existir zip antigo, apagar
        if (File::exists($zipPath)) {
            File::delete($zipPath);
        }

        //cria e abre o zip pra escrita
        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Não foi possível criar o ficheiro ZIP.");
        }

        //percore sobre todos os ficheiros da pasta

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        //adiciona cada ficheiro ao zip
        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath(); //caminho completo

                // caminho relativo dentro do zip
                $relativePath = substr($filePath, strlen($sourcePath) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        return $zipPath;
    }
}