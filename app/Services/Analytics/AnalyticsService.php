<?php

namespace App\Services\Analytics;

use App\Models\Generation;
use App\Models\Project;
use App\Models\ProjectDocumentation;
use App\Models\ProjectEndpoint;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class AnalyticsService
{
    /**
     * Analytics resumidos para utilizadores normais.
     *
     * O user normal não vê dados sensíveis individuais.
     * Vê apenas médias globais da plataforma.
     */
    public function userSummary(): array
    {
        $totalUsers = User::count() ?: 1;
        $totalProjects = Project::count();
        $totalGenerations = Generation::count();
        $totalEndpoints = ProjectEndpoint::count();
        $totalDocumentations = ProjectDocumentation::count();

        $completedGenerations = Generation::where('status', 'completed')->count();
        $failedGenerations = Generation::where('status', 'failed')->count();

        return [
            'averages' => [
                'projects_per_user' => round($totalProjects / $totalUsers, 2),
                'generations_per_user' => round($totalGenerations / $totalUsers, 2),
                'endpoints_per_project' => round($totalEndpoints / max($totalProjects, 1), 2),
                'documentations_per_project' => round($totalDocumentations / max($totalProjects, 1), 2),
            ],

            'rates' => [
                'success_rate' => $this->percentage($completedGenerations, $totalGenerations),
                'error_rate' => $this->percentage($failedGenerations, $totalGenerations),
                'documentation_generation_rate' => $this->percentage($totalDocumentations, $totalProjects),
            ],

            'popular' => [
                'frameworks' => $this->frameworkUsage(),
                'templates' => $this->templateUsage(),
            ],

            'activity' => [
                'peak_hour' => $this->peakActivityHour(),
                'generations_by_day' => $this->generationsByDay(),
            ],
        ];
    }

    /**
     * Analytics completos para administradores.
     *
     * O admin vê estatísticas globais, dados geográficos
     * e métricas mais detalhadas da plataforma.
     */
    public function adminAnalytics(): array
    {
        $totalUsers = User::count();
        $totalProjects = Project::count();
        $totalGenerations = Generation::count();
        $totalEndpoints = ProjectEndpoint::count();
        $totalDocumentations = ProjectDocumentation::count();

        $completedGenerations = Generation::where('status', 'completed')->count();
        $failedGenerations = Generation::where('status', 'failed')->count();

        $totalDownloads = Generation::sum('download_count');
        $totalDocumentationDownloads = ProjectDocumentation::sum('download_count');

        $averageGenerationTime = round(Generation::whereNotNull('duration_ms')->avg('duration_ms') ?? 0, 2);
        $averageDownloadTime = round(Generation::whereNotNull('avg_download_ms')->avg('avg_download_ms') ?? 0, 2);
        $averageProjectSize = round(Generation::whereNotNull('zip_size_bytes')->avg('zip_size_bytes') ?? 0, 2);

        $totalApiTests = 0;
        if (SchemaHelper::hasColumn('api_test_runs', 'id')) {
            $totalApiTests = DB::table('api_test_runs')->count();
        }

        $newUsers = \App\Models\User::where('login_count', '<=', 1)->count();
        $returningUsers = \App\Models\User::where('login_count', '>', 1)->count();

        return [
            'users' => [
                'new_users' => $newUsers,
                'returning_users' => $returningUsers,
            ],

            'totals' => [
                'users' => $totalUsers,
                'projects' => $totalProjects,
                'generations' => $totalGenerations,
                'endpoints' => $totalEndpoints,
                'documentations' => $totalDocumentations,
            ],

            'rates' => [
                'success_rate' => $this->percentage($completedGenerations, $totalGenerations),
                'error_rate' => $this->percentage($failedGenerations, $totalGenerations),
                'documentation_generation_rate' => $this->percentage($totalDocumentations, $totalProjects),
            ],

            'averages' => [
                'projects_per_user' => round($totalProjects / max($totalUsers, 1), 2),
                'generations_per_user' => round($totalGenerations / max($totalUsers, 1), 2),
                'endpoints_per_project' => round($totalEndpoints / max($totalProjects, 1), 2),
            ],

            'geo' => [
                'countries' => $this->usersByCountry(),
                'cities' => $this->usersByCity(),
                'continents' => $this->usersByContinent(),
                'heatmap_points' => $this->heatmapPoints(),
            ],

            'popular' => [
                'frameworks' => $this->frameworkUsage(),
                'templates' => $this->templateUsage(),
            ],

            'activity' => [
                'peak_hour' => $this->peakActivityHour(),
                'generations_by_day' => $this->generationsByDay(),
                'users_by_day' => $this->usersByDay(),
            ],

            'retention' => [
                'inactive_users_14_days' => $this->inactiveUsersAfterTwoWeeks(),
                'abandonment_rate' => $this->abandonmentRate(),
            ],

            'downloads' => [
                'total_zip_downloads' => (int) $totalDownloads,
                'download_rate' => $this->percentage((int) $totalDownloads, max($totalGenerations, 1)),
                'documentation_downloads' => (int) $totalDocumentationDownloads,
                'documentation_download_rate' => $this->percentage((int) $totalDocumentationDownloads, max($totalDocumentations, 1)),
            ],

            'performance' => [
                'average_generation_time_ms' => $averageGenerationTime,
                'average_download_time_ms' => $averageDownloadTime,
                'average_project_size_bytes' => $averageProjectSize,
                'average_documentation_time_ms' => round(
                    \App\Models\ProjectDocumentation::whereNotNull('duration_ms')->avg('duration_ms') ?? 0,
                    2
                ),
            ],

            'testing' => [
                'total_api_tests' => $totalApiTests,
                'api_test_rate' => $this->percentage($totalApiTests, max($totalEndpoints, 1)),
            ],
        ];
    }

    /**
     * Calcula percentagem com proteção contra divisão por zero.
     */
    protected function percentage(int $value, int $total): float
    {
        if ($total === 0) {
            return 0;
        }

        return round(($value / $total) * 100, 2);
    }

    /**
     * Frameworks mais usadas nos projetos gerados.
     */
    protected function frameworkUsage(): array
    {
        return Project::query()
            ->select('framework', DB::raw('COUNT(*) as total'))
            ->groupBy('framework')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'label' => $item->framework ?? 'unknown',
                'total' => (int) $item->total,
            ])
            ->toArray();
    }

    /**
     * Templates mais usados.
     *
     * Para já tenta usar a coluna template_key se existir.
     * Se ainda não existir na tabela projects, devolve vazio.
     */
    protected function templateUsage(): array
    {
        if (!SchemaHelper::hasColumn('projects', 'template_key')) {
            return [];
        }

        return Project::query()
            ->select('template_key', DB::raw('COUNT(*) as total'))
            ->whereNotNull('template_key')
            ->groupBy('template_key')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'label' => $item->template_key,
                'total' => (int) $item->total,
            ])
            ->toArray();
    }

    /**
     * Hora com maior atividade de gerações.
     */
    protected function peakActivityHour(): ?array
    {
        $row = Generation::query()
            ->select(DB::raw('EXTRACT(HOUR FROM created_at) as hour'), DB::raw('COUNT(*) as total'))
            ->groupBy('hour')
            ->orderByDesc('total')
            ->first();

        if (!$row) {
            return null;
        }

        return [
            'hour' => (int) $row->hour,
            'total' => (int) $row->total,
        ];
    }

    /**
     * Gerações por dia para gráfico de linha.
     */
    protected function generationsByDay(): array
    {
        return Generation::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->limit(30)
            ->get()
            ->map(fn($item) => [
                'date' => (string) $item->date,
                'total' => (int) $item->total,
            ])
            ->toArray();
    }

    /**
     * Novos utilizadores por dia.
     */
    protected function usersByDay(): array
    {
        return User::query()
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as total'))
            ->groupBy('date')
            ->orderBy('date')
            ->limit(30)
            ->get()
            ->map(fn($item) => [
                'date' => (string) $item->date,
                'total' => (int) $item->total,
            ])
            ->toArray();
    }

    /**
     * Utilizadores por país.
     */
    protected function usersByCountry(): array
    {
        return User::query()
            ->select('country', DB::raw('COUNT(*) as total'))
            ->whereNotNull('country')
            ->groupBy('country')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'label' => $item->country,
                'total' => (int) $item->total,
            ])
            ->toArray();
    }

    /**
     * Utilizadores por cidade.
     */
    protected function usersByCity(): array
    {
        return User::query()
            ->select('city', DB::raw('COUNT(*) as total'))
            ->whereNotNull('city')
            ->groupBy('city')
            ->orderByDesc('total')
            ->limit(10)
            ->get()
            ->map(fn($item) => [
                'label' => $item->city,
                'total' => (int) $item->total,
            ])
            ->toArray();
    }

    /**
     * Crescimento/uso por continente.
     */
    protected function usersByContinent(): array
    {
        return User::query()
            ->select('continent', DB::raw('COUNT(*) as total'))
            ->whereNotNull('continent')
            ->groupBy('continent')
            ->orderByDesc('total')
            ->get()
            ->map(fn($item) => [
                'label' => $item->continent,
                'total' => (int) $item->total,
            ])
            ->toArray();
    }

    /**
     * Pontos geográficos para heatmap/mapa.
     */
    protected function heatmapPoints(): array
    {
        return User::query()
            ->whereNotNull('latitude')
            ->whereNotNull('longitude')
            ->get(['latitude', 'longitude', 'city', 'country'])
            ->map(fn($user) => [
                'lat' => (float) $user->latitude,
                'lng' => (float) $user->longitude,
                'city' => $user->city,
                'country' => $user->country,
            ])
            ->toArray();
    }

    /**
     * Utilizadores sem atividade nos últimos 14 dias.
     */
    protected function inactiveUsersAfterTwoWeeks(): int
    {
        return User::query()
            ->where('updated_at', '<', now()->subDays(14))
            ->count();
    }

    /**
     * Taxa de abandono:
     * utilizadores sem atividade há mais de 14 dias / total de utilizadores.
     */
    protected function abandonmentRate(): float
    {
        $totalUsers = User::count();

        return $this->percentage(
            $this->inactiveUsersAfterTwoWeeks(),
            $totalUsers
        );
    }
}