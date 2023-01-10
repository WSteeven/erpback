<?php

namespace App\Http\Controllers;

use App\Http\Resources\RegistroTendidoResource;
use App\Models\ControlMaterialSubtarea;
use App\Models\MaterialGrupoTarea;
use App\Models\RegistroTendido;
use App\Models\Subtarea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Src\App\ControlMaterialSubtareaService;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;
use Src\Shared\Utils;

class RegistroTendidoController extends Controller
{
    private $entidad = 'Registro tendido';
    private $controlMaterialSubtareaService;

    public function __construct()
    {
        $this->controlMaterialSubtareaService = new ControlMaterialSubtareaService();
    }

    /**
     * Listar
     */
    public function index()
    {
        $tendido = request('tendido');

        $results = RegistroTendidoResource::collection(RegistroTendido::where('tendido_id', $tendido)->get());
        return response()->json(compact('results'));
    }

    /**
     * Guardar
     */
    public function store(Request $request)
    {
        $datos = $request->all();
        $datos['tendido_id'] = $datos['tendido'];
        $datos['subtarea_id'] = $datos['subtarea'];
        $materialesOcupados = $datos['materiales_ocupados'];

        // Validar el stock disponible
        $this->controlMaterialSubtareaService->verificarDisponibleStock($materialesOcupados);
        $this->controlMaterialSubtareaService->computarMaterialesOcupados($materialesOcupados);

        // Las cantidades se validaron y se puede proceder al registro de materiales
        foreach ($materialesOcupados as $material) {
            $subtarea = Subtarea::find($datos['subtarea_id']);

            $material['subtarea_id'] = $datos['subtarea_id'];
            $material['tarea_id'] = $subtarea->tarea->id;
            $material['grupo_id'] = $subtarea->grupo->id;
            $material['fecha'] = Carbon::now()->format('d-m-Y');
            ControlMaterialSubtarea::create($material);
        }

        // Guardar imagenes        
        $datos['imagen_elemento'] = (new GuardarImagenIndividual($datos['imagen_elemento'], RutasStorage::REGISTROS_TENDIDOS))->execute();

        // Log::channel('testing')->info('Log', ['Existe imahgen de cruce americano', $request['imagen_cruce_americano']]);
        if ($datos['imagen_cruce_americano'])
            $datos['imagen_cruce_americano'] = (new GuardarImagenIndividual($datos['imagen_cruce_americano'], RutasStorage::REGISTROS_TENDIDOS))->execute();    

        if ($datos['imagen_poste_anclaje1'])
            $datos['imagen_poste_anclaje1'] = (new GuardarImagenIndividual($datos['imagen_poste_anclaje1'], RutasStorage::REGISTROS_TENDIDOS))->execute();

        if ($datos['imagen_poste_anclaje2'])
            $datos['imagen_poste_anclaje2'] = (new GuardarImagenIndividual($datos['imagen_poste_anclaje2'], RutasStorage::REGISTROS_TENDIDOS))->execute();

        $modelo = new RegistroTendidoResource(RegistroTendido::create($datos)); 
        return response()->json(compact('modelo'));
    }

    /**
     * Consultar
     */
    public function show(RegistroTendido $registroTendido)
    {
        $modelo = new RegistroTendidoResource($registroTendido);
        return response()->json(compact('modelo'));
    }

    /**
     * Actualizar
     */
    public function update(Request $request, RegistroTendido $registroTendido)
    {
        $registroTendido->update($request->all());
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
        $modelo = $registroTendido;
        return response()->json(compact('mensaje', 'modelo'));
    }
}
