<?php

namespace App\Http\Controllers\FondosRotativos\Gasto;

use App\Http\Controllers\Controller;
use App\Http\Requests\SubdetalleViaticaRequest;
use App\Http\Resources\FondosRotativos\Gastos\SubDetalleViaticoResource;
use App\Models\FondosRotativos\Usuario\Estatus;
use App\Models\FondosRotativos\Gasto\SubDetalleViatico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
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
        $results = SubDetalleViatico::with('estatus', 'detalle')->ignoreRequest(['campos'])->filter()->get();
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
        $subDetalleViatico = SubDetalleViatico::where('id', $id)->first();
        $modelo = new SubDetalleViaticoResource($subDetalleViatico);
        return response()->json(compact('modelo'), 200);
    }
    /**
     * It takes a request, validates it, creates a new model, and returns a response
     *
     * @param Request request The request object.
     */
    public function store(SubdetalleViaticaRequest $request)
    {
        $datos = $request->validated();
        $user = Auth::user();
        $estatus = Estatus::where('descripcion', $request->estatus)->first();
        $datos['autorizacion'] = $request->autorizacion;
        $datos['id_detalle_viatico'] = $request->detalle_viatico;
        $datos['id_estatus'] = $estatus->id;
        $datos['descripcion'] = $request->descripcion;
        $modelo = SubDetalleViatico::create($datos);
        $modelo = new SubDetalleViaticoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }
    /**
     * La función actualiza un modelo SubDetalleViatico con datos de una solicitud validada y devuelve una
     * respuesta JSON con un mensaje y el modelo actualizado.
     *
     * @param SubdetalleViaticaRequest request El parámetro `SubdetalleViaticaRequest ` en la
     * función `update` es una instancia de la clase `SubdetalleViaticaRequest`. Este parámetro se utiliza
     * para manejar los datos de la solicitud entrante y realizar la validación de esos datos.
     * @param SubDetalleViatico subdetalle_viatico La función "actualizar" que proporcionó parece estar
     * actualizando una instancia del modelo "SubDetalleViatico" en función de los datos de una solicitud
     * "SubdetalleViaticaRequest".
     *
     * @return El método `update` devuelve una respuesta JSON que contiene las variables `` y
     * ``. La variable `mensaje` es el mensaje obtenido del método `Utils::obtenerMensaje` para la
     * acción 'actualizar' sobre la entidad. La variable `modelo` contiene el modelo ``
     * actualizado.
     */
    public function update(SubdetalleViaticaRequest $request, SubDetalleViatico  $subdetalle_viatico)
    {
        $subdetalle_viatico = SubDetalleViatico::find($request->id);
        Log::channel('testing')->info('Log', ['error', $subdetalle_viatico]);

        $datos = $request->validated();
        // Respuesta
        $subdetalle_viatico->update($datos);
        $modelo = $subdetalle_viatico;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

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
