<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Saldo\TransferenciaResource;
use App\Models\Transferencia;
use Illuminate\Http\Request;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class TransferenciasController extends Controller
{
    private $entidad = 'transferencia';
    public function __construct()
    {
        $this->middleware('can:puede.ver.transferencia')->only('index', 'show');
        $this->middleware('can:puede.crear.transferencia')->only('store');
        $this->middleware('can:puede.editar.transferencia')->only('update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = Transferencia::with('usuario')->ignoreRequest(['campos'])->filter()->get();
        $results = TransferenciaResource::collection($results);
        return response()->json(compact('results'));

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['usuario_envia_id'] = auth()->user()->id;
        $datos['usuario_recibe_id'] = $request->usuario_recibe_id;
        if ($request->comprobante != null) $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante1, RutasStorage::TRANSFERENCIAS))->execute();
        $modelo = Transferencia::create($datos);
        $modelo = new TransferenciaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Transferencia = Transferencia::where('id', $id)->first();
        $modelo = new TransferenciaResource($Transferencia);
        return response()->json(compact('modelo'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Transferencia  $transferencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,Transferencia $transferencia)
    {
        $datos = $request->all();
        $datos['usuario_envia_id'] = auth()->user()->id;
        $datos['usuario_recibe_id'] = $request->usuario_recibe_id;
        if ($request->comprobante != null) $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante1, RutasStorage::TRANSFERENCIAS))->execute();
        $modelo = $transferencia->update($datos);
        $modelo = new TransferenciaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  Transferencia  $transferencia
     * @return \Illuminate\Http\Response
     */
    public function destroy(Transferencia $transferencia)
    {
        $transferencia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
}
