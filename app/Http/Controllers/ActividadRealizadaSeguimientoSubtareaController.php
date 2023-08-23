<?php

namespace App\Http\Controllers;

use App\Http\Requests\ActividadRealizadaSeguimientoSubtareaRequest;
use App\Http\Resources\ActividadRealizadaSeguimientoSubtareaResource;
use App\Models\ActividadRealizadaSeguimientoSubtarea;
use Carbon\Carbon;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class ActividadRealizadaSeguimientoSubtareaController extends Controller
{
    private $entidad = 'Actividad';

    public function index()
    {
        $results = ActividadRealizadaSeguimientoSubtarea::filter()->latest()->get();
        $results = ActividadRealizadaSeguimientoSubtareaResource::collection($results);
        return response()->json(compact('results'));
    }

    public function store(ActividadRealizadaSeguimientoSubtareaRequest $request)
    {
        $datos = $request->validated();
        $datos['subtarea_id'] = $datos['subtarea'];

        if ($datos['fotografia']) $datos['fotografia'] = (new GuardarImagenIndividual($datos['fotografia'], RutasStorage::SEGUIMIENTO))->execute();

        $modelo = new ActividadRealizadaSeguimientoSubtarea();
        $datos['fecha_hora'] = Carbon::parse($datos['fecha_hora'])->format('Y-m-d H:i:s');
        $modelo->fill($datos);
        $modelo->save();

        $modelo = new ActividadRealizadaSeguimientoSubtareaResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }
}
