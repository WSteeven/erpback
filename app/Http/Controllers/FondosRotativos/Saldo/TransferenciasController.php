<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Events\TransferenciaSaldoContabilidadEvent;
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
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
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
        try {
            $results = [];
            $usuario = Auth::user();
            $usuario_ac = User::where('id', $usuario->id)->first();
            if ($usuario_ac->hasRole('CONTABILIDAD'))
                $results = Transferencias::with('empleadoEnvia', 'empleadoRecibe')->ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
            else
                $results = Transferencias::with('empleadoEnvia', 'empleadoRecibe')->where('usuario_envia_id', Auth::user()->empleado->id)->orWhere('usuario_recibe_id', Auth::user()->empleado->id)->ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
            $results = TransferenciaResource::collection($results);
            return response()->json(compact('results'));
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'Error al consultar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(TransferenciaSaldoRequest $request)
    {
        DB::beginTransaction();
        try {
            $datos = $request->validated();
            $datos['estado'] = Transferencias::PENDIENTE;
            if ($request->comprobante != null) $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante, RutasStorage::TRANSFERENCIASALDO))->execute();
            $modelo = Transferencias::create($datos);
            event(new TransferenciaSaldoEvent($modelo));
            event(new TransferenciaSaldoContabilidadEvent($modelo));
            $modelo = new TransferenciaResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }
    public function autorizacionesTransferencia(Request $request)
    {
        try {
            $user = Auth::user()->empleado;
            $usuario = User::where('id', $user->id)->first();
            $results = [];
            $results = Transferencias::where('usuario_recibe_id', $user->id)->ignoreRequest(['campos'])->with('empleadoEnvia', 'empleadoRecibe')->filter()->get();
            $results = TransferenciaResource::collection($results);
            return response()->json(compact('results'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al consultar registro' => [$e->getMessage()],
            ]);
        }
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
    public function update(Request $request, TransferenciaSaldoRequest $transferencia)
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
    public function aprobarTransferencia(Request $request)
    {
        try {
            DB::beginTransaction();
            $transferencia = Transferencias::find($request->id);
            $transferencia->estado = Transferencias::APROBADO;
            $transferencia->save();
            DB::commit();
            return response()->json(['success' => 'Transferencia realizada correctamente']);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al aprobar gasto' => [$e->getMessage()],
            ]);
        }
    }
    /**
     * It updates the status of the expense to 1, which means it is rejected.
     *
     * @param Request request The request object.
     *
     * @return A JSON object with the success message.
     */
    public function rechazarTransferencia(Request $request)
    {
        $transferencia = Transferencias::where('id', $request->id)->first();
        $transferencia->estado = Transferencias::RECHAZADO;
        $transferencia->save();
        event(new TransferenciaSaldoEvent($transferencia));
        return response()->json(['success' => 'Transferencia rechazada']);
    }
/**
 * La función `anularTransferencia` cancela una transferencia y dispara un evento para notificar sobre
 * la cancelación.
 *
 * @param Request request La función `anularTransferencia` se utiliza para cancelar una transferencia
 * en la base de datos. Aquí hay un desglose del código:
 *
 * @return La función `anularTransferencia` está devolviendo una respuesta JSON con un mensaje de éxito
 * 'Transferencia anulada'.
 */
    public function anularTransferencia(Request $request)
    {
        $transferencia = Transferencias::where('id', $request->id)->first();
        $transferencia->estado = Transferencias::ANULADO;
        $transferencia->save();
        event(new TransferenciaSaldoEvent($transferencia));
        return response()->json(['success' => 'Transferencia anulada']);
    }
}
