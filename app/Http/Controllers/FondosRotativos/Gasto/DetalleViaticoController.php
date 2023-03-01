<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Gastos\DetalleViaticoResource;
use App\Models\FondosRotativos\Usuario\Estatus;
use App\Models\FondosRotativos\Gasto\DetalleViatico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Utils;

class DetalleViaticoController extends Controller
{
    private $entidad = 'detalle_gasto';
    public function __construct()
    {
        $this->middleware('can:puede.ver.detalle_fondo')->only('index', 'show');
        $this->middleware('can:puede.crear.detalle_fondo')->only('store');
        $this->middleware('can:puede.editar.detalle_fondo')->only('update');
        $this->middleware('can:puede.eliminar.detalle_fondo')->only('update');
    }
   /**
    * It returns a list of all the detalle_gastos in the database.
    *
    * @param Request request The request object.
    *
    * @return A collection of DetalleViaticoResource
    */
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];

        $results = DetalleViatico::with('estatus')->ignoreRequest(['campos'])->filter()->get();
        $results = DetalleViaticoResource::collection($results);
        return response()->json(compact('results'));
    }
    /**
     * It returns the data of a specific record.
     *
     * @param id The id of the resource you want to retrieve.
     *
     * @return A JSON object with the data of the model.
     */
    public function show($id)
    {
        $detalleViatico = DetalleViatico::with('estatus')->where('id', $id)->first();
        $modelo = new DetalleViaticoResource($detalleViatico);
        return response()->json(compact('modelo'), 200);
    }
   /**
    * It creates a new record in the database.
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
        $estatus = Estatus::where('descripcion','like', '%'.$request->estatus.'%')->first();
        $datos['autorizacion'] = $request->autorizacion;
        $datos['transcriptor']= $user->name;
        $datos['id_estatus'] = $estatus->id;
        $datos['descripcion'] = $request->descripcion;
        $datos['fecha_trans'] =date('Y-m-d');

        $modelo = DetalleViatico::create($datos);
        $modelo = new DetalleViaticoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }


    /**
     * It deletes a record from the database
     *
     * @param DetalleViatico gasto The model instance that you want to delete.
     */
    public function destroy(DetalleViatico $gasto)
    {
        $gasto->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));

    }
}
