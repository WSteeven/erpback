<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ValorEmpleadoRolMensualResource;
use App\Models\RecursosHumanos\NominaPrestamos\ValorEmpleadoRolMensual;
use Illuminate\Http\JsonResponse;

class ValorEmpleadoRolMensualController extends Controller
{
//    private string $entidad = "Valor";

    public function __construct()
    {
        $this->middleware('can:puede.ver.vacaciones')->only('index', 'show');
//        $this->middleware('can:puede.editar.vacaciones')->only('update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = ValorEmpleadoRolMensual::filter()->get();
        $results = ValorEmpleadoRolMensualResource::collection($results);
        return response()->json(compact('results'));
    }
//
//    /**
//     * Store a newly created resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @return \Illuminate\Http\Response
//     */
//    public function store(Request $request)
//    {
//        //
//    }

    /**
     * Display the specified resource.
     *
     * @param ValorEmpleadoRolMensual $valor
     * @return JsonResponse
     */
    public function show(ValorEmpleadoRolMensual $valor)
    {
        $modelo = new ValorEmpleadoRolMensualResource($valor);
        return response()->json(compact('modelo'));
    }

//    /**
//     * Update the specified resource in storage.
//     *
//     * @param  \Illuminate\Http\Request  $request
//     * @param  int  $id
//     * @return \Illuminate\Http\Response
//     */
//    public function update(Request $request, $id)
//    {
//        //
//    }

}
