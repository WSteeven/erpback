<?php

namespace App\Http\Controllers;

use App\Http\Requests\ConfiguracionGeneralRequest;
use App\Http\Resources\ConfiguracionGeneralResource;
use App\Models\ConfiguracionGeneral;
use Illuminate\Http\Request;
use Src\Shared\Utils;

class ConfiguracionGeneralController extends Controller
{
    private $entidad = 'Configuración General';

    public function __construct()
    {
        $this->middleware('can:puede.ver.configuracion_general')->only('index', 'show');
        $this->middleware('can:puede.editar.configuracion_general')->only('store');
    }

    /**
     * Listar
     */
    public function index()
    {
        $results = ConfiguracionGeneral::first();
        return response()->json(compact('results'));
    }

    /**
     * Consultar
     */
    public function show(ConfiguracionGeneral $configuracion)
    {
        $modelo = new ConfiguracionGeneralResource($configuracion);
        return response()->json(compact('modelo'));
    }

    /**
     * actualizar
     */
    public function store(ConfiguracionGeneralRequest $request)
    {
        $datos = $request->validated();

        $configuracion = ConfiguracionGeneral::first();

        if ($configuracion) $configuracion->update($datos);
        $configuracion = ConfiguracionGeneral::create($datos);

        $modelo = new ConfiguracionGeneralResource($configuracion->refresh());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }
}
