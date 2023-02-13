<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\SubDetalleViaticoResource;
use App\Models\FondosRotativos\Usuario\Estatus;
use App\Models\FondosRotativos\Viatico\SubDetalleViatico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Utils;

class SubDetalleViaticoController extends Controller
{
    private $entidad = 'sub_detalle_viatico';
    public function __construct()
    {
        $this->middleware('can:puede.ver.sub_detalle_fondo')->only('index', 'show');
        $this->middleware('can:puede.crear.sub_detalle_fondo')->only('store');
        $this->middleware('can:puede.editar.sub_detalle_fondo')->only('update');
        $this->middleware('can:puede.eliminar.sub_detalle_fondo')->only('update');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = SubDetalleViatico::with('estatus','detalle')->ignoreRequest(['campos'])->filter()->get();
        $results = SubDetalleViaticoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show($id)
    {
        $subDetalleViatico = SubDetalleViatico::where('id',$id)->first();
        $modelo = new SubDetalleViaticoResource($subDetalleViatico);
        return response()->json(compact('modelo'), 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required',
            'autorizacion' => 'required',
            'estatus' => 'required',
        ]);
        $user = Auth::user();
        $estatus = Estatus::where('descripcion', $request->estatus)->first();
        $datos['autorizacion'] = $request->autorizacion;
        $datos['id_detalle_viatico']= $request->detalle_viatico;
        $datos['transcriptor']= $user->name;
        $datos['id_estatus'] = $estatus->id;
        $datos['descripcion'] = $request->descripcion;
        $datos['fecha_trans'] =date('Y-m-d');

        $modelo = SubDetalleViatico::create($datos);
        $modelo = new SubDetalleViaticoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }


    public function destroy(SubDetalleViatico $subDetalleViatico)
    {
        $subDetalleViatico->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
