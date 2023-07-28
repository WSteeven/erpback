<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Http\Requests\VacacionRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\VacacionResource;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\PermisoEmpleado;
use App\Models\RecursosHumanos\NominaPrestamos\Vacacion;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class VacacionController extends Controller
{
    private $entidad = 'Solicitudde vacación';
    public function __construct()
    {
        $this->middleware('can:puede.ver.vacacion')->only('index', 'show');
        $this->middleware('can:puede.crear.vacacion')->only('store');
        $this->middleware('can:puede.editar.vacacion')->only('update');
        $this->middleware('can:puede.eliminar.vacacion')->only('update');
    }

    /**
     * La función de índice recupera datos de vacaciones en función del rol del usuario y los devuelve
     * como una respuesta JSON.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que representa
     * una solicitud HTTP. Contiene información sobre la solicitud actual, como el método de solicitud,
     * los encabezados y los datos de entrada.
     *
     * @return una respuesta JSON que contiene la variable 'resultados'.
     */
    public function index(Request $request)
    {
        $results = [];
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
    public function show(Request $request, Vacacion $Vacacion)
    {
        $modelo = new VacacionResource($Vacacion);
        return response()->json(compact('modelo'), 200);
    }
    /**
     * La función "descuentos_permiso" calcula la duración total de los permisos de vacaciones de un
     * determinado empleado.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que se utiliza
     * para recuperar datos de la solicitud HTTP. En este caso, se utiliza para recuperar el valor del
     * parámetro "empleado" de la solicitud.
     *
     * @return la duración en horas de los permisos de vacaciones para un empleado específico.
     */
    public function descuentos_permiso(Request $request)
    {
        $duracionEnDias = PermisoEmpleado::where('empleado_id', $request->empleado)->where('cargo_vacaciones', 1)
            ->selectRaw('SUM(TIMESTAMPDIFF(HOUR, fecha_hora_inicio, fecha_hora_fin)) as duracion')
            ->first();
        return $duracionEnDias;
    }
    /**
     * La función almacena datos de vacaciones y realiza comprobaciones de validación.
     *
     * @param VacacionRequest request El parámetro `` es una instancia de la clase
     * `VacacionRequest`. Se utiliza para validar y recuperar los datos enviados en la solicitud HTTP.
     *
     * @return una respuesta JSON que contiene las variables 'mensaje' y 'modelo'.
     */
    public function store(VacacionRequest $request)
    {
        // 1. Verificar y obtener la vacación existente o crear una nueva
        $datos = $request->validated();
        $empleado_tiene_vacaciones = Vacacion::where('empleado_id', $request->empleado_id)->where('periodo_id', $request->periodo_id)->first();
        $empleado = Empleado::where('id', $request->empleado_id)->first();
        $fechaInicio = Carbon::parse($empleado->fecha_ingreso);
        $fechaActual = Carbon::now();

        // Calcula la diferencia entre las dos fechas en años
        $diferencia = $fechaInicio->diffInYears($fechaActual);
        if ($diferencia <= 1) {
            throw ValidationException::withMessages([
                '404' => ['Vacaciones no disponibles debido a que no cumple un año trabajando en la empresa'],
            ]);
        }


        if ($empleado_tiene_vacaciones != null) {
            throw ValidationException::withMessages([
                '404' => ['Ya ha solicitado vaciones en este periodo'],
            ]);
        }
        if ($request->numero_dias != null) {
            $dias_descuentos_permiso = intval(($request->descuento_vacaciones / 24));
            if ($dias_descuentos_permiso == 0) {
                if ($request->numero_dias != 15) {
                    throw ValidationException::withMessages([
                        '404' => ['Por favor ingrese en rangos de vacaciones'],
                    ]);
                }
            }
            $resta_dias_permiso = $request->numero_dias - $dias_descuentos_permiso;
            $total_dias_aceptable = 15 - $dias_descuentos_permiso;
            $dias_restantes = $request->numero_dias - $total_dias_aceptable;
            $dias_restantes_texto = ' ' . $dias_restantes > 1 ? 'días' : 'día';
            if ($resta_dias_permiso  ==  $total_dias_aceptable) {
                throw ValidationException::withMessages([
                    '404' => ['No se puede dar vacaciones en las fechas establecidas porfavor disminuya ' . $dias_restantes . ' ' . $dias_restantes_texto],
                ]);
            }
        }
        // 3. Validar el rango en caso de ser necesario
        if ($request->numero_dias_rango1 != null && $request->numero_dias_rango2 != null) {
            $suma_srangos = $request->numero_dias_rango1 + $request->numero_dias_rango2;
            if ($suma_srangos != 15 || $request->numero_dias_rango1 < 7 || $request->numero_dias_rango1 > 8 || $request->numero_dias_rango2 < 7 || $request->numero_dias_rango2 > 8) {
                throw ValidationException::withMessages([
                    '404' => ['Por favor ingrese días en rango 1 y rango 2 que sumen 15 y estén entre 7 y 8 respectivamente'],
                ]);
            }
        }
        // 4. Crear la vacación y devolver la respuesta
        $datos['estado'] =  Vacacion::PENDIENTE;
        $vacacion = Vacacion::create($datos);
        $modelo = new VacacionResource($vacacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
    /**
     * La función actualiza un modelo de Vacaciones con los datos validados de la solicitud y devuelve
     * una respuesta JSON con un mensaje y el modelo actualizado.
     *
     * @param VacacionRequest request El parámetro  es una instancia de la clase VacacionRequest,
     * que se utiliza para validar y recuperar los datos de la solicitud HTTP.
     * @param Vacacion Vacacion El parámetro "Vacacion" es una instancia del modelo "Vacacion".
     * Representa un registro de vacaciones específico en la base de datos.
     *
     * @return una respuesta JSON que contiene las variables 'mensaje' y 'modelo'.
     */
    public function update(VacacionRequest $request, Vacacion $Vacacion)
    {
        $datos = $request->validated();
        $datos['estado'] = $request->estado;
        $Vacacion->update($datos);
        $modelo = new VacacionResource($Vacacion);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
        return $Vacacion;
    }
    /**
     * La función destruye un objeto Vacaciones y devuelve una respuesta JSON.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que representa
     * una solicitud HTTP realizada al servidor. Contiene información sobre la solicitud, como el método
     * HTTP, los encabezados y los datos de la solicitud.
     * @param Vacacion Vacacion El parámetro Vacaciones es una instancia del modelo Vacaciones.
     * Representa un registro de vacaciones específico que debe eliminarse de la base de datos.
     *
     * @return una respuesta JSON que contiene el objeto Vacaciones eliminado.
     */
    public function destroy(Request $request, Vacacion $Vacacion)
    {
        $Vacacion->delete();
        return response()->json(compact('Vacacion'));
    }
}
