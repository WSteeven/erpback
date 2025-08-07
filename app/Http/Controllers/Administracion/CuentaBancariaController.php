<?php

namespace App\Http\Controllers\Administracion;

use App\Http\Controllers\Controller;
use App\Http\Requests\Administracion\CuentaBancariaRequest;
use App\Http\Resources\Administracion\CuentaBancariaResource;
use App\Models\Administracion\CuentaBancaria;
use DB;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class CuentaBancariaController extends Controller
{
    private string $entidad = 'Cuenta bancaria';

    public function __construct()
    {
        $this->middleware('can:puede.ver.cuentas_bancarias')->only('index', 'show');
        $this->middleware('can:puede.crear.cuentas_bancarias')->only('store');
        $this->middleware('can:puede.editar.cuentas_bancarias')->only('update');
        $this->middleware('can:puede.eliminar.cuentas_bancarias')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = CuentaBancaria::filter()->orderByDesc('es_principal')->orderBy('id', 'desc')->get();

        $results = CuentaBancariaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CuentaBancariaRequest $request
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function store(CuentaBancariaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $this->asegurarSoloUnaPrincipal($datos);
            // Respuesta
            $modelo = CuentaBancaria::create($datos);
            DB::commit();

            $modelo = new CuentaBancariaResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        } catch (Throwable $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param CuentaBancaria $cuenta
     * @return JsonResponse
     */
    public function show(CuentaBancaria $cuenta)
    {
        $modelo = new CuentaBancariaResource($cuenta);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CuentaBancariaRequest $request
     * @param CuentaBancaria $cuenta
     * @return JsonResponse
     * @throws Throwable
     * @throws ValidationException
     */
    public function update(CuentaBancariaRequest $request, CuentaBancaria $cuenta)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $this->asegurarSoloUnaPrincipal($datos, $cuenta->id);
            // Respuesta
            $cuenta->update($datos);
            DB::commit();

            $modelo = new CuentaBancariaResource($cuenta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        } catch (Throwable $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param CuentaBancaria $cuenta
     * @return JsonResponse
     */
    public function destroy(CuentaBancaria $cuenta)
    {
        $cuenta->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Garantiza que solo una cuenta bancaria quede marcada como principal.
     *
     * Si los datos recibidos tienen 'es_principal' en true, desmarca cualquier otra cuenta
     * bancaria que esté como principal en la base de datos. En el caso de actualización,
     * puede excluir la cuenta actual para evitar desmarcarse a sí misma.
     *
     * @param array $datos Datos validados del request, debe incluir 'es_principal'.
     * @param int|null $excluirId (Opcional) ID de la cuenta a excluir del cambio, útil en update.
     * @return void
     */
    private function asegurarSoloUnaPrincipal(array $datos, ?int $excluirId = null): void
    {
        if (!empty($datos['es_principal']) && $datos['es_principal']) {
            $query = CuentaBancaria::where('es_principal', true);
            if ($excluirId) {
                $query->where('id', '!=', $excluirId);
            }
            $query->update(['es_principal' => false]);
        }
    }
}
