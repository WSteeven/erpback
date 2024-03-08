<?php

namespace App\Http\Controllers\FondosRotativos;

use App\Http\Controllers\Controller;
use App\Http\Requests\FondosRotativos\AjusteSaldoFondoRotativoRequest;
use App\Http\Resources\FondosRotativos\AjusteSaldoFondoRotativoResource;
use App\Models\FondosRotativos\AjusteSaldoFondoRotativo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AjusteSaldoFondoRotativoController extends Controller
{
    private $entidad = 'Ajuste de Saldo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.ajustes_saldos')->only('index', 'show');
        $this->middleware('can:puede.crear.ajustes_saldos')->only('store');
    }


    public function index()
    {
        $results = AjusteSaldoFondoRotativo::filter()->orderBy('id', 'desc')->get();
        $results = AjusteSaldoFondoRotativoResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(AjusteSaldoFondoRotativoRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
                //hacer toda la logica para cuando se guarde un ajuste este sume o reste el saldo del empleado segun corresponda
                // este tipo de transacciones deben verse reflejadas en el estado de cuenta, 
                // no se veran reflejadas en ningun otro reporte



            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al guardar el ajuste' => [$e->getMessage(), $e->getLine()],
            ]);
        }
    }

    /**
     * Consultar
     */
    public function show(AjusteSaldoFondoRotativo $ajuste)
    {
        $modelo = new AjusteSaldoFondoRotativoResource($ajuste);
        return response()->json(compact('modelo'));
    }
}
