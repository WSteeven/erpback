<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcreditacionRequest;
use App\Http\Resources\FondosRotativos\Saldo\AcreditacionResource;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Gasto\EstadoGasto;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;

class AcreditacionesController extends Controller
{
    private $entidad = 'Acreditacion';
    public function __construct()
    {
        $this->middleware('can:puede.ver.acreditacion')->only('index', 'show');
        $this->middleware('can:puede.crear.acreditacion')->only('store');
        $this->middleware('can:puede.editar.acreditacion')->only('update');
        $this->middleware('can:puede.puede.eliminar.acreditacion')->only('update');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = Acreditaciones::with('usuario')->ignoreRequest(['campos'])->filter()->get();
        $results = AcreditacionResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\AcreditacionRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(AcreditacionRequest $request)
    {
        try {
            $datos = $request->validated();
            $datos_usuario_add_saldo = User::where('id', $request->usuario)->first();
            //Adaptacion de campos
            $datos['id_tipo_fondo'] =  $request->safe()->only(['tipo_fondo'])['tipo_fondo'];
            $datos['id_tipo_saldo'] =  $request->safe()->only(['tipo_saldo'])['tipo_saldo'];
            $datos['id_usuario'] =     $request->safe()->only(['usuario'])['usuario'];
            Log::channel('testing')->info('Log', ['datos', $datos]);
            $modelo = Acreditaciones::create($datos);
            $modelo = new AcreditacionResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['ERROR en el insert de gasto', $e->getMessage(), $e->getLine()]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $acreditacion = Acreditaciones::findOrFail($id);
        $modelo = new AcreditacionResource($acreditacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $acreditacion = Acreditaciones::findOrFail($id);
        $datos_usuario_add_saldo = User::where('id', $request->usuario)->first();
        //Adaptacion de campos
        $datos = $request->all();
        $datos['id_tipo_fondo'] = $request->tipo_fondo;
        $datos['id_tipo_saldo'] = $request->tipo_saldo;
        $datos['id_usuario'] = $request->usuario;
        $datos['fecha'] = date('Y-m-d H:i:s', strtotime($request->fecha));
        $modelo = $acreditacion->update($datos);
        $modelo = new AcreditacionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $acreditacion = Acreditaciones::findOrFail($id);
        $acreditacion->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
