<?php

namespace Database\Seeders;

use App\Models\Generation;
use App\Models\Project;
use App\Models\ProjectDocumentation;
use App\Models\ProjectEndpoint;
use App\Models\Specification;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class FinalDemoDataSeeder extends Seeder
{
    public function run(): void
    {
        $admin = User::updateOrCreate(
            ['email' => 'admin@backendai.test'],
            [
                'name' => 'BackendAI Admin',
                'password' => Hash::make('password'),
                'role' => 'admin',
                'country' => 'Portugal',
                'city' => 'Lisboa',
                'continent' => 'Europe',
                'latitude' => 38.7223,
                'longitude' => -9.1393,
                'user_type' => 'teacher',
                'experience_level' => 'advanced',
                'main_interest' => 'ai',
                'last_login_at' => now(),
                'last_activity_at' => now(),
                'login_count' => 15,
            ]
        );

        $usersData = [
            ['Ana Silva', 'ana@test.com', 'Portugal', 'Porto', 'Europe', 41.1579, -8.6291, 'student', 'beginner', 'backend', 2],
            ['Miguel Rocha', 'miguel@test.com', 'Portugal', 'Lisboa', 'Europe', 38.7223, -9.1393, 'freelancer', 'intermediate', 'fullstack', 7],
            ['Sofia Martins', 'sofia@test.com', 'Portugal', 'Braga', 'Europe', 41.5454, -8.4265, 'junior_developer', 'intermediate', 'databases', 4],
            ['Carlos Mendes', 'carlos@test.com', 'Brazil', 'São Paulo', 'South America', -23.5505, -46.6333, 'freelancer', 'advanced', 'backend', 9],
            ['Pedro Lima', 'pedro@test.com', 'Brazil', 'Rio de Janeiro', 'South America', -22.9068, -43.1729, 'student', 'beginner', 'ai', 1],
            ['Maria Costa', 'maria@test.com', 'Angola', 'Luanda', 'Africa', -8.8390, 13.2894, 'student', 'beginner', 'frontend', 3],
            ['Nadia Fernandes', 'nadia@test.com', 'Mozambique', 'Maputo', 'Africa', -25.9692, 32.5732, 'teacher', 'advanced', 'backend', 8],
            ['John Smith', 'john@test.com', 'United States', 'New York', 'North America', 40.7128, -74.0060, 'company', 'advanced', 'devops', 11],
            ['Emily Brown', 'emily@test.com', 'United Kingdom', 'London', 'Europe', 51.5072, -0.1276, 'company', 'advanced', 'fullstack', 6],
            ['Yuki Tanaka', 'yuki@test.com', 'Japan', 'Tokyo', 'Asia', 35.6762, 139.6503, 'junior_developer', 'intermediate', 'ai', 5],
            ['Luca Rossi', 'luca@test.com', 'Italy', 'Rome', 'Europe', 41.9028, 12.4964, 'student', 'intermediate', 'backend', 2],
            ['Amina Diallo', 'amina@test.com', 'Senegal', 'Dakar', 'Africa', 14.7167, -17.4677, 'freelancer', 'intermediate', 'fullstack', 4],
            ['Chen Wei', 'chen@test.com', 'China', 'Shanghai', 'Asia', 31.2304, 121.4737, 'company', 'advanced', 'databases', 10],
            ['Lucas Martin', 'lucas@test.com', 'France', 'Paris', 'Europe', 48.8566, 2.3522, 'junior_developer', 'beginner', 'frontend', 1],
            ['Diego Perez', 'diego@test.com', 'Spain', 'Madrid', 'Europe', 40.4168, -3.7038, 'student', 'intermediate', 'devops', 3],
        ];

        $users = collect([$admin]);

        foreach ($usersData as $data) {
            $users->push(User::updateOrCreate(
                ['email' => $data[1]],
                [
                    'name' => $data[0],
                    'password' => Hash::make('password'),
                    'role' => 'user',
                    'country' => $data[2],
                    'city' => $data[3],
                    'continent' => $data[4],
                    'latitude' => $data[5],
                    'longitude' => $data[6],
                    'user_type' => $data[7],
                    'experience_level' => $data[8],
                    'main_interest' => $data[9],
                    'last_login_at' => now()->subDays(rand(0, 25)),
                    'last_activity_at' => now()->subDays(rand(0, 25)),
                    'login_count' => $data[10],
                ]
            ));
        }

        $templates = [
            ['Library Management API', 'library', 'Sistema de gestão de livraria com livros, autores, categorias e empréstimos.', ['Book', 'Author', 'Category', 'Loan']],
            ['Task Manager API', 'task_manager', 'Gestão de projetos, tarefas, comentários e estados.', ['ProjectTask', 'Task', 'Comment', 'Status']],
            ['E-commerce API', 'ecommerce', 'Loja online com produtos, carrinho, encomendas e pagamentos.', ['Product', 'Cart', 'Order', 'Payment']],
            ['Blog Platform API', 'blog', 'Plataforma de blog com posts, categorias e comentários.', ['Post', 'Category', 'Comment']],
            ['Booking System API', 'booking', 'Sistema de reservas com clientes, serviços e marcações.', ['Customer', 'Service', 'Booking']],
            ['School Management API', 'school', 'Gestão escolar com alunos, turmas, disciplinas e notas.', ['Student', 'Classroom', 'Subject', 'Grade']],
            ['Fitness Tracker API', 'fitness', 'API para treinos, exercícios e progresso físico.', ['Workout', 'Exercise', 'Progress']],
            ['Restaurant Orders API', 'restaurant', 'Sistema de pedidos para restaurante com mesas, menus e encomendas.', ['MenuItem', 'Table', 'Order']],
            ['CRM Starter API', 'crm', 'CRM simples com contactos, empresas e oportunidades.', ['Contact', 'Company', 'Deal']],
            ['Chat Application API', 'chat', 'Aplicação de chat com canais, mensagens e utilizadores.', ['Channel', 'Message']],
            ['Finance Tracker API', 'finance', 'Gestão financeira com despesas, receitas e categorias.', ['Expense', 'Income', 'Budget']],
            ['Healthcare Appointments API', 'healthcare', 'Sistema de marcações médicas com pacientes, médicos e consultas.', ['Patient', 'Doctor', 'Appointment']],
        ];

        foreach ($users as $user) {
            $projectsForUser = rand(2, 5);

            for ($i = 0; $i < $projectsForUser; $i++) {
                $template = $templates[array_rand($templates)];
                [$name, $templateKey, $description, $entities] = $template;

                $projectName = $name . ' - ' . $user->name . ' #' . ($i + 1);

                $projectData = [
                    'name' => $projectName,
                    'framework' => 'laravel',
                    'description' => $description,
                    'created_at' => now()->subDays(rand(1, 60)),
                    'updated_at' => now()->subDays(rand(0, 20)),
                ];

                if (Schema::hasColumn('projects', 'template_key')) {
                    $projectData['template_key'] = $templateKey;
                }

                if (Schema::hasColumn('projects', 'user_id')) {
                    $projectData['user_id'] = $user->id;
                }

                $project = Project::updateOrCreate(
                    ['name' => $projectName],
                    $projectData
                );

                $spec = $this->buildSpec($projectName, $entities);

                Specification::updateOrCreate(
                    ['project_id' => $project->id],
                    ['spec' => $spec]
                );

                ProjectEndpoint::where('project_id', $project->id)->delete();

                foreach ($entities as $entity) {
                    $this->createCrudEndpoints($project, $entity);
                }

                $generationsCount = rand(1, 3);

                for ($g = 0; $g < $generationsCount; $g++) {
                    Generation::create([
                        'project_id' => $project->id,
                        'status' => rand(1, 100) <= 82 ? 'completed' : 'failed',
                        'output_path' => "/fake/generated/{$templateKey}-{$project->id}-{$g}.zip",
                        'duration_ms' => rand(1500, 18000),
                        'zip_size_bytes' => rand(650000, 16000000),
                        'download_count' => rand(0, 15),
                        'avg_download_ms' => rand(70, 1200),
                        'created_at' => now()->subDays(rand(0, 60)),
                        'updated_at' => now()->subDays(rand(0, 30)),
                    ]);
                }

                if (rand(1, 100) <= 65) {
                    ProjectDocumentation::updateOrCreate(
                        ['project_id' => $project->id],
                        [
                            'content' => "# {$project->name}\n\nDocumentação automática de teste.\n\n## Setup\n\n```bash\ncomposer install\nphp artisan migrate\nphp artisan serve\n```\n\n## Entidades\n\n- " . implode("\n- ", $entities),
                            'format' => 'markdown',
                            'download_count' => rand(0, 10),
                            'duration_ms' => rand(250, 3500),
                        ]
                    );
                }
            }
        }
    }

    private function createCrudEndpoints(Project $project, string $entity): void
    {
        $resource = Str::kebab(Str::pluralStudly($entity));
        $basePath = "/api/{$resource}";

        $endpoints = [
            ['GET', $basePath, "List {$entity}", null],
            ['POST', $basePath, "Create {$entity}", ['name' => "Example {$entity}", 'description' => 'Example description']],
            ['GET', "{$basePath}/{id}", "Show {$entity}", null],
            ['PUT', "{$basePath}/{id}", "Update {$entity}", ['name' => "Updated {$entity}", 'description' => 'Updated description']],
            ['DELETE', "{$basePath}/{id}", "Delete {$entity}", null],
        ];

        foreach ($endpoints as [$method, $path, $name, $body]) {
            ProjectEndpoint::create([
                'project_id' => $project->id,
                'method' => $method,
                'path' => $path,
                'name' => $name,
                'description' => "{$name} endpoint.",
                'requires_auth' => true,
                'sample_body' => $body,
            ]);
        }
    }

    private function buildSpec(string $projectName, array $entities): array
    {
        return [
            'project_name' => $projectName,
            'framework' => 'laravel',
            'auth' => [
                'enabled' => true,
                'type' => 'sanctum',
            ],
            'entities' => collect($entities)->map(fn ($entity) => [
                'name' => $entity,
                'table' => Str::snake(Str::pluralStudly($entity)),
                'fields' => [
                    ['name' => 'name', 'type' => 'string', 'required' => true],
                    ['name' => 'description', 'type' => 'text', 'required' => false],
                ],
            ])->toArray(),
        ];
    }
}
