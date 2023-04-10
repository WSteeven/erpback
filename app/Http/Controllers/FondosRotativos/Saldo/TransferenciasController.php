<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Events\TransferenciaSaldoEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferenciaRequest;
use App\Http\Requests\TransferenciaSaldoRequest;
use App\Http\Resources\FondosRotativos\Saldo\TransferenciaResource;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        if($usuario_ac->hasRole('CONTABILIDAD'))
            $results = Transferencias::with('usuario_envia', 'usuario_recibe')->ignoreRequest(['campos'])->filter()->get();
        else
            $results = Transferencias::with('usuario_envia', 'usuario_recibe')->where('usuario_envia_id', Auth::user()->id)->orWhere('usuario_recibe_id',Auth::user()->id) ->ignoreRequest(['campos'])->filter()->get();
        $results = TransferenciaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransferenciaSaldoRequest $request)
    {
        try {
        $datos = $request->validated();
        $contabilidad = User::where('name','mvalarezo')->first();
        $datos['usuario_recibe_id'] = $request->usuario_recibe == 0 ? $contabilidad->id : $request->usuario_recibe;
        $datos['id_tarea'] = $request->tarea==0?null:$request->tarea;
        $datos['estado'] = 3;
        if ($request->comprobante != null) $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante, RutasStorage::TRANSFERENCIASALDO))->execute();
        $modelo = Transferencias::create($datos);
        event(new TransferenciaSaldoEvent($modelo));
        event(new TransferenciaSaldoEvent($modelo,true));
        $modelo = new TransferenciaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    } catch (Exception $e) {
        Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
    }
    }
    public function autorizaciones_transferencia(Request $request)
    {
        $user = Auth::user()->empleado;
        $usuario = User::where('id', $user->id)->first();
        $results = [];

        $results = Transferencias::where('usuario_recibe_id', $user->id)->ignoreRequest(['campos'])->with('usuario_envia', 'usuario_recibe')->filter()->get();
        $results = TransferenciaResource::collection($results);

        return response()->json(compact('results'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $Transferencia = Transferencias::where('id', $id)->first();
        $modelo = new TransferenciaResource($Transferencia);
        return response()->json(compact('modelo'), 200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  Transferencias  $transferencia
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Transferencias $transferencia)
    {
        $datos = $request->all();
        $datos['usuario_envia_id'] = auth()->user()->id;
        $datos['usuario_recibe_id'] = $request->usuario_recibe_id;
        if ($request->comprobante != null) $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante, RutasStorage::TRANSFERENCIASALDO))->execute();
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
    public function destroy(Transferencias $transferencia)
    {
        $transferencia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');

        return response()->json(compact('mensaje'));
    }
    /**
     * It updates the status of the expense to 1, which means it is approved.
     *
     * @param Request request The request object.
     *
     * @return A JSON object with the success message.
     */
    public function aprobar_transferencia(Request $request)
    {
        $transferencia = Transferencias::where('id', $request->id)->first();
        $transferencia->estado = 1;
        $transferencia->save();
        event(new TransferenciaSaldoEvent($transferencia));
        return response()->json(['success' => 'Transferencia realizada correctamente']);
    }
    /**
     * It updates the status of the expense to 1, which means it is rejected.
     *
     * @param Request request The request object.
     *
     * @return A JSON object with the success message.
     */
    public function rechazar_transferencia(Request $request)
    {
        $transferencia = Transferencias::where('id', $request->id)->first();
        $transferencia->estado = 2;
        $transferencia->save();
        event(new TransferenciaSaldoEvent($transferencia));
        return response()->json(['success' => 'Transferencia rechazada']);
    }
    public function anular_transferencia(Request $request)
    {
        $transferencia = Transferencias::where('id', $request->id)->first();
        $transferencia->estado = 4;
        $transferencia->save();
        event(new TransferenciaSaldoEvent($transferencia));
        return response()->json(['success' => 'Transferencia anulada']);
    }
}
