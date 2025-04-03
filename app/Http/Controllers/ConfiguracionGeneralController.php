<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfiguracionGeneralRequest;
use App\Http\Resources\ConfiguracionGeneralResource;
use App\Models\ConfiguracionGeneral;
use Illuminate\Support\Facades\Log;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Throwable;

class ConfiguracionGeneralController extends Controller
{

    public function __construct()
    {
        // $this->middleware('can:puede.ver.configuracion_general')->only('index', 'show');
        $this->middleware('can:puede.editar.configuracion_general')->only('store');
    }

    /**
     * Listar
     */
    public function index()
    {
        $configuracion = ConfiguracionGeneral::first();
        $results = $configuracion ? ConfiguracionGeneralResource::collection([$configuracion]) : [];
        return response()->json(compact('results'));
    }

    /**
     * actualizar
     * @throws Throwable
     */
    public function store(ConfiguracionGeneralRequest $request)
    {
        $datos = $request->validated();
        $configuracion = ConfiguracionGeneral::first();

        if ($datos['logo_claro']) $datos['logo_claro'] = $this->guardarImagen($datos['logo_claro'], 'logo_claro', $configuracion?->logo_claro);
        if ($datos['logo_oscuro']) $datos['logo_oscuro'] = $this->guardarImagen($datos['logo_oscuro'], 'logo_oscuro', $configuracion?->logo_oscuro);
        if ($datos['logo_marca_agua']) $datos['logo_marca_agua'] = $this->guardarImagen($datos['logo_marca_agua'], 'logo_marca_agua', $configuracion?->logo_marca_agua);

        if ($configuracion) {
            $configuracion->update($datos);
        } else {
            ConfiguracionGeneral::create($datos);
        }

        /* $modelo = new ConfiguracionGeneralResource($configuracion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');*/
        $mensaje = 'Actualizado exitosamente!';

        return response()->json(compact('mensaje'));
    }

    /**
     * @throws Throwable
     */
    private function guardarImagen($imagen, $nombre_predeterminado, $ruta_a_eliminar = null)
    {
        $timestamp = time();
        if ($imagen) {
            if (Utils::esBase64($imagen)) {
                $nombre = $timestamp . '_' . $nombre_predeterminado;
                return (new GuardarImagenIndividual($imagen, RutasStorage::CONFIGURACION_GENERAL,  $ruta_a_eliminar, $nombre))->execute();
            } else {
                $componentesUrl = parse_url($imagen);
                return $componentesUrl['path'];
            }
        }
    }
}
