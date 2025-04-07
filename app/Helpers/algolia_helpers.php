<?php


use Algolia\AlgoliaSearch\SearchClient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Src\App\Sistema\PaginationService;
use Src\Config\Constantes;

if (!function_exists('buscarConAlgoliaFiltrado')) {
    /**
     * Filtra con Eloquent y luego busca en Algolia, devolviendo los modelos.
     *
     * @param Builder $query Query base con filtros previos
     * @param string $idColumn Columna de ID a usar en filtros (default: 'id')
     * @param string|null $search Texto de búsqueda (si null, retorna resultado de $query)
     * @param string|null $indexName Nombre del índice de Algolia (si null, se usa el modelo de la query)
     * @param int $perPage Cuántos hits por página
     * @param int|null $page Página actual (default: request('page', 0))
     * @param bool $paginate Si paginar con Laravel o devolver una colección simple
 * @return Builder|LengthAwarePaginator|Collection
     */
    function buscarConAlgoliaFiltrado(
        Builder $query,
        string  $idColumn = 'id',
        ?string $search = null,
        ?string $indexName = null,
        int     $perPage = Constantes::PAGINATION_ITEMS_PER_PAGE,
        ?int    $page = null,
        bool $paginate = false
    )
    {
        $paginacion_service = new PaginationService();
        if (is_null($search)) {
            return $paginate? $paginacion_service->paginate($query, $perPage, $page): $query->get();
        }
        $ids = $query->pluck($idColumn);

        if ($ids->isEmpty()) {
            return collect();
        }

        $filters = implode(' OR ', $ids->map(fn($id) => "$idColumn:$id")->toArray());

        $client = SearchClient::create(
            config('scout.algolia.id'),
            config('scout.algolia.secret')
        );

        // Inferimos el índice desde el modelo si no se pasa explícitamente
        if (!$indexName) {
            $modelClass = get_class($query->getModel());
            $indexName = (new $modelClass)->searchableAs();
        }

        $index = $client->initIndex($indexName);

        $results = $index->search($search, [
            'filters' => "($filters)",
            'hitsPerPage' => $perPage,
            'page' => $page ?? request('page', 0),
        ]);

        $resultIds = collect($results['hits'])->pluck($idColumn);

        return $query->getModel()->whereIn($idColumn, $resultIds)->get();
    }
}
