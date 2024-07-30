<?php

namespace App\Http\Controllers\Intranet;

use App\Http\Controllers\Controller;
use App\Http\Requests\Intranet\NoticiaRequest;
use App\Http\Resources\Intranet\NoticiaResource;
use App\Models\Intranet\Noticia;
use Illuminate\Http\Request;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class NoticiaController extends Controller
{
    private $entidad = 'Noticia';
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
     * @return \Illuminate\Http\Response
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
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NoticiaRequest $request)
    {
         //Respuesta
         $datos = $request->validated();

        if ($datos['imagen_noticia']) {
            $datos['imagen_noticia'] = (new GuardarImagenIndividual($datos['imagen_noticia'], RutasStorage::IMAGENES_NOTICIAS))->execute();
        }

         $modelo = Noticia::create($datos);
         $modelo = new NoticiaResource($modelo);
         $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

         return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Noticia $noticia)
    {
        $modelo = new NoticiaResource($noticia);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(NoticiaRequest $request, Noticia $noticia)
    {
        //Respuesta
        $datos = $request->validated();

        if ($datos['imagen_noticia'] && Utils::esBase64($datos['imagen_noticia'])) {
            $datos['imagen_noticia'] = (new GuardarImagenIndividual($datos['imagen_noticia'], RutasStorage::IMAGENES_NOTICIAS))->execute();
        } else {
            unset($datos['imagen_noticia']);
        }
        $noticia->update($datos);
        $modelo = new NoticiaResource($noticia->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Noticia $noticia)
    {
        $noticia->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
