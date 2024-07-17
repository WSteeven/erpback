<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Exports\RolPagoExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\NominaPrestamos\RolPagoRequest;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\ArchivoRolPagoResource;
use App\Http\Resources\RecursosHumanos\NominaPrestamos\RolPagoResource;
use App\Models\Departamento;
use App\Models\Empleado;
use App\Models\RecursosHumanos\NominaPrestamos\DescuentosGenerales;
use App\Models\RecursosHumanos\NominaPrestamos\EgresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\ExtensionCoverturaSalud;
use App\Models\RecursosHumanos\NominaPrestamos\IngresoRolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Multas;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoHipotecario;
use App\Models\RecursosHumanos\NominaPrestamos\PrestamoQuirografario;
use App\Models\RecursosHumanos\NominaPrestamos\RolPago;
use App\Models\RecursosHumanos\NominaPrestamos\Rubros;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\App\RecursosHumanos\NominaPrestamos\NominaService;
use Src\App\RecursosHumanos\NominaPrestamos\PrestamoService;
use Src\Config\RutasStorage;
use Src\Shared\GuardarArchivo;
use Src\Shared\Utils;

class RolPagosController extends Controller
{
    private $entidad = 'Rol_de_pagos';
    private $reporteService;
    private $nominaService;
    private $prestamoService;


    public function __construct()
    {
        $this->reporteService = new ReportePdfExcelService();
        $this->nominaService = new NominaService();
        $this->prestamoService = new PrestamoService();
        $this->middleware('can:puede.ver.rol_pago')->only('index', 'show');
        $this->middleware('can:puede.crear.rol_pago')->only('store');
    }

    /**
     * Listar.
     */
    public function index(Request $request)
    {
        $results = [];
        $results = RolPago::ignoreRequest(['campos'])->filter()->get();
        $results = RolPagoResource::collection($results);

        return response()->json(compact('results'));
    }

    public function archivo_rol_pago_empleado(Request $request)
    {
        $request->validate([
            'rol_pago_id' => 'required|numeric|integer',
        ]);
        $rolpago = RolPago::find($request['rol_pago_id']);
        if (!$rolpago) {
            throw ValidationException::withMessages([
                'rolpago' => ['El permiso del empleado no existe'],
            ]);
        }
        if (!$request->hasFile('file')) {
            throw ValidationException::withMessages([
                'file' => ['Debe seleccionar al menos un archivo.'],
            ]);
        }

        $archivoJSON =  GuardarArchivo::json($request, RutasStorage::DOCUMENTOS_ROL_EMPLEADO, true, $rolpago->empleado_id);
        $rolpago->rol_firmado = $archivoJSON;
        $rolpago->estado = RolPago::FINALIZADO;
        $rolpago->save();

        $mes = $rolpago->mes;
        // Divide la fecha en mes y año
        list($month, $year) = explode('-', $mes);
        // Crea un objeto Carbon para representar la fecha en Laravel
        $date = \Carbon\Carbon::createFromDate($year, $month, 1);
        // Formatea la fecha en el formato deseado
        $mes = $date->format('Y-m');
        $this->prestamoService->setMes($mes);
        $this->prestamoService->pagarPrestamoEmpresarial();

        return response()->json(['modelo' => $rolpago, 'mensaje' => 'Subido exitosamente!']);
    }

    /**
     * La función recupera un registro rol_pago específico y lo devuelve como una respuesta JSON.
     *
     * @param Request $request El parámetro  es una instancia de la clase Request, que
     * representa la solicitud HTTP actual.
     *
     * @return una respuesta JSON que contiene los resultados de la consulta. Los resultados se están
     * transformando en una colección de recursos de ArchivoRolPago utilizando la clase
     * ArchivoRolPagoResource.
     */
    public function index_archivo_rol_pago_empleado(Request $request)
    {
        $results = RolPago::where('id', $request->rol_pago_id)->get();

        $results = ArchivoRolPagoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * La función "enviar_rolPago_empleado" recupera un pago de rol e información del empleado, luego
     * envía el pago de rol por email al empleado usando el método "enviar_rol_pago" de la clase "nominaService",
     * y finalmente devuelve una respuesta JSON con un mensaje de éxito.
     *
     * @param int $rolPagoId El parámetro "rolPagoId" es el ID del rolPago (nómina) que desea enviar a un
     * empleado.
     *
     * @return una respuesta JSON con un mensaje indicando que la nómina ha sido enviada exitosamente.
     */
    public function enviar_rolPago_empleado($rolPagoId)
    {
        $rol_pago = RolPago::where('id', $rolPagoId)->first();
        $empleado = Empleado::where('id', $rol_pago->empleado_id)->first();
        $this->nominaService->enviar_rol_pago($rol_pago->id, $empleado);
        $mensaje = 'Rol de pago enviado correctamente';
        return response()->json(compact('mensaje'));
    }

    /**
     * La función store crea un nuevo registro RolPago, valida los datos de la solicitud,
     * guarda la identificación del empleado asociado y establece el estado en 'EJECUTANDO', luego
     * comienza una transacción en la base de datos, crea el registro RolPago y llama a un servicio
     * para guardar los ingresos y gastos asociados a la solicitud.
     *
     */
    public function store(RolPagoRequest $request)
    {
        try {
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only(['empleado'])['empleado'];
            $datos['estado'] = 'EJECUTANDO';
            DB::beginTransaction();
            $rolPago = RolPago::create($datos);

            $this->nominaService->guardarIngresosYEgresos($request, $rolPago);

            // foreach ($request->ingresos as $ingreso) {
            //     $this->GuardarIngresos($ingreso, $rolPago);
            // }
            // foreach ($request->egresos as $egreso) {
            //     $this->GuardarEgresos($egreso, $rolPago);
            // }

            $modelo = new RolPagoResource($rolPago);
            DB::commit();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de rol de pago', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }


    /**
     * La función "show" devuelve una respuesta JSON que contiene el objeto "modelo", que se crea a
     * partir del objeto "RolPago" dado.
     *
     * @param RolPago rolPago El parámetro `rolPago` es una instancia de la clase `RolPago`.
     *
     * @return una respuesta JSON con la variable "modelo" como dato.
     */
    public function show(RolPago $rolPago)
    {
        $modelo = new RolPagoResource($rolPago);
        return response()->json(compact('modelo'), 200);
    }

    /**
     * Esta función PHP calcula el nivel de deuda de un empleado en función de su salario y varios
     * montos de préstamos y deducciones.
     */
    public function nivel_endeudamiento(Request $request)
    {
        $empleado = Empleado::where('id', $request->empleado)->first();
        $date = Carbon::now();
        $mes = $date->format('m-Y');
        $salario =  $empleado->salario;
        $porcentaje_iess = Rubros::find(1) != null ? Rubros::find(1)->valor_rubro / 100 : 0;
        $supa = $empleado->supa;
        $prestamo_quirorafario = PrestamoQuirografario::where('empleado_id', $empleado->id)->where('mes', $mes)->sum('valor');
        $prestamo_hipotecario = PrestamoHipotecario::where('empleado_id', $empleado->id)->where('mes', $mes)->sum('valor');
        $extension_conyugal = ExtensionCoverturaSalud::where('empleado_id', $empleado->id)->where('mes', $mes)->sum('aporte');
        $sueldo = $salario;
        $iess = ($sueldo) * $porcentaje_iess;
        $total_descuento =  round(($supa + $prestamo_hipotecario + $extension_conyugal + $prestamo_quirorafario + $iess), 2);
        $porcentaje_endeudamiento = ($total_descuento / $sueldo) * 100;
        $porcentaje_endeudamiento = round(($porcentaje_endeudamiento), 2);

        $results = [
            'total_descuento' => $total_descuento,
            'porcentaje' => $porcentaje_endeudamiento,
            'mensaje' => $porcentaje_endeudamiento > 40 ? 'NIVEL DE ENDEUDAMIENTO SUPERA EL 40%' : ''
        ];
        return response()->json(compact('results'));
    }

    /**
     * Actualizar un rol de pago.
     */
    public function update(RolPagoRequest $request, $rolPagoId): JsonResponse
    {
        Log::channel('testing')->info('Log', ['ID',  $rolPagoId]);
        Log::channel('testing')->info('Log', ['request', $request->all(), $rolPagoId]);
        $datos = $request->validated();
        $rolPago = RolPago::findOrFail($rolPagoId);
        $rolPago->update($datos);
        Log::channel('testing')->info('Log', ['rol actualizado', $rolPago->refresh()]);

        $this->nominaService->guardarIngresosYEgresos($request, $rolPago);
        // $this->guardarIngresosYEgresos($request, $rolPago);

        $modelo = new RolPagoResource($rolPago->refresh());
        Log::channel('testing')->info('Log', ['rol actualizado pasado por el resource', $rolPago->refresh()]);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * La función destruye un objeto RolPago buscándolo usando su ID y luego eliminándolo.
     *
     * @param rolPagoId El parámetro `rolPagoId` es el ID del objeto `RolPago` que debe eliminarse.
     *
     * @return el objeto RolPago eliminado.
     */
    public function destroy($rolPagoId)
    {
        $rolPago = RolPago::find($rolPagoId);
        $rolPago->delete();
        return $rolPago;
    }

    /**
     * La función `cambiar_estado` actualiza el estado de un objeto `RolPago` basado en el valor del
     * parámetro `estado` y devuelve una respuesta JSON con el modelo actualizado y un mensaje.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que
     * representa la solicitud HTTP actual. Contiene información sobre la solicitud, como el método de
     * solicitud, encabezados y datos de entrada.
     * @param rolPagoId Es el ID del objeto `RolPago` cuyo estado desea actualizar.
     *
     * @return una respuesta JSON que contiene las variables "modelo" y "mensaje".
     */
    public function cambiar_estado(Request $request, $rolPagoId)
    {
        $rolPago = RolPago::find($rolPagoId);
        $estado_mensaje = '';
        switch ($request->estado) {
            case RolPago::EJECUTANDO:
                $estado_mensaje = ' Ejecutado el Rol de Pagos';
                break;
            case RolPago::REALIZADO:
                $estado_mensaje = ' Realizado el Rol de Pagos';
                break;
            case  RolPago::CANCELADO:
                break;
            case RolPago::FINALIZADO:
                break;
        }
        $rolPago->estado = $request->estado;
        $rolPago->save();
        $modelo = new RolPagoResource($rolPago->refresh());
        $mensaje = 'Se ha ' . $estado_mensaje;
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * La función `imprimir_rol_pago` genera un informe en PDF para el pago de un rol específico
     * utilizando datos de la base de datos.
     *
     * @param rolPagoId El parámetro `rolPagoId` es el ID del rol_pago (nómina) específico que desea
     * imprimir.
     *
     * @return el resultado del método `imprimir_reporte` del objeto `reporteService`.
     */
    public function imprimir_rol_pago($rolPagoId)
    {
        try {
            $nombre_reporte = 'rol_pagos';
            $roles_pagos = RolPago::where('id', $rolPagoId)->get();
            $results = RolPago::empaquetarListado($roles_pagos);
            $recursosHumanos = Departamento::where('id', 7)->first()->responsable_id;
            $responsable = Empleado::where('id', $recursosHumanos)->first();
            $reportes =  ['roles_pago' => $results, 'responsable' => $responsable];
            $vista = 'recursos-humanos.rol_pagos';
            $export_excel = new RolPagoExport($reportes);
            return $this->reporteService->imprimir_reporte('pdf', 'A5', 'landscape', $reportes, $nombre_reporte, $vista, $export_excel);
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * La función "actualizar_masivo" actualiza el estado de un grupo de objetos "RolPago" de "CREADO"
     * a "EJECUTANDO"
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que se
     * utiliza para recuperar datos de la solicitud HTTP. En este caso, se utiliza para recuperar el
     * valor del parámetro 'rol_pago_id' de la solicitud.
     *
     * @return una respuesta JSON con un mensaje que indica que se ha iniciado la ejecución de todo el
     * rol.
     */
    public function actualizar_masivo(Request $request)
    {
        // Realizar la actualización masiva
        RolPago::where('rol_pago_id',  $request->rol_pago_id)->where('estado', RolPago::CREADO)
            ->update(['estado' => RolPago::EJECUTANDO]);
        return response()->json(['mensaje' => 'Se ha comenzado a ejecutar todo el rol']);
    }

    /**
     * La función "finalizar_masivo" actualiza el estado de un grupo de objetos "RolPago" a
     * "FINALIZADO" en base a un "rol_pago_id" determinado y devuelve una respuesta JSON con un mensaje
     * de éxito.
     *
     * @param Request request El parámetro  es una instancia de la clase Request, que
     * representa una solicitud HTTP. Contiene todos los datos y la información sobre la solicitud
     * actual, como el método de solicitud, los encabezados y los parámetros de la solicitud.
     *
     * @return una respuesta JSON con un mensaje que indica que se ha finalizado todo el "rol".
     */
    public function finalizar_masivo(Request $request)
    {
        // Realizar la actualización masiva
        RolPago::where('rol_pago_id',  $request->rol_pago_id)->where('estado', RolPago::EJECUTANDO)
            ->update(['estado' => RolPago::FINALIZADO]);
        return response()->json(['mensaje' => 'Se ha finalizado todo el rol']);
    }
}
