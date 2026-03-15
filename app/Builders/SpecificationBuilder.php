<?php

namespace App\Builders;

class SpecificationBuilder
{
    /**
     * Normaliza a resposta da IA e garante estrutura mínima.
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

    private function normalizeProjectName(string $name): string
    {
        $name = strtolower(trim($name));
        $name = preg_replace('/[^a-z0-9]+/', '-', $name);
        return trim($name, '-') ?: 'generated-project';
    }

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
