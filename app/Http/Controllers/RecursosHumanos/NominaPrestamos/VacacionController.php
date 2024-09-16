<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Events\VacacionEvent;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\VacacionRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\VacacionResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use App\Models\User;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class VacacionController extends Controller
{
    private string $entidad = 'Solicitud de vacación';
    public function __construct()
    {
        $this->middleware('can:puede.ver.vacacion')->only('index', 'show');
        $this->middleware('can:puede.crear.vacacion')->only('store');
        $this->middleware('can:puede.editar.vacacion')->only('update');
        $this->middleware('can:puede.eliminar.vacacion')->only('destroy');
    }

    /**
     * La función de índice recupera datos de vacaciones en función del rol del usuario y los devuelve
     * como una respuesta JSON.
     *
     * @return JsonResponse respuesta JSON que contiene la variable 'resultados'.
     */
    public function index()
    {
        $usuario = Auth::user();
        $usuario_ac = User::where('id', $usuario->id)->first();
        if ($usuario_ac->hasRole('RECURSOS HUMANOS')) {
            $results = Vacacion::ignoreRequest(['campos'])->filter()->get();
        } else {
            $empleados = Empleado::where('jefe_id', Auth::user()->empleado->id)->orWhere('id', Auth::user()->empleado->id)->get('id');
            $results = Vacacion::ignoreRequest(['campos'])->filter()->WhereIn('empleado_id', $empleados->pluck('id'))->get();
        }
        $results = VacacionResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * La función almacena datos de vacaciones y realiza comprobaciones de validación.
     *
     * @param VacacionRequest $request El parámetro `` es una instancia de la clase
     * `VacacionRequest`. Se utiliza para validar y recuperar los datos enviados en la solicitud HTTP.
     *
     * @return JsonResponse respuesta JSON que contiene las variables 'mensaje' y 'modelo'.
     * @throws ValidationException|Throwable
     */
    public function store(VacacionRequest $request)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();

            $empleado = Empleado::findOrFail($request->empleado_id);
            $fechaInicio = Carbon::parse($empleado->fecha_ingreso);

            $diferencia = $fechaInicio->diffInYears(
                $request->fecha_inicio ?? $request->fecha_inicio_rango1_vacaciones
            );
            if ($diferencia <= 0) {
                throw ValidationException::withMessages([
                    '404' => ['Vacaciones no disponibles debido a fecha establecida es menor a un año de trabajo en la empresa'],
                ]);
            }

            $empleado_tiene_vacaciones = Vacacion::where('empleado_id', $request->empleado_id)
                ->where('periodo_id', $request->periodo_id)
                ->first();
            if ($empleado_tiene_vacaciones) {
                throw ValidationException::withMessages([
                    '404' => ['Ya ha solicitado vacaciones en este periodo'],
                ]);
            }

            $total_dias_aceptable = 15;
            if ($request->numero_dias != null) {
                $dias_descuentos_permiso = intval($request->descuento_vacaciones / 24);
                $resta_dias_permiso = $request->numero_dias - $dias_descuentos_permiso;

                if ($dias_descuentos_permiso == 0 && $request->numero_dias != $total_dias_aceptable) {
                    throw ValidationException::withMessages([
                        '404' => ['Por favor ingrese en rangos de vacaciones'],
                    ]);
                }

                if ($request->descuento_vacaciones > 0 && $resta_dias_permiso == $total_dias_aceptable) {
                    throw ValidationException::withMessages([
                        '404' => ['No se puede dar vacaciones en las fechas establecidas por favor disminuya ' . $resta_dias_permiso . ' día' . ($resta_dias_permiso > 1 ? 's' : '')],
                    ]);
                }
            }

            if ($request->numero_dias_rango1 != null && $request->numero_dias_rango2 != null) {
                $suma_srangos = $request->numero_dias_rango1 + $request->numero_dias_rango2;
                if ($suma_srangos != $total_dias_aceptable || $request->numero_dias_rango1 < 7 || $request->numero_dias_rango1 > 8 || $request->numero_dias_rango2 < 7 || $request->numero_dias_rango2 > 8) {
                    throw ValidationException::withMessages([
                        '404' => ['Por favor ingrese días en rango 1 y rango 2 que sumen 15 y estén entre 7 y 8 respectivamente'],
                    ]);
                }
            }

            $datos['estado'] = Vacacion::PENDIENTE;
            $vacacion = Vacacion::create($datos);
            event(new VacacionEvent($vacacion));
            $modelo = new VacacionResource($vacacion);

            DB::commit();

            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();

            Log::channel('testing')->info('Log', ['Ha ocurrido un error al insertar el registro:', $e->getMessage(), $e->getLine()]);

            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    public function show( Vacacion $Vacacion)
    {
        $modelo = new VacacionResource($Vacacion);
        return response()->json(compact('modelo'));
    }
    /**
     * La función "descuentos_permiso" calcula la duración total de los permisos de vacaciones de un
     * determinado empleado.
     *
     * @param Request $request El parámetro es una instancia de la clase Request, que se utiliza
     * para recuperar datos de la solicitud HTTP. En este caso, se utiliza para recuperar el valor del
     * parámetro "empleado" de la solicitud.
     *
     */
    public function descuentos_permiso(Request $request)
    {
        return PermisoEmpleado::where('empleado_id', $request->empleado)->where('cargo_vacaciones', 1)
            ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, fecha_hora_inicio, fecha_hora_fin)) as duracion')
            ->first();
    }

    /**
     * La función actualiza un modelo de Vacaciones con los datos validados de la solicitud y devuelve
     * una respuesta JSON con un mensaje y el modelo actualizado.
     *
     * @param VacacionRequest $request El parámetro es una instancia de la clase VacacionRequest,
     * que se utiliza para validar y recuperar los datos de la solicitud HTTP.
     * @param Vacacion $Vacacion El parámetro "Vacacion" es una instancia del modelo "Vacacion".
     * Representa un registro de vacaciones específico en la base de datos.
     *
     * @return JsonResponse respuesta JSON que contiene las variables 'mensaje' y 'modelo'.
     * @throws Exception
     */
    public function update(VacacionRequest $request, Vacacion $Vacacion)
    {
        $datos = $request->validated();
        $datos['estado'] = $request->estado;
        $Vacacion->update($datos);
        event(new VacacionEvent($Vacacion));
        $modelo = new VacacionResource($Vacacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * La función destruye un objeto Vacaciones y devuelve una respuesta JSON.
     *
     * @param Vacacion $Vacacion El parámetro Vacaciones es una instancia del modelo Vacaciones.
     * Representa un registro de vacaciones específico que debe eliminarse de la base de datos.
     *
     * @return JsonResponse respuesta JSON que contiene el objeto Vacaciones eliminado.
     */
    public function destroy( Vacacion $Vacacion)
    {
        $Vacacion->delete();
        return response()->json(compact('Vacacion'));
    }
}
