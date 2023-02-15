<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Exports\ViaticoExport;
use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\ViaticoResource;
use App\Models\FondosRotativos\Viatico\DetalleViatico;
use App\Models\FondosRotativos\Viatico\EstadoViatico;
use App\Models\FondosRotativos\Viatico\Viatico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Excel;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Excel as ExcelExcel;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class ViaticoController extends Controller
{
    private $entidad = 'viatico';
    public function __construct()
    {
        $this->middleware('can:puede.ver.fondo')->only('index', 'show');
        $this->middleware('can:puede.crear.fondo')->only('store');
        $this->middleware('can:puede.editar.fondo')->only('update');
        $this->middleware('can:puede.eliminar.fondo')->only('update');
    }
     /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $results = [];

        $results = Viatico::ignoreRequest(['campos'])->with('detalle_info','aut_especial_user','estado_info')->filter()->get();
        $results = ViaticoResource::collection($results);

        return response()->json(compact('results'));
    }
      /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Viatico  $viatico
     * @return \Illuminate\Http\Response
     */
    public function store (Request $request)
    {

        $datos = $request->all();
        $user = Auth::user();
        $usuario_autorizado = User::where('id', $request->aut_especial)->first();
        $datos_detalle = DetalleViatico::where('id', $request->detalle)->first();
        $saldo_consumido_viatico=0;
        if($datos_detalle->descripcion==''){
            if($datos_detalle->autorizacion=='SI'){
                $datos_estatus_via= EstadoViatico::where('descripcion','POR APROBAR')->first();
            }
            else{
                $datos_estatus_via= EstadoViatico::where('descripcion','APROBADO')->first();
                $saldo_consumido_viatico=(float)$saldo_consumido_viatico+(float)$request->total;
            }

        }else{
            if($datos_detalle->autorizacion=='SI'){
                $datos_estatus_via= EstadoViatico::where('descripcion','POR APROBAR')->first();
            }
            else{
                $datos_estatus_via= EstadoViatico::where('descripcion','APROBADO')->first();
                $saldo_consumido_viatico=(float)$saldo_consumido_viatico+(float)$request->total;
            }
        }

        //Adaptacion de foreign keys
        $datos['id_lugar'] = $request->lugar;
        $datos['id_usuario'] = $usuario_autorizado->id;
        $datos['fecha_ingreso']= date('Y-m-d');
        $datos['transcriptor'] = $user->name;
        $datos['estado'] = $datos_estatus_via->id;
        $datos['cantidad'] = $request->cant;
        //Convierte base 64 a url
        if ($request->comprobante1 != null) $datos['comprobante'] = (new GuardarImagenIndividual($request->comprobante1, RutasStorage::COMPROBANTES_VIATICOS))->execute();
        if ($request->comprobante2 != null) $datos['comprobante2'] = (new GuardarImagenIndividual($request->comprobante2, RutasStorage::COMPROBANTES_VIATICOS))->execute();
        //Guardar Registro
        $modelo = Viatico::create($datos);
        $modelo = new ViaticoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }

     /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Viatico  $viatico
     * @return \Illuminate\Http\Response
     */
    public function update(Viatico $request, Viatico $activo)
    {
        //Adaptacion de foreign keys
        $datos = $request->all();
        $user = Auth::user();
        $usuario_autorizado = User::where('id', $request->aut_especial)->first();
        $datos_detalle = DetalleViatico::where('id', $request->detalle)->first();
        $saldo_consumido_viatico=0;
        if($datos_detalle->descripcion==''){
            if($datos_detalle->autorizacion=='SI'){
                $datos_estatus_via= EstadoViatico::where('descripcion','POR APROBAR')->first();
            }
            else{
                $datos_estatus_via= EstadoViatico::where('descripcion','APROBADO')->first();
                $saldo_consumido_viatico=(float)$saldo_consumido_viatico+(float)$request->total;
            }

        }else{
            if($datos_detalle->autorizacion=='SI'){
                $datos_estatus_via= EstadoViatico::where('descripcion','POR APROBAR')->first();
            }
            else{
                $datos_estatus_via= EstadoViatico::where('descripcion','APROBADO')->first();
                $saldo_consumido_viatico=(float)$saldo_consumido_viatico+(float)$request->total;
            }
        }
        //Adaptacion de foreign keys
        $datos['id_lugar'] = $request->lugar;
        $datos['id_usuario'] = $usuario_autorizado->id;
        $datos['fecha_ingreso']= date('Y-m-d');
        $datos['transcriptor'] = $user->name;
        $datos['estado'] = $datos_estatus_via->id;
        $datos['cantidad'] = $request->cant;

        //Respuesta
        $activo->update($datos);
        $modelo = new ViaticoResource($activo->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('modelo', 'mensaje'));
    }

    public function show(Viatico $viatico)
    {
        $modelo = new ViaticoResource($viatico);
        return response()->json(compact('modelo'), 200);
    }

    public function destroy(Viatico $viatico)
    {
        $viatico->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));

    }
    public function generar_reporte(Request $request,$tipo){
        switch ($tipo) {
            case 'excel':
                return Excel::download(new ViaticoExport( $request->fecha_inicio, $request->fecha_fin), 'fondo_fecha.xlsx');
                break;
            default:
                # code...
                break;
        }

    }
    public function generar_reporte_prueba($tipo){
        switch ($tipo) {
            case 'excel':
              return Excel::download(new ViaticoExport( '2023-02-01', '2023-02-25'), 'fondo_fecha.xlsx');
                break;
            default:
                # code...
                break;
        }

    }

}
