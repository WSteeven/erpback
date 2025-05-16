<?php

namespace App\Http\Controllers\Bodega;

use App\Http\Controllers\Controller;
use App\Http\Requests\Bodega\PermisoArmaRequest;
use App\Http\Resources\Bodega\PermisoArmaResource;
use App\Models\Bodega\PermisoArma;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\EliminarImagen;
use Src\Shared\Utils;

class PermisoArmaController extends Controller
{
    private $entidad = 'Permiso';
    public function __construct()
    {
        $this->middleware('can:puede.ver.permisos_armas')->only('index', 'show');
        $this->middleware('can:puede.crear.permisos_armas')->only('store');
        $this->middleware('can:puede.editar.permisos_armas')->only('update');
        $this->middleware('can:puede.eliminar.permisos_armas')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = PermisoArma::filter()->get();
        $results = PermisoArmaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermisoArmaRequest $request)
    {
        //Respuesta
        $datos = $request->validated();
        if ($datos['imagen_permiso']) {
            $datos['imagen_permiso'] = (new GuardarImagenIndividual($datos['imagen_permiso'], RutasStorage::IMAGENES_PERMISOS_ARMAS))->execute();
        }
        if ($datos['imagen_permiso_reverso']) {
            $datos['imagen_permiso_reverso'] = (new GuardarImagenIndividual($datos['imagen_permiso_reverso'], RutasStorage::IMAGENES_PERMISOS_ARMAS))->execute();
        }
        $modelo = PermisoArma::create($datos);
        $modelo = new PermisoArmaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(PermisoArma $permiso)
    {
        $modelo = new PermisoArmaResource($permiso);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PermisoArmaRequest $request, PermisoArma $permiso)
    {
        //Respuesta
        $datos = $request->validated();
        if ($datos['imagen_permiso'] && Utils::esBase64($datos['imagen_permiso'])) {
            $datos['imagen_permiso'] = (new GuardarImagenIndividual($datos['imagen_permiso'], RutasStorage::IMAGENES_PERMISOS_ARMAS, $permiso->imagen_permiso))->execute();
        } else {
            unset($datos['imagen_permiso']);
        }
        if ($datos['imagen_permiso_reverso'] && Utils::esBase64($datos['imagen_permiso_reverso'])) {
            $datos['imagen_permiso_reverso'] = (new GuardarImagenIndividual($datos['imagen_permiso_reverso'], RutasStorage::IMAGENES_PERMISOS_ARMAS, $permiso->imagen_permiso_reverso))->execute();
        } else {
            unset($datos['imagen_permiso_reverso']);
        }
        $permiso->update($datos);
        $modelo = new PermisoArmaResource($permiso->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(PermisoArma $permiso)
    {
        try {
            // throw new Exception('Este método no está definido. Por favor comunicate con el departamento de informática para más información.');
            $ruta_imagen_permiso = str_replace('storage', 'public', $permiso['imagen_permiso']);
            $ruta_imagen_permiso_reverso = str_replace('storage', 'public', $permiso['imagen_permiso_reverso']);
            Storage::delete([$ruta_imagen_permiso, $ruta_imagen_permiso_reverso]);

            $permiso->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            return response()->json(compact('mensaje', ));
        } catch (\Throwable $th) {
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
    }
}
