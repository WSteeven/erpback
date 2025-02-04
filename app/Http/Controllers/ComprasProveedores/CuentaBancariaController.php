<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComprasProveedores\CuentaBancariaResource;
use App\Models\ComprasProveedores\CuentaBancaria;
use Illuminate\Http\Request;
use Src\App\Sistema\PaginationService;

class CuentaBancariaController extends Controller
{
    private string $entidad = 'Beneficiario';
    protected PaginationService $paginationService;

    public function __construct()
    {
        $this->paginationService = new PaginationService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $search = request('search');
        $paginate = request('paginate');
        $beneficiario_id = request('beneficiario_id');

        if ($search) $query = CuentaBancaria::search($search)->query(function ($q) use ($beneficiario_id) {
            $q->whereHas('beneficiario', fn($q) => $q->where('id', $beneficiario_id));
        });
        else $query = CuentaBancaria::ignoreRequest(['campos'])->filter()->latest();

        if ($paginate) $results = $this->paginationService->paginate($query, 100, request('page'));
        else $results = $query->get();

        return CuentaBancariaResource::collection($results);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
