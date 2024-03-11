<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Http\Requests\Ventas\NovedadVentaRequest;
use App\Http\Resources\Ventas\NovedadVentaResource;
use App\Models\Ventas\NovedadVenta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class NovedadVentaController extends Controller
{
    private $entidad = 'Novedad';

    public function __construct()
    {
    }

    public function index()
    {
        $results = NovedadVenta::filter()->latest()->get();
        $results = NovedadVentaResource::collection($results);

        return response()->json(compact('results'));
    }

    public function store(NovedadVentaRequest $request)
    {
        $datos = $request->validated();
        $datos['venta_id'] = $datos['venta'];

        if ($datos['fotografia']) $datos['fotografia'] = (new GuardarImagenIndividual($datos['fotografia'], RutasStorage::FOTOGRAFIAS_NOVEDADES_VENTAS_CLARO))->execute();

        $modelo = new NovedadVenta();
        $datos['fecha_hora'] = Carbon::parse($datos['fecha_hora'])->format('Y-m-d H:i:s');
        $modelo->fill($datos);
        $modelo->save();

        $modelo = new NovedadVentaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }
}
