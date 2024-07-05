<?php

namespace Src\App\Sistema;

use App\Http\Resources\TareaResource;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Log;

class PaginationService
{
    /**
     * Pagina una consulta y devuelve los resultados paginados.
     *
     * @param Builder $query
     * @param int $perPage
     * @param int $page
     * @return LengthAwarePaginator
     */
    public function paginate(Builder $query, $perPage = 50, $page = null)
    {
        $page = $page ?: LengthAwarePaginator::resolveCurrentPage();

        return $query->paginate($perPage, ['*'], 'page', $page);
        // return $this->formatPaginatedResults($paginated);
    }

    public static function formatPaginatedResults($paginated) //, $collectionResource)
    {
        Log::channel('testing')->info('Log', ['paginated', $paginated]);
        // Log::channel('testing')->info('Log', ['collectionResource', $collectionResource]);

        $array = $paginated->toArray();
        $array['results'] = $array['data']; // TareaResource::collection(collect($array['data']));
        unset($array['data']);
        return $array;
    }

    public static function formatPaginatedResultsOld($paginated)
    {
        Log::channel('testing')->info('Log', ['paginated', $paginated]);
        $array = $paginated->toArray();
        $array['results'] = $array['data'];
        unset($array['data']);
        return $array;
    }
}
