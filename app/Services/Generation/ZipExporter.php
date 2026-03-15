<?php

namespace App\Services\Generation;

use Illuminate\Support\Facades\File;
use ZipArchive;

class ZipExporter
{
    /**
     * Cria um ZIP de uma pasta inteira.
     *
     * @param string $sourcePath Caminho da pasta a comprimir
     * @return string Caminho absoluto do ficheiro ZIP criado
     */
    public function export(string $sourcePath): string
    {
        $zipPath = $sourcePath . '.zip';

        // Se já existir zip antigo, apagar
        if (File::exists($zipPath)) {
            File::delete($zipPath);
        }

        $zip = new ZipArchive();

        if ($zip->open($zipPath, ZipArchive::CREATE | ZipArchive::OVERWRITE) !== true) {
            throw new \RuntimeException("Não foi possível criar o ficheiro ZIP.");
        }

        $files = new \RecursiveIteratorIterator(
            new \RecursiveDirectoryIterator($sourcePath, \RecursiveDirectoryIterator::SKIP_DOTS),
            \RecursiveIteratorIterator::LEAVES_ONLY
        );

        foreach ($files as $file) {
            if (!$file->isDir()) {
                $filePath = $file->getRealPath();

                // caminho relativo dentro do zip
                $relativePath = substr($filePath, strlen($sourcePath) + 1);

                $zip->addFile($filePath, $relativePath);
            }
        }

        $zip->close();

        return $zipPath;
    }
}
