<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfiguracionGeneralRequest;
use App\Http\Resources\ConfiguracionGeneralResource;
use App\Models\ConfiguracionGeneral;
use Illuminate\Http\Request;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;
use Illuminate\Support\Facades\Url;

class ConfiguracionGeneralController extends Controller
{
    private $entidad = 'Configuración General';

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
     */
    public function store(ConfiguracionGeneralRequest $request)
    {
        $datos = $request->validated();

        $configuracion = ConfiguracionGeneral::first();

        if ($configuracion) {

            if ($datos['logo_claro']) $datos['logo_claro'] = $this->guardarImagen($datos['logo_claro'], 'logo_claro');
            if ($datos['logo_oscuro']) $datos['logo_oscuro'] = $this->guardarImagen($datos['logo_oscuro'], 'logo_oscuro');
            if ($datos['logo_marca_agua']) $datos['logo_marca_agua'] = $this->guardarImagen($datos['logo_marca_agua'], 'logo_marca_agua');

            $configuracion->update($datos);
        } else {
            if ($datos['logo_claro']) $datos['logo_claro'] = $this->guardarImagen($datos['logo_claro'], 'logo_claro');
            if ($datos['logo_oscuro']) $datos['logo_oscuro'] = $this->guardarImagen($datos['logo_oscuro'], 'logo_oscuro');
            if ($datos['logo_marca_agua']) $datos['logo_marca_agua'] = $this->guardarImagen($datos['logo_marca_agua'], 'logo_marca_agua');

            $configuracion = ConfiguracionGeneral::create($datos);
        }

        $modelo = new ConfiguracionGeneralResource($configuracion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    private function guardarImagen($imagen, $nombre_predeterminado)
    {
        $timestamp = time();
        if ($imagen) {
            if (Utils::esBase64($imagen)) {
                $nombre = $timestamp . '_' . $nombre_predeterminado;
                return (new GuardarImagenIndividual($imagen, RutasStorage::CONFIGURACION_GENERAL, $nombre))->execute();
            } else {
                $componentesUrl = parse_url($imagen);
                return $componentesUrl['path'];
            }
        }
    }
}
