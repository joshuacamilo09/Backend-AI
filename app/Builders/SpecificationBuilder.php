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
            //Procuramos pelo nome do projeto e se nn existir usamos o default que tá como generated-project.
            'project_name' => $this->normalizeProjectName($raw['project_name'] ?? 'generated-project'),

            //Se a IA não dizer a framework a ser usada, assumimos que será laravel.
            'framework' => $raw['framework'] ?? 'laravel',

            //Se a IA definimos a autenticação, se a IA nn enviar nada, vai estar com auth ativada automaticamente e a usar jwt
            'auth' => [
                'enabled' => (bool)($raw['auth']['enabled'] ?? true),
                'type' => $raw['auth']['type'] ?? 'jwt',
            ],

            //Se a IA nn enviar as entidades, vamos receber um array vazio.
            'entities' => $this->normalizeEntities($raw['entities'] ?? []),

            //Relações entre entidades entidades, se nn enviar, vamos receber um array vazio
            'relations' => $this->normalizeRelations($raw['relations'] ?? []),

            //rotas que o projeto vai ter, se nn enviar, vamos receber um array vazio
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
        //recebe uma string e devolve uma string limpa em minusculo
        $name = strtolower(trim($name));

        //removemos espaços e trocamos por hifens, removemos caracteres especiais, para ficar num formato seguro e compativel.
        $name = preg_replace('/[^a-z0-9]+/', '-', $name);

        //retornamos o nome limpo e formatado, e se nn der certo vai ser "generated-project"
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
    private function normalizeEntities(array $entities): array // recebemos uma lista de entidades e normalizamos cada uma.
    {
        //array_map para percorrer cada item do array e transforma-lo.
        return array_map(function (array $entity) {
            return [
                //se a entidade nn tiver nome, usa "UnknownEntity"
                'name' => $entity['name'] ?? 'UnknownEntity',

                //a entidade transforma o nome da tabela para minusculas, mas se a entidade nn receber um nome da tabela, usa o proprio nome da entidade com um "s" no fina.
                'table' => $entity['table'] ?? strtolower(($entity['name'] ?? 'unknown')) . 's',

                //percorremos os campos das entidades
                'fields' => array_map(function (array $field) {
                    return [
                        //preenchermos o nome, se nn tiver, usa "unknown_field"
                        'name' => $field['name'] ?? 'unknown_field',

                        //preenchermos o tipo, se nn tiver, usa "string"
                        'type' => $field['type'] ?? 'string',

                        //preenchermos o required, se nn tiver, usa false
                        'required' => (bool)($field['required'] ?? false),

                        //preenchermos o unique, se nn tiver, usa false
                        'unique' => (bool)($field['unique'] ?? false),
                    ];
                    //Se a entidades nn tiver campos, retorna um array vazio
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
        //percorremos as relações para ver quais são
        return array_map(function (array $relation) {
            return [
                //se a relação nn tiver um tipo, usa one-to-many
                'type' => $relation['type'] ?? 'one-to-many',

                //se a relação nn tiver um from, usa null
                'from' => $relation['from'] ?? null,

                //se a relación nn tiver um to, usa null
                'to' => $relation['to'] ?? null,

                //se a relação nn tiver um foreign_key, usa null
                'foreign_key' => $relation['foreign_key'] ?? null,
            ];
        }, $relations);
    }


    /*
        Garante que se a IA não especificar nada, assume um CRUD completo para garantir que cada entidade tem pelo menos um conjunto básico de rotas CRUD.
    */
    private function normalizeEndpoints(array $endpoints): array
    {
        //percerremos as rotas para ver quais são
        return array_map(function (array $endpoint) {
            return [
                //se a rotas nn tiver um entity, usa null
                'entity' => $endpoint['entity'] ?? null,

                //se a IA nn enviar rotas, o sistema assume um crud completo mapeado para get, post, put e delete.
                'routes' => $endpoint['routes'] ?? ['index', 'show', 'store', 'update', 'destroy'],
            ];
        }, $endpoints);
    }
}

/*
    Este ficherio afecta diretamnete:
        - geração de backend
        - geração de documentação
        - geração de migrations
        - geração de models
        - geração de controllers
        - geração de rotas
        - geração de documentação
        - API Tester
        - dashboard e analytics indiretamente.
*/
