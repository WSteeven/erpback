<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Gastos\SubDetalleViaticoResource;
use App\Models\FondosRotativos\Usuario\Estatus;
use App\Models\FondosRotativos\Gasto\SubDetalleViatico;
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
   /**
    * A function that is used to delete a record from the database.
    *
    * @param Request request The request object.
    *
    * @return The response is a JSON object with the following properties:
    */
    public function index(Request $request)
    {
        $results = [];
        $results = SubDetalleViatico::with('estatus','detalle')->ignoreRequest(['campos'])->filter()->get();
        $results = SubDetalleViaticoResource::collection($results);
        return response()->json(compact('results'));
    }
  /**
   * *|CURSOR_MARCADOR|*
   *
   * @param id The id of the subDetalleViatico you want to show.
   *
   * @return A JSON object with the data of the subDetalleViatico.
   */
    public function show($id)
    {
        $subDetalleViatico = SubDetalleViatico::where('id',$id)->first();
        $modelo = new SubDetalleViaticoResource($subDetalleViatico);
        return response()->json(compact('modelo'), 200);
    }
   /**
    * It takes a request, validates it, creates a new model, and returns a response
    *
    * @param Request request The request object.
    */
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


   /**
    * *|CURSOR_MARCADOR|*
    *
    * @param SubDetalleViatico subDetalleViatico The model instance that will be deleted.
    */
    public function destroy(SubDetalleViatico $subDetalleViatico)
    {
        $subDetalleViatico->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
