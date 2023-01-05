<?php

namespace App\Http\Controllers;

use App\Models\ControlMaterialSubtarea;
use App\Models\MaterialGrupoTarea;
use App\Models\RegistroTendido;
use App\Models\Subtarea;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Src\App\ControlMaterialSubtareaService;
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
        $results = RegistroTendido::all();
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

        $datos['materiales_ocupados'] = $datos['materiales_ocupados'];

        $modelo = RegistroTendido::create($datos);

        return response()->json(compact('modelo'));
    }

    /**
     * Consultar
     */
    public function show(RegistroTendido $registroTendido)
    {
        $modelo = $registroTendido;
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
