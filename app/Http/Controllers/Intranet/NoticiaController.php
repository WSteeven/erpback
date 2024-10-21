<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Intranet\NoticiaRequest;
use App\Http\Resources\Intranet\NoticiaResource;
use App\Models\Intranet\Noticia;
use DB;
use Illuminate\Http\JsonResponse;
use Mockery\Exception;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class NoticiaController extends Controller
{
    private string $entidad = 'Noticia';

    public function __construct()
    {
        $this->middleware('can:puede.ver.intra_noticias')->only('index', 'show');
        $this->middleware('can:puede.crear.intra_noticias')->only('store');
        $this->middleware('can:puede.editar.intra_noticias')->only('update');
        $this->middleware('can:puede.eliminar.intra_noticias')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $results = Noticia::ignoreRequest(['estado'])->filter()->orderBy('titulo', 'desc')->get();
        $results = NoticiaResource::collection($results);

        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NoticiaRequest $request
     * @return JsonResponse
     */
    public function store(NoticiaRequest $request)
    {
        try {
            DB::beginTransaction();
            //Respuesta
            $datos = $request->validated();

            if ($datos['imagen_noticia']) {
                $datos['imagen_noticia'] = (new GuardarImagenIndividual($datos['imagen_noticia'], RutasStorage::IMAGENES_NOTICIAS))->execute();
            }

            // Convertir array de departamentos en cadena separada por comas
            if (isset($datos['departamentos_destinatarios'])) {
                $datos['departamentos_destinatarios'] = implode(',', $datos['departamentos_destinatarios']);
            }

            $modelo = Noticia::create($datos);
            $modelo = new NoticiaResource($modelo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw  Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param Noticia $noticia
     * @return JsonResponse
     */
    public function show(Noticia $noticia)
    {
        $modelo = new NoticiaResource($noticia);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param NoticiaRequest $request
     * @param Noticia $noticia
     * @return JsonResponse
     */
    public function update(NoticiaRequest $request, Noticia $noticia)
    {
        try {
            DB::beginTransaction();
            //Respuesta
            $datos = $request->validated();

            if ($datos['imagen_noticia'] && Utils::esBase64($datos['imagen_noticia'])) {
                $datos['imagen_noticia'] = (new GuardarImagenIndividual($datos['imagen_noticia'], RutasStorage::IMAGENES_NOTICIAS))->execute();
            } else {
                unset($datos['imagen_noticia']);
            }

            // Convertir array de departamentos en cadena separada por comas
            if (isset($datos['departamentos_destinatarios'])) {
                $datos['departamentos_destinatarios'] = implode(',', $datos['departamentos_destinatarios']);
            }

            $noticia->update($datos);
            $modelo = new NoticiaResource($noticia->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (Throwable $th) {
            DB::rollBack();
            throw  Utils::obtenerMensajeErrorLanzable($th);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Noticia $noticia
     * @return JsonResponse
     * @throws Throwable
     */
    public function destroy(Noticia $noticia)
    {
        //        $noticia->delete();
        //        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        try {
            throw new Exception('Método no definido, comunicate con el departamento informático para más información');
        } catch (Throwable $th) {
            throw  Utils::obtenerMensajeErrorLanzable($th);
        }
        //        return response()->json(compact('mensaje'));
    }
}
