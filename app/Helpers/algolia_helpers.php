<?php


use Algolia\AlgoliaSearch\SearchClient;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Log;
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
        string $modelClass,
        Builder $query,
        string  $idColumn = 'id',
        ?string $search = null,
        int     $perPage = Constantes::PAGINATION_ITEMS_PER_PAGE,
        ?int    $page = null,
        bool $paginate = false,
        ?string $filters = null
    )
    {
        $paginacion_service = new PaginationService();
        if (is_null($search)) {
            return $paginate? $paginacion_service->paginate($query, $perPage, $page): $query->get();
        }

        $client = SearchClient::create(
            config('scout.algolia.id'),
            config('scout.algolia.secret')
        );

        $model = new $modelClass;
        $indexName =  $model->searchableAs();
        $index = $client->initIndex($indexName);


        $results = $index->search($search, [
            'filters' => $filters,
            'hitsPerPage' => 500,
            // 'page' => $page ?? request('page', 0),
        ]);

        Log::channel('testing')->info('Log', ['algolia -> $results', $results ]);
        $resultIds = collect($results['hits'])->pluck($idColumn);
        Log::channel('testing')->info('Log', ['algolia -> $search', $index->search($search) ]);

        $query = $modelClass::whereIn($idColumn, $resultIds)->orderBy($idColumn, 'desc');

        return $paginate? $paginacion_service->paginate($query, $perPage, $page): $query->get();
        // return $query->getModel()->whereIn($idColumn, $allResults)->get();
    }
}
