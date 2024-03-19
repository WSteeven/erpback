<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\DiagnosticoRecetaRequest;
use App\Http\Resources\Medico\DiagnosticoRecetaResource;
use App\Models\Medico\DiagnosticoCitaMedica;
use App\Models\Medico\Receta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Src\Shared\Utils;
use Exception;
use Illuminate\Validation\ValidationException;

class DiagnosticoRecetaController extends Controller
{
    private $entidad = 'CIE';

    public function store(DiagnosticoRecetaRequest $request)
    {
        try {
            $datos = $request->validated();

            DB::beginTransaction();

            $datos = $request->validated();

            Receta::create([
                'rp' => $datos['rp'],
                'prescripcion' => $datos['prescripcion'],
            ]);

            foreach ($datos['diagnosticos'] as $diagnostico) {
                DiagnosticoCitaMedica::create($diagnostico);
            }

            $modelo = new DiagnosticoRecetaResource($datos);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de cie' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
