<?php

use App\Http\Controllers\BackendGenerationController;
use App\Http\Controllers\DownloadGeneratedBackendController;
use App\Http\Controllers\GenerationController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ProjectController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EndpointTesterController;
use App\Http\Controllers\ProjectDocumentationController;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware(['auth', 'verified'])->group(function () {
    /**
     * Páginas da plataforma
     */
    Route::get('/documentation', function () {
        return view('documentation');
    })->name('documentation');

    Route::get('/api-tester', function () {
        return view('api-tester');
    })->name('api-tester');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    Route::get('/generate-backend', function () {
        return view('generate-backend');
    })->name('generate-backend');

    Route::get('/projects', function () {
        return view('projects');
    })->name('projects');

    Route::get('/templates', function () {
        return view('templates');
    })->name('templates');

    Route::get('/settings', function () {
        return view('settings');
    })->name('settings');

    /**
     * Perfil
     */
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    /**
     * Endpoints internos da plataforma (AJAX do frontend)
     * Estes usam sessão web do Breeze e evitam o erro "Unauthenticated"
     */
    Route::prefix('app-api')->group(function () {

        Route::get('/projects/{project}/documentation', [ProjectDocumentationController::class, 'show'])
            ->name('app.projects.documentation.show');

        Route::post('/projects/{project}/documentation/generate', [ProjectDocumentationController::class, 'generate'])
            ->name('app.projects.documentation.generate');

        Route::get('/projects/{project}/documentation/download-pdf', [ProjectDocumentationController::class, 'downloadPdf'])
            ->name('app.projects.documentation.download-pdf');

        Route::get('/projects/{project}/endpoints', [EndpointTesterController::class, 'endpoints'])
            ->name('app.projects.endpoints');

        Route::post('/endpoint-tests/run', [EndpointTesterController::class, 'run'])
            ->name('app.endpoint-tests.run');

        Route::post('/generate-backend', [BackendGenerationController::class, 'store'])
            ->name('app.generate-backend');

        Route::get('/projects', [ProjectController::class, 'index'])
            ->name('app.projects.index');

        Route::get('/projects/{project}', [ProjectController::class, 'show'])
            ->name('app.projects.show');

        Route::get('/generations', [GenerationController::class, 'index'])
            ->name('app.generations.index');

        Route::get('/generations/{generation}', [GenerationController::class, 'show'])
            ->name('app.generations.show');

        Route::get('/generations/{generation}/download', DownloadGeneratedBackendController::class)
            ->name('generations.download');
    });
});

require __DIR__ . '/auth.php';