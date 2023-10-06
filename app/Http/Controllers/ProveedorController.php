<?php

namespace App\Http\Controllers;

use App\Events\ComprasProveedores\CalificacionProveedorEvent;
use App\Exports\ComprasProveedores\CalificacionProveedorExcel;
use App\Exports\ComprasProveedores\ProveedorExport;
use App\Http\Requests\ComprasProveedores\ProveedorRequest;
use App\Http\Resources\ComprasProveedores\ProveedorResource;
use App\Models\Archivo;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use App\Models\ConfiguracionGeneral;
use App\Models\Departamento;
use App\Models\Proveedor;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\ArchivoService;
use Src\App\ComprasProveedores\ProveedorService;
use Src\App\FondosRotativos\ReportePdfExcelService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class ProveedorController extends Controller
{
    private $entidad = 'Proveedor';
    private $archivoService;
    private $proveedorService;
    private $reporteService;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->proveedorService = new ProveedorService();
        $this->reporteService = new ReportePdfExcelService();
        $this->middleware('can:puede.ver.proveedores')->only('index', 'show');
        $this->middleware('can:puede.crear.proveedores')->only('store');
        $this->middleware('can:puede.editar.proveedores')->only('update');
        $this->middleware('can:puede.eliminar.proveedores')->only('destroy');
    }
    /**
     * Listar
     */
    public function index()
    {
        $results = ProveedorResource::collection(Proveedor::filter()->get());
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     */
    public function store(ProveedorRequest $request)
    {
        Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        $departamento_financiero = Departamento::where('nombre', 'FINANCIERO')->first();
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
            $datos['parroquia_id'] = $request->safe()->only(['parroquia'])['parroquia'];
            $datos['forma_pago'] = Utils::convertArrayToString($request->forma_pago, ',');

            // Log::channel('testing')->info('Log', ['Datos validados', $datos]);
            //Respuesta
            $proveedor = Proveedor::create($datos);
            $proveedor->servicios_ofertados()->attach($request->tipos_ofrece);
            $proveedor->categorias_ofertadas()->attach($datos['categorias_ofrece']);
            $proveedor->departamentos_califican()->sync($request->departamentos);
            if (is_int($request->departamentos)) {
                if ($departamento_financiero->id != $request->departamentos)
                    $proveedor->departamentos_califican()->attach($departamento_financiero->id);
            } else {
                if (!in_array($departamento_financiero->id, $request->departamentos)) {
                    $proveedor->departamentos_califican()->attach($departamento_financiero->id);
                }
            }

            //guardando la logistica del proveedor
            if ($proveedor->empresa->logistica()->first()) {
                // Log::channel('testing')->info('Log', ['Ya existe logistica:', $proveedor->empresa->logistica()->first()]);
                $proveedor->empresa->logistica()->update([
                    'tiempo_entrega' => $request->tiempo_entrega,
                    'envios' => $request->envios,
                    'tipo_envio' => Utils::convertArrayToString($request->tipo_envio, ','),
                    'transporte_incluido' => $request->transporte_incluido,
                    'garantia' => $request->garantia,
                ]);
            } else {
                // Log::channel('testing')->info('Log', ['No existe logistica:', Utils::convertirStringComasArray($request->tipo_envio), $request->all()]);
                // Log::channel('testing')->info('Log', ['No existe logistica:', $request->all()]);
                $proveedor->empresa->logistica()->create([
                    'tiempo_entrega' => $request->tiempo_entrega,
                    'envios' => $request->envios,
                    'tipo_envio' =>  Utils::convertArrayToString($request->tipo_envio, ','),
                    'transporte_incluido' => $request->transporte_incluido,
                    'garantia' => $request->garantia,
                ]);
            }

            //Verificando si hay archivos en la request
            if ($request->allFiles()) {
                foreach ($request->files() as $archivo) {
                    $archivo = $this->archivoService->guardar($proveedor->empresa, $archivo, RutasStorage::PROVEEDORES);
                }
            }

            $modelo = new ProveedorResource($proveedor);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();

            Log::channel('testing')->info('Log', ['Modelo a recorrer', $proveedor->departamentos_califican]);
            foreach ($proveedor->departamentos_califican as $departamento) {
                event(new CalificacionProveedorEvent($proveedor, auth()->user()->empleado->id, $departamento['responsable_id'], false));
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            $mensaje = '(' . $e->getLine() . ') Hubo un erorr: ' . $e->getMessage();
            return response()->json(compact('mensaje'), 500);
            //throw $th;
        }
    }


    /**
     * Consultar
     */
    public function show(Proveedor $proveedor)
    {
        $modelo = new ProveedorResource($proveedor);
        return response()->json(compact('modelo'));
    }

    /**
     * Consultar sin el show en los resources
     */
    public function showPreview(Proveedor $proveedor)
    {
        $modelo = new ProveedorResource($proveedor);
        return response()->json(compact('modelo'));
    }


    /**
     * Actualizar
     */
    public function update(ProveedorRequest $request, Proveedor  $proveedor)
    {
        Log::channel('testing')->info('Log', ['Solicitud recibida:', $request->all()]);
        $departamento_financiero = Departamento::where('nombre', 'FINANCIERO')->first();
        try {
            DB::beginTransaction();
            //Adaptación de foreign keys
            $datos = $request->validated();
            $datos['empresa_id'] = $request->safe()->only(['empresa'])['empresa'];
            $datos['parroquia_id'] = $request->safe()->only(['parroquia'])['parroquia'];
            $datos['forma_pago'] = Utils::convertArrayToString($request->forma_pago, ',');
            
            //Respuesta
            $proveedor->update($datos);

            //attaching related models
            $proveedor->servicios_ofertados()->sync($request->tipos_ofrece);
            $proveedor->categorias_ofertadas()->sync($request->categorias_ofrece);
            $proveedor->departamentos_califican()->sync($request->departamentos);
            if (!in_array($departamento_financiero->id, $request->departamentos)) {
                $proveedor->departamentos_califican()->attach($departamento_financiero->id);
            }

            //guardando la logistica del proveedor
            if ($proveedor->empresa->logistica()->first()) {
                // Log::channel('testing')->info('Log', ['Ya existe logistica:', $proveedor->empresa->logistica()->first()]);
                $proveedor->empresa->logistica()->update([
                    'tiempo_entrega' => $request->tiempo_entrega,
                    'envios' => $request->envios,
                    'tipo_envio' => Utils::convertArrayToString($request->tipo_envio, ','),
                    'transporte_incluido' => $request->transporte_incluido,
                    'garantia' => $request->garantia,
                ]);
            } else {
                // Log::channel('testing')->info('Log', ['No existe logistica:', Utils::convertirStringComasArray($request->tipo_envio), $request->all()]);
                // Log::channel('testing')->info('Log', ['No existe logistica:', $request->all()]);
                $proveedor->empresa->logistica()->create([
                    'tiempo_entrega' => $request->tiempo_entrega,
                    'envios' => $request->envios,
                    'tipo_envio' =>  Utils::convertArrayToString($request->tipo_envio, ','),
                    'transporte_incluido' => $request->transporte_incluido,
                    'garantia' => $request->garantia,
                ]);
            }

            //Verificando si hay archivos en la request
            if ($request->allFiles()) {
                foreach ($request->files() as $archivo) {
                    $archivo = $this->archivoService->guardar($proveedor->empresa, $archivo, RutasStorage::PROVEEDORES);
                }
            }

            $modelo = new ProveedorResource($proveedor->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            Log::channel('testing')->info('Log', ['Error:', $e->getLine(), $e->getMessage()]);
            return response()->json(['mensaje' => $e->getMessage() . '. ' . $e->getLine()], 422);
        }
    }


    /**
     * Eliminar
     */
    public function destroy(Proveedor $proveedor)
    {
        $proveedor->departamentos_califican()->detach();
        $proveedor->servicios_ofertados()->detach();
        $proveedor->categorias_ofertadas()->detach();
        $proveedor->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /**
     * Desactivar un proveedor
     */
    public function anular(Request $request, Proveedor $proveedor)
    {
        $request->validate(['motivo' => ['required', 'string']]);
        $proveedor->causa_inactivacion = $request->motivo;
        $proveedor->estado = !$proveedor->estado;
        $proveedor->save();

        $modelo = new ProveedorResource($proveedor->refresh());
        return response()->json(compact('modelo'));
    }

    /**
     * Reportes
     */
    public function reportes(Request $request)
    {
        $configuracion = ConfiguracionGeneral::first();
        Log::channel('testing')->info('Log', ['ProveedorController->reportes', $request->all()]);
        $results = [];
        try {
            $vista = 'compras_proveedores.proveedores.proveedores';
            $request['empresa.razon_social'] = $request->razon_social;
            $results = $this->proveedorService->filtrarProveedores($request);
            $registros = $this->proveedorService->empaquetarDatos($results, 'razon_social');
            $contactos = $this->proveedorService->empaquetarDatosContactos($results, 'razon_social');
            $datosBancarios = $this->proveedorService->empaquetarDatosBancariosProveedor($results, 'razon_social');
            switch ($request->accion) {
                case 'excel':
                    $reporte = $registros;
                    Log::channel('testing')->info('Log', ['Lo que se va a imprimir', $reporte, $contactos, $datosBancarios]);
                    return Excel::download(new ProveedorExport(collect($reporte), collect($contactos), collect($datosBancarios)), 'reporte_proveedores.xlsx');
                    // return $this->reporteService->imprimir_reporte('excel', 'A4', 'landscape', $reporte, 'reporte_proveedores', $vista, $export_excel);
                    break;
                case 'pdf':
                    try {
                        $reporte = $registros;
                        $peticion = $request->all();
                        $pdf = Pdf::loadView($vista, compact(['reporte', 'peticion', 'configuracion']));
                        $pdf->setPaper('A4', 'landscape');
                        $pdf->render();
                        // return $pdf->output();
                        return $pdf->stream();
                        // return $this->reporteService->imprimir_reporte('pdf', 'A4', 'landscape', $reportes, 'reporte_proveedores', $vista);
                    } catch (Exception $ex) {
                        Log::channel('testing')->info('Log', ['ERROR', $ex->getMessage(), $ex->getLine()]);
                    }
                    break;
                default:
                    // Log::channel('testing')->info('Log', ['ProveedorController->reportes->default', '¿Todo bien en casa?']);
            }
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['error', $ex->getMessage(), $ex->getLine()]);
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$ex->getMessage()],
            ]);
        }
        $results = ProveedorResource::collection($results);
        return response()->json(compact('results'));
    }

    public function reporteCalificacion(Proveedor $proveedor)
    {
        try {
            $registros = $this->proveedorService->empaquetarDatosCalificacionProveedor($proveedor);

            return Excel::download(new CalificacionProveedorExcel(collect($registros)), 'calificacion_proveedor.xlsx');
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Error en reporte de calificacion de proveedores', $ex->getMessage(), $ex->getLine()]);
            return response()->json(['message' => 'Error de validacion' . $ex->getMessage() . $ex->getLine()], 422);
        }
    }




    /**
     * Listar archivos enlazados a los detalle_departamento_proveedor de un proveedor dado
     */
    public function indexFilesDepartamentosCalificadores(Proveedor $proveedor)
    {
        $results = [];
        try {
            $idsDetallesDepartamentos = DetalleDepartamentoProveedor::where('proveedor_id', $proveedor->id)->get('id');
            $results = Archivo::whereIn('archivable_id', $idsDetallesDepartamentos)->get();
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Error en el listarArchivos de Archivo Service', $ex->getMessage(), $ex->getCode(), $ex->getLine()]);
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('results'));
    }
}
