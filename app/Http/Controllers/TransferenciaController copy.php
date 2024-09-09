<?php

namespace App\Http\Controllers;

use App\Http\Requests\TransferenciaRequest;
use App\Http\Resources\TransferenciaResource;
use App\Models\Transferencia;
use App\Models\User;
use Illuminate\Http\Request;
use Src\App\TransferenciaService;
use Src\Shared\Utils;

class Transferencia2Controller extends Controller
{
    private $entidad = 'Transacción';
    private $servicio;
    public function __construct()
    {
        $this->servicio = new TransferenciaService();
        $this->middleware('can:puede.ver.transacciones_egresos')->only('index', 'show');
        $this->middleware('can:puede.crear.transacciones_egresos')->only('store');
        $this->middleware('can:puede.editar.transacciones_egresos')->only('update');
        $this->middleware('can:puede.eliminar.transacciones_egresos')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $page = $request['page'];
        $offset = $request['offset'];
        $estado = $request['estado'];
        $tipo = 'TRANSFERENCIA';
        $results = [];

        if (auth()->user()->hasRole(User::ROL_BODEGA)) {
            $results = Transferencia::all();
        }

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransferenciaRequest $request)
    {
        $datos = $request->validated();
        !is_null($request->motivo) ?? $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        $datos['solicitante_id'] = $request->safe()->only(['solicitante'])['solicitante'];
        $datos['sucursal_id'] = $request->safe()->only(['sucursal'])['sucursal'];
        $datos['motivo_id'] = $request->safe()->only(['motivo'])['motivo'];
        $datos['per_autoriza_id'] = $request->safe()->only(['per_autoriza'])['per_autoriza'];
        !is_null($request->subtarea_id) ?? $datos['subtarea_id'] = $request->safe()->only(['subtarea'])['subtarea'];
        !is_null($request->per_atiende) ?? $datos['per_atiende_id'] = $request->safe()->only(['per_atiende'])['per_atiende'];
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Transferencia  $transferencia
     * @return \Illuminate\Http\Response
     */
    public function show(Transferencia $transferencia)
    {
        $modelo = new TransferenciaResource($transferencia);
        return response()->json(compact('modelo'));
    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Transferencia  $transferencia
     * @return \Illuminate\Http\Response
     */
    public function update(TransferenciaRequest $request, Transferencia $transferencia)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Transferencia  $transferencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transferencia $transferencia)
    {
        $transferencia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Consultar datos sin el método show
     */
    public function showPreview(Transferencia $transferencia)
    {
        $modelo = new TransferenciaResource($transferencia);

        return response()->json(compact('modelo'), 200);
    }
}
