<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComprasProveedores\CuentaBancariaResource;
use App\Models\ComprasProveedores\CuentaBancaria;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Src\App\Sistema\PaginationService;

class CuentaBancariaController extends Controller
{
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return AnonymousResourceCollection
     */
    public function index()
    {
        $search = request('search');
        $paginate = request('paginate');
        $beneficiario_id = request('beneficiario_id');

        if ($search) $query = CuentaBancaria::where('numero_cuenta','LIKE', "%$search%")->query(function ($q) use ($beneficiario_id) {
            $q->whereHas('beneficiario', fn($q) => $q->where('id', $beneficiario_id));
        });
        else $query = CuentaBancaria::ignoreRequest(['campos'])->filter()->latest();

        if ($paginate) $results = $this->paginationService->paginate($query, 100, request('page'));
        else $results = $query->get();

        return CuentaBancariaResource::collection($results);
    }


}
