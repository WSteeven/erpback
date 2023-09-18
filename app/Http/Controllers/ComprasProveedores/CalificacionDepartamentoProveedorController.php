<?php

namespace App\Http\Controllers\ComprasProveedores;

use App\Http\Controllers\Controller;
use App\Http\Resources\ComprasProveedores\CalificacionDepartamentoProveedorResource;
use App\Models\ComprasProveedores\CalificacionDepartamentoProveedor;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
use App\Models\Proveedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\App\ArchivoService;
use Src\Config\RutasStorage;

class CalificacionDepartamentoProveedorController extends Controller
{
    private $entidad = 'Calificacion de departamento';
    private $archivoService;
    public function __construct()
    {
        $this->archivoService = new ArchivoService();
        // $this->middleware('can:puede.ver.calificacion_departamento_proveedor')->only('index', 'show');
        // $this->middleware('can:puede.crear.calificacion_departamento_proveedor')->only('store');
        // $this->middleware('can:puede.editar.calificacion_departamento_proveedor')->only('update');
        // $this->middleware('can:puede.eliminar.calificacion_departamento_proveedor')->only('destroy');
    }

    public function index(Request $request)
    {
        $results = CalificacionDepartamentoProveedor::filter()->get();

        $results = CalificacionDepartamentoProveedorResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(Request $request)
    {
        Log::channel('testing')->info('Log', ['Request recibida CalificacionDepartamentoProveedorController', $request->all()]);
        try {
            DB::beginTransaction();

            $modelos = [];
            $detalle = DetalleDepartamentoProveedor::where('departamento_id', auth()->user()->empleado->departamento_id)->where('proveedor_id', $request->proveedor_id)->first();
            $datos = array_map(function ($detalle){
                return [
                    'criterio_calificacion_id' => $detalle['id'],
                    'comentario' => array_key_exists('comentario', $detalle) ? $detalle['comentario'] : null,
                    'peso' => $detalle['peso'],
                    'puntaje' => $detalle['puntaje'],
                    'calificacion' => $detalle['calificacion']
                ];
            }, $request->criterios);
            $detalle->calificaciones_criterios()->sync($datos);
            // if ($request->criterios) {
            //     foreach ($request->criterios as $criterio) {
            //         $calificacion = CalificacionDepartamentoProveedor::create([
                //             'detalle_departamento_id' => $detalle->id,
            //             'comentario' => array_key_exists('comentario', $criterio) ? $criterio['comentario'] : null,
            //             'peso' => $criterio['peso'],
            //             'puntaje' => $criterio['puntaje'],
            //             'calificacion' => $criterio['calificacion']
            //         ]);
            //         array_push($modelos, $calificacion);
            //     }
            // }

            DB::commit();
            //despues del commit se guarda la calificacion en el departamento
            $detalle->update([
                'calificacion' => $request->calificacion,
                'empleado_id' => auth()->user()->empleado->id,
                'fecha_calificacion' => date('Y-m-d H:i:s')
            ]);

            // $proveedor = Proveedor::find($request->proveedor_id);
            Proveedor::guardarCalificacion($request->proveedor_id); //Aquí se llama a la función para guardar la calificacion del proveedor
            $modelo = $detalle->refresh();

            return response()->json(['mensaje' => 'Se crearon exitosamente las calificaciones',  'permisos' => $modelos, 'modelo' => $modelo]);
        } catch (Exception $e) {
            DB::rollback();
            Log::channel('testing')->info('Log', ['Request recibida CalificacionDepartamentoProveedorController', 'Ha ocurrido un error al insertar los registros', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar los registros' . $e->getMessage() . $e->getLine()], 422);
        }
    }

    public function indexFiles(Request $request, $detalle)
    {
        $results = [];
        // Log::channel('testing')->info('Log', ['Recibido del front en indexFiles', $request->all(), $detalle]);
        try {
            $detalle_dept = DetalleDepartamentoProveedor::find($detalle);
            if ($detalle_dept) {
                $results = $detalle_dept->archivos()->get();
            }

            return response()->json(compact('results'));
        } catch (Exception $ex) {
            $mensaje = $ex->getMessage();
            return response()->json(compact('mensaje'));
        }
        return response()->json(compact('results'));
    }


    public function storeFiles(Request $request, $detalle)
    {
        // Log::channel('testing')->info('Log', ['Recibido del front en storeFiles', $request->all(), $detalle]);
        $modelo = [];
        try {
            $detalle_dept = DetalleDepartamentoProveedor::find($detalle);
            if ($detalle_dept) {
                if ($request->allFiles()) {
                    foreach ($request->allFiles() as $archivo) {
                        $archivo = $this->archivoService->guardarArchivo($detalle_dept, $archivo, RutasStorage::CALIFICACIONES_PROVEEDORES->value);
                        array_push($modelo, $archivo);
                    }
                }
            }

            $mensaje = 'Archivo subido correctamente';
        } catch (\Throwable $th) {
            $mensaje = $th->getMessage();
            return response()->json(compact('mensaje'));
        }
        return response()->json(compact('mensaje', 'modelo'));
    }
}
