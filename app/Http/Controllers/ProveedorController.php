<?php

namespace App\Http\Controllers;

use App\Events\ComprasProveedores\CalificacionProveedorEvent;
use App\Exports\ComprasProveedores\CalificacionProveedorExcel;
use App\Exports\ComprasProveedores\ProveedorExport;
use App\Exports\ComprasProveedores\TodosProveedoresExport;
use App\Http\Requests\ComprasProveedores\ProveedorRequest;
use App\Http\Resources\ComprasProveedores\ProveedorResource;
use App\Models\Archivo;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use App\Models\ConfiguracionGeneral;
use App\Models\Departamento;
use App\Models\Proveedor;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\ArchivoService;
use Src\App\ComprasProveedores\ProveedorService;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class ProveedorController extends Controller
{
    private string $entidad = 'Proveedor';
    private ArchivoService $archivoService;
    private ProveedorService $proveedorService;
//    private  $reporteService;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        $this->proveedorService = new ProveedorService();
//        $this->reporteService = new ReportePdfExcelService();
        $this->middleware('can:puede.ver.proveedores')->only('index', 'show');
        $this->middleware('can:puede.crear.proveedores')->only('store');
        $this->middleware('can:puede.editar.proveedores')->only('update');
        $this->middleware('can:puede.eliminar.proveedores')->only('destroy');
    }
    /**
     * Listar
     */
    public function index(Request $request)
    {
        if ($request->boolean('filtrarProveedores')) {
            if (auth()->user()->hasRole([User::ROL_ADMINISTRADOR, User::ROL_COMPRAS, User::ROL_CONTABILIDAD]))
                $results = Proveedor::ignoreRequest(['filtrarProveedores'])->filter()->get();
            else {
                $results = Proveedor::whereHas('departamentos_califican', function ($query) {
                    $query->where('departamento_id', auth()->user()->empleado->departamento_id);
                })->ignoreRequest(['filtrarProveedores'])->filter()->get();
            }
        } else {
            $results = Proveedor::ignoreRequest(['filtrarProveedores'])->filter()->get();
        }

        $results = ProveedorResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Guardar
     * @throws ValidationException|Throwable
     */
    public function store(ProveedorRequest $request)
    {
        $departamento_financiero = Departamento::where('nombre', Departamento::DEPARTAMENTO_FINANCIERO)->first();
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['forma_pago'] = Utils::convertArrayToString($request->forma_pago);

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
            $this->guardarLogisticaProveedor($proveedor, $request);

            //Verificando si hay archivos en la request
            if ($request->allFiles()) {
                foreach ($request->files() as $archivo) {
                    $this->archivoService->guardarArchivo($proveedor->empresa, $archivo, RutasStorage::PROVEEDORES);
                }
            }

            $modelo = new ProveedorResource($proveedor);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();

            foreach ($proveedor->departamentos_califican as $departamento) {
                event(new CalificacionProveedorEvent($proveedor, auth()->user()->empleado->id, $departamento['responsable_id'], false));
            }

            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable|Exception $e) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($e);
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
     * @throws ValidationException|Throwable
     */
    public function update(ProveedorRequest $request, Proveedor  $proveedor)
    {
        $departamento_financiero = Departamento::where('nombre', 'FINANCIERO')->first();
        try {
            DB::beginTransaction();
            //Adaptación de foreign keys
            $datos = $request->validated();
            $datos['forma_pago'] = Utils::convertArrayToString($request->forma_pago);

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
            $this->guardarLogisticaProveedor($proveedor, $request);

            //Verificando si hay archivos en la request
            if ($request->allFiles()) {
                foreach ($request->files() as $archivo) {
                    $this->archivoService->guardarArchivo($proveedor->empresa, $archivo, RutasStorage::PROVEEDORES);
                }
            }

            $modelo = new ProveedorResource($proveedor->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Throwable|Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$e->getMessage()],
            ]);
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
     * @throws ValidationException
     */
    public function reportes(Request $request)
    {
        $configuracion = ConfiguracionGeneral::first();
        try {
            $vista = 'compras_proveedores.proveedores.proveedores';
            $request['empresa.razon_social'] = $request->razon_social;
            $results = $this->proveedorService->filtrarProveedores($request);
            $registros = $this->proveedorService->empaquetarDatos($results, 'razon_social');
            $contactos = $this->proveedorService->empaquetarDatosContactos($results, 'razon_social');
            $datosBancarios = $this->proveedorService->empaquetarDatosBancariosProveedor($results, 'razon_social');
            $proveedoresCompletos = $this->proveedorService->empaquetarDatosProveedoresCompletos($results, 'razon_social');
            switch ($request->accion) {
                case 'excel':
                    $reporte = $registros;
                    return Excel::download(new ProveedorExport(collect($reporte), collect($contactos), collect($datosBancarios), $configuracion, collect($proveedoresCompletos)), 'reporte_proveedores.xlsx');
                case 'pdf':
                    try {
                        $reporte = $registros;
                        $peticion = $request->all();
                        $pdf = Pdf::loadView($vista, compact(['reporte', 'peticion', 'configuracion']));
                        $pdf->setPaper('A4', 'landscape');
                        $pdf->render();
                        return $pdf->stream();
                    } catch (Throwable $ex) {
                        throw Utils::obtenerMensajeErrorLanzable($ex);
                    }
                default:
                    // Log::channel('testing')->info('Log', ['ProveedorController->reportes->default', '¿Todo bien en casa?']);
            }
        } catch (Exception $ex) {
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$ex->getMessage()],
            ]);
        }
        $results = ProveedorResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * @throws ValidationException
     */
    public function reporteTodos()
    {
        try {
            $proveedoresCompletos = $this->proveedorService->empaquetarDatosProveedoresCompletos(Proveedor::all(), 'razon_social');
            return Excel::download(new TodosProveedoresExport($proveedoresCompletos), 'datos_proveedores.xlsx');
        } catch (Exception $ex) {

            throw ValidationException::withMessages([
                'Error al generar reporte' => [$ex->getMessage()],
            ]);
        }
    }

    /**
     * @throws ValidationException
     */
    public function reporteCalificacion(Proveedor $proveedor)
    {
        try {
            $registros = $this->proveedorService->empaquetarDatosCalificacionProveedor($proveedor);

            return Excel::download(new CalificacionProveedorExcel(collect($registros)), 'calificacion_proveedor.xlsx');
        } catch (Exception $ex) {
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$ex->getMessage() . '. ' . $ex->getLine()],
            ]);
        }
    }




    /**
     * Listar archivos enlazados a los detalle_departamento_proveedor de un proveedor dado
     */
    public function indexFilesDepartamentosCalificadores(Proveedor $proveedor)
    {
        try {
            $idsDetallesDepartamentos = DetalleDepartamentoProveedor::where('proveedor_id', $proveedor->id)->get('id');
            $results = Archivo::whereIn('archivable_id', $idsDetallesDepartamentos)->get();
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'), 500);
        }
        return response()->json(compact('results'));
    }


    /**
     * @throws Exception
     */
    public function actualizarCalificacion(Proveedor $proveedor)
    {
        Proveedor::guardarCalificacion($proveedor->id);
        $modelo = new ProveedorResource($proveedor->refresh());
        $mensaje = 'Calificación de proveedor actualizada con éxito';
        return response()->json(compact('mensaje', 'modelo'));
    }

    public function proveedoresConOrdenes(Request $request)
    {
        // Log::channel('testing')->info('Log', ['Requst:', $request->all()]);
        $campos = request('campos') ? explode(',', request('campos')) : '*';
        $proveedores = Proveedor::whereHas('ordenesCompras', function ($query) use ($request, $campos) {
            $query->when($request->solicitante_id, function ($q) use ($request) {
                $q->where('solicitante_id', $request->solicitante_id);
            });
        })->get($campos);

        $results = ProveedorResource::collection($proveedores);
        return response()->json(compact('results'));
    }

    /**
     * @param Proveedor $proveedor
     * @param ProveedorRequest $request
     * @return void
     */
    private function guardarLogisticaProveedor(Proveedor $proveedor, ProveedorRequest $request): void
    {
        if ($proveedor->empresa->logistica()->first()) {
            $proveedor->empresa->logistica()->update([
                'tiempo_entrega' => $request->tiempo_entrega,
                'envios' => $request->envios,
                'tipo_envio' => Utils::convertArrayToString($request->tipo_envio),
                'transporte_incluido' => $request->transporte_incluido,
                'garantia' => $request->garantia,
            ]);
        } else {
            $proveedor->empresa->logistica()->create([
                'tiempo_entrega' => $request->tiempo_entrega,
                'envios' => $request->envios,
                'tipo_envio' => Utils::convertArrayToString($request->tipo_envio),
                'transporte_incluido' => $request->transporte_incluido,
                'garantia' => $request->garantia,
            ]);
        }
    }
}
