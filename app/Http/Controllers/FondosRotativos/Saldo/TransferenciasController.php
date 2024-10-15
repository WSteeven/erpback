<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Events\TransferenciaSaldoContabilidadEvent;
use App\Events\TransferenciaSaldoEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\TransferenciaSaldoRequest;
use App\Http\Resources\FondosRotativos\Saldo\TransferenciaResource;
use App\Models\FondosRotativos\Saldo\Transferencias;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class TransferenciasController extends Controller
{
    private string $entidad = 'transferencia';

    public function __construct()
    {
        $this->middleware('can:puede.ver.transferencia')->only('index', 'show');
        $this->middleware('can:puede.crear.transferencia')->only('store');
        $this->middleware('can:puede.editar.transferencia')->only('update');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     * @throws ValidationException
     */
    public function index()
    {
        try {
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
     * @param TransferenciaSaldoRequest $request
     * @return JsonResponse
     * @throws ValidationException|Throwable
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

    /**
     * @throws ValidationException
     */
    public function autorizacionesTransferencia()
    {
        try {
            $user = Auth::user()->empleado;
            $results = Transferencias::where('usuario_recibe_id', $user->id)->ignoreRequest(['campos'])->with('empleadoEnvia', 'empleadoRecibe')->filter()->orderBy('id', 'desc')->get();
            $results = TransferenciaResource::collection($results);
            return response()->json(compact('results'));
        } catch (Exception $e) {
            throw ValidationException::withMessages([
                'Error al consultar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Transferencias $transferencia
     * @return JsonResponse
     */
    public function show(Transferencias $transferencia)
    {
        $modelo = new TransferenciaResource($transferencia);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param TransferenciaSaldoRequest $request
     * @param Transferencias $transferencia
     * @return JsonResponse
     * @throws ValidationException
     */
    public function update(TransferenciaSaldoRequest $request, Transferencias $transferencia)
    {
        try {
            $datos = $request->all();
            if ($request->comprobante != null)
                $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante, RutasStorage::TRANSFERENCIASALDO))->execute();
            $transferencia->update($datos);
            $modelo = new TransferenciaResource($transferencia->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable $e) {
            throw  Utils::obtenerMensajeErrorLanzable($e);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Transferencias $transferencia
     * @return JsonResponse
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
     * @param Request $request The request object.
     *
     * @return JsonResponse JSON object with the success message.
     * @throws Throwable|ValidationException
     */
    public function aprobarTransferencia(Request $request)
    {
        DB::beginTransaction();
        try {
            $transferencia_repetida = Transferencias::where('estado', Transferencias::APROBADO)->where('id', $request->id)->lockForUpdate()->get();
            if ($transferencia_repetida->count() > 0) {
                throw ValidationException::withMessages([
                    '404' => ['Transferencia  ya fue aprobada'],
                ]);
            }
            $transferencia = Transferencias::find($request->id);
            if ($transferencia) {
                $transferencia->estado = Transferencias::APROBADO;
                $transferencia->save();
                event(new TransferenciaSaldoEvent($transferencia));
                event(new TransferenciaSaldoContabilidadEvent($transferencia));
                DB::commit();
            } else {
                throw ValidationException::withMessages([
                    '404' => ['Transferencia no encontrada'],
                ]);
            }
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
     * @param Request $request The request object.
     *
     * @return JsonResponse JSON object with the success message.
     * @throws Throwable|ValidationException
     */
    public function rechazarTransferencia(Request $request)
    {
        DB::beginTransaction();
        try {
            $transferencia = Transferencias::where('id', $request->id)->first();
            if ($transferencia) {
                $transferencia->estado = Transferencias::RECHAZADO;
                $transferencia->save();
                event(new TransferenciaSaldoEvent($transferencia));
                event(new TransferenciaSaldoContabilidadEvent($transferencia));
                DB::commit();
            } else throw new Exception('Transferencia no encontrada');

            return response()->json(['mensaje' => 'Transferencia rechazada']);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al aprobar gasto' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * La función `anularTransferencia` cancela una transferencia y dispara un evento para notificar sobre
     * la cancelación.
     *
     * @param Request $request La función `anularTransferencia` se utiliza para cancelar una transferencia
     * en la base de datos. Aquí hay un desglose del código:
     *
     * @return JsonResponse función `anularTransferencia` está devolviendo una respuesta JSON con un mensaje de éxito
     * 'Transferencia anulada'.
     * @throws ValidationException|Throwable
     */
    public function anularTransferencia(Request $request)
    {
        DB::beginTransaction();
        try {
            $transferencia_repetida = Transferencias::where('estado', Transferencias::ANULADO)->where('id', $request->id)->lockForUpdate()->get();
            if ($transferencia_repetida->count() > 0) {
                throw ValidationException::withMessages([
                    '404' => ['Transferencia  ya fue anulada'],
                ]);
            }
            $transferencia = Transferencias::where('id', $request->id)->first();
            if ($transferencia) {
                $transferencia->estado = Transferencias::ANULADO;
                $transferencia->save();
                event(new TransferenciaSaldoEvent($transferencia));
                event(new TransferenciaSaldoContabilidadEvent($transferencia));
                DB::commit();
            } else {
                throw ValidationException::withMessages([
                    '404' => ['Transferencia no encontrada'],
                ]);
            }
            return response()->json(['success' => 'Transferencia anulada']);
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al aprobar gasto' => [$e->getMessage()],
            ]);
        }
    }
}
