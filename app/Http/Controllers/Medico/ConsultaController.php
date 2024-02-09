<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\ConsultaRequest;
use App\Http\Resources\Medico\ConsultaResource;
use App\Models\Medico\Consulta;
use App\Models\Medico\Diagnostico;
use App\Models\Medico\DiagnosticoCita;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class ConsultaController extends Controller
{
    private $entidad = 'Consulta';

    public function __construct()
    {
        $this->middleware('can:puede.ver.consultas')->only('index', 'show');
        $this->middleware('can:puede.crear.consultas')->only('store');
        $this->middleware('can:puede.editar.consultas')->only('update');
        $this->middleware('can:puede.eliminar.consultas')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = Consulta::ignoreRequest(['campos'])->filter()->get();
        $results = ConsultaResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\ConsultaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConsultaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();

            $consulta = Consulta::create($datos);

            foreach($request['diagnosticos'] as $diagnostico) {
                $diagnostico['cie_id'] = $diagnostico['cie'];

                DiagnosticoCita::create($diagnostico);
            }

            $modelo = new ConsultaResource($consulta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  Consulta  $consulta
     * @return \Illuminate\Http\Response
     */
    public function show($consulta)
    {
        $modelo = new ConsultaResource($consulta);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\ConsultaRequest  $request
     * @param  Consulta  $consulta
     * @return \Illuminate\Http\Response
     */
    public function update(ConsultaRequest $request, $consulta)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $consulta->update($datos);
            $modelo = new ConsultaResource($consulta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de consulta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  consulta  $consulta
     * @return \Illuminate\Http\Response
     */
    public function destroy($consulta)
    {
        try {
            DB::beginTransaction();
            $consulta->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de consulta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
