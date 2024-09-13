<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Http\Controllers\Controller;
use App\Http\Requests\AcreditacionRequest;
use App\Http\Resources\FondosRotativos\Saldo\AcreditacionResource;
use App\Models\FondosRotativos\Saldo\Acreditaciones;
use App\Models\FondosRotativos\Saldo\EstadoAcreditaciones;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;
use Throwable;

class AcreditacionesController extends Controller
{
    private string $entidad = 'Acreditacion';
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
     * @return JsonResponse
     * @noinspection PhpUndefinedMethodInspection
     */
    public function index()
    {
        $results = Acreditaciones::with('usuario', 'estado')->ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = AcreditacionResource::collection($results);
        return response()->json(compact('results'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param AcreditacionRequest $request
     * @return JsonResponse
     * @throws ValidationException|Throwable
     * @noinspection PhpUndefinedMethodInspection
     */
    public function store(AcreditacionRequest $request)
    {
        DB::beginTransaction();
        try {
            $datos = $request->validated();
            $acreditacion = Acreditaciones::create($datos);
            $modelo = new AcreditacionResource($acreditacion);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param Acreditaciones $acreditacion
     * @return JsonResponse
     */
    public function show(Acreditaciones $acreditacion)
    {
        $modelo = new AcreditacionResource($acreditacion);
        return response()->json(compact('modelo'));
    }

    /**
     * La función `anularAcreditacion` anula la acreditacion
     * basado en los datos de la solicitud proporcionados.
     *
     * @param Request $request La función `anularAcreditacion` toma como parámetro un objeto de solicitud.
     * Es probable que este objeto de solicitud contenga datos necesarios para procesar la solicitud, como
     * el ID de la acreditación que se cancelará y una descripción del motivo de la cancelación.
     *
     * @return JsonResponse función `anularAcreditacion` está devolviendo una respuesta JSON con un mensaje
     * almacenado en la variable ``. El mensaje se obtiene mediante el método `obtenerMensaje` de
     * la clase `Utils` con los parámetros `->entidad` y `'update'`. La respuesta JSON incluye el
     * mensaje en la clave `mensaje`.
     * @throws ValidationException|Throwable
     * @noinspection PhpUndefinedMethodInspection
     */
    public function anularAcreditacion(Request $request)
    {
        DB::beginTransaction();
        try {
            $acreditacion_repetida = Acreditaciones::where('id_estado',  EstadoAcreditaciones::ANULADO)->where('id', $request->id)->lockForUpdate()->get();
            if ($acreditacion_repetida->count() > 0) {
                throw ValidationException::withMessages([
                    '404' => ['Acreditación  ya fue anulada'],
                ]);
            }
            $acreditacion = Acreditaciones::find($request->id);
            $acreditacion->motivo =  $request->descripcion_acreditacion;
            $acreditacion->id_estado = EstadoAcreditaciones::ANULADO;
            $acreditacion->save();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al anular registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param AcreditacionRequest $request
     * @param Acreditaciones $acreditacion
     * @return JsonResponse
     */
    public function update(AcreditacionRequest $request,Acreditaciones $acreditacion)
    {
        $datos = $request->validated();
        $modelo = $acreditacion->update($datos);
        $modelo = new AcreditacionResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Acreditaciones $acreditacion
     * @return Response
     */
//    public function destroy(Acreditaciones $acreditacion)
//    {
//        $acreditacion = Acreditaciones::findOrFail($id);
//        $acreditacion->delete();
//        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
//        return response()->json(compact('mensaje'));
//    }
}
