<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\CuestionarioPublicoRequest;
use App\Http\Resources\Medico\CuestionarioPublicoResource;
use App\Models\Medico\Cuestionario;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\Medico\Persona;
use Illuminate\Http\Request;
use Src\Shared\Utils;
use Exception;
use Illuminate\Support\Facades\Log;
use Src\App\Medico\CuestionarioPublicoService;
use Src\App\Medico\CuestionariosRespondidosService;

class CuestionarioPublicoController extends Controller
{
    private $entidad = 'Cuestionario público';
    // private CuestionarioPublicoService $cuestionarioPublicoService;

    /*public function __construct() {
        
    }*/
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(CuestionarioPublicoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            $tipo_cuestionario_id = Cuestionario::find($datos['cuestionario'][0]['id_cuestionario'])->tipo_cuestionario_id;

            if ((new CuestionariosRespondidosService())->personaYaLlenoCuestionario($datos['persona']['identificacion'], $tipo_cuestionario_id))
                throw ValidationException::withMessages(['cuestionario_completado' => ['Usted ya completó el cuestionario para este año.']]);
            // throw new Exception('Usted ya completó el cuestionario para este año.');

            $datos['persona']['tipo_cuestionario_id'] = $tipo_cuestionario_id;
            $persona = Persona::create($datos['persona']);

            $cuestionarioPublicoService = new CuestionarioPublicoService($persona->id);
            $cuestionarioPublicoService->guardarCuestionario($request->cuestionario);

            $modelo = [];
            $mensaje = 'Gracias por completar el cuestionario.';

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (\Throwable $th) {
            // Log::channel('testing')->info('Log', ['error en obtenerEmpleadosFondosRotativos', $th->getMessage(), $th->getLine()]);
            throw $th; //new ValidationException($th->getMessage());
        }
        /* catch (Exception $e) {
            DB::rollBack();
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de cuestionario publico|| ' . $e->getMessage() . ' ' . $e->getLine()], 422);
        } */
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
