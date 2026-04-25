<?php

namespace App\Builders;

/*
    A IA devlve uma resposta "solta" com campos que podem vir incompletos, mal formatados ou inconsistentes.

    Essa classe pega nessa resposta e converte para uma estrutura mais consistente e limpa, de movo a evitar erros durante a geração do backend.
*/

class SpecificationBuilder
{
    /*
      Normaliza a resposta da IA e garante estrutura mínima. isto afecta a maneira como o json me devolve os dados.
     */
    public function build(array $raw): array
    {
        return [
            'project_name' => $this->normalizeProjectName($raw['project_name'] ?? 'generated-project'),
            'framework' => $raw['framework'] ?? 'laravel',
            'auth' => [
                'enabled' => (bool)($raw['auth']['enabled'] ?? true),
                'type' => $raw['auth']['type'] ?? 'jwt',
            ],
            'entities' => $this->normalizeEntities($raw['entities'] ?? []),
            'relations' => $this->normalizeRelations($raw['relations'] ?? []),
            'endpoints' => $this->normalizeEndpoints($raw['endpoints'] ?? []),
        ];
    }

    /*
      Converte pra minúsculas
      Remove caracters especiais e espaços
      Substitui tudo por -
      Garante que nunca fique fazio devido ao:
             " ?: 'generated-project' "
    */
    private function normalizeProjectName(string $name): string
    {
        $name = strtolower(trim($name));
        $name = preg_replace('/[^a-z0-9]+/', '-', $name);
        return trim($name, '-') ?: 'generated-project';
    }

    /*
        Para cada entidade:
            - garante que tenham nome
            - gera automaticamente o nome da tabela
            - normaliza cada campo
            - garante que cada campo tem:
                - nome
                - tipo
                - required (true or false)
                - unique (true or false)
    */
    private function normalizeEntities(array $entities): array
    {
        return array_map(function (array $entity) {
            return [
                'name' => $entity['name'] ?? 'UnknownEntity',
                'table' => $entity['table'] ?? strtolower(($entity['name'] ?? 'unknown')) . 's',
                'fields' => array_map(function (array $field) {
                    return [
                        'name' => $field['name'] ?? 'unknown_field',
                        'type' => $field['type'] ?? 'string',
                        'required' => (bool)($field['required'] ?? false),
                        'unique' => (bool)($field['unique'] ?? false),
                    ];
                }, $entity['fields'] ?? []),
            ];
        }, $entities);
    }


    /*
        Garante que cada relação tem:
            - tipo (padrão one-to-many)
            - entidade origem e destino
            - chave estrangeira
    */
    private function normalizeRelations(array $relations): array
    {
        return array_map(function (array $relation) {
            return [
                'type' => $relation['type'] ?? 'one-to-many',
                'from' => $relation['from'] ?? null,
                'to' => $relation['to'] ?? null,
                'foreign_key' => $relation['foreign_key'] ?? null,
            ];
        }, $relations);
    }


    /*
        Garante que se a IA não especificar nada, assume um CRUD completo para garantir que cada entidade tem pelo menos um conjunto básico de rotas CRUD.
    */
    private function normalizeEndpoints(array $endpoints): array
    {
        return array_map(function (array $endpoint) {
            return [
                'entity' => $endpoint['entity'] ?? null,
                'routes' => $endpoint['routes'] ?? ['index', 'show', 'store', 'update', 'destroy'],
            ];
        }, $endpoints);
    }
}
