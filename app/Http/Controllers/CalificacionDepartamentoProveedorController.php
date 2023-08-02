<?php

namespace App\Http\Controllers;

use App\Http\Resources\CalificacionDepartamentoProveedorResource;
use App\Models\CalificacionDepartamentoProveedor;
use App\Models\DetalleDepartamentoProveedor;
use App\Models\Proveedor;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class CalificacionDepartamentoProveedorController extends Controller
{
    private $entidad = 'Calificacion de departamento';
    public function __construct()
    {
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
            if ($request->criterios) {
                foreach ($request->criterios as $criterio) {
                    $calificacion = CalificacionDepartamentoProveedor::create([
                        'detalle_departamento_id' => $detalle->id,
                        'criterio_calificacion_id' => $criterio['id'],
                        'comentario' => array_key_exists('comentario', $criterio) ? $criterio['comentario'] : null,
                        'peso' => $criterio['peso'],
                        'puntaje' => $criterio['puntaje'],
                        'calificacion' => $criterio['calificacion']
                    ]);
                    array_push($modelos, $calificacion);
                }
            }

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
            Log::channel('testing')->info('Log', ['Request recibida CalificacionDepartamentoProveedorController', 'Ha ocurrido un error al insertar los registros' , $e->getMessage() , $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar los registros' . $e->getMessage() . $e->getLine()], 422);
        }
    }
}
