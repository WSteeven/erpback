<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\MedicacionConsultaRequest;
use App\Http\Resources\Medico\MedicacionConsultaResource;
use App\Models\Medico\MedicacionConsulta;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class MedicacionConsultaController extends Controller
{
    private $entidad = 'Medicacion  de Consulta';

    public function __construct()
    {
        $this->middleware('can:puede.ver.medicaciones_consultas')->only('index', 'show');
        $this->middleware('can:puede.crear.medicaciones_consultas')->only('store');
        $this->middleware('can:puede.editar.medicaciones_consultas')->only('update');
        $this->middleware('can:puede.eliminar.medicaciones_consultas')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = MedicacionConsulta::ignoreRequest(['campos'])->filter()->get();
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\MedicacionConsultaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(MedicacionConsultaRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $medicacion_consulta = MedicacionConsulta::create($datos);
            $modelo = new MedicacionConsultaResource($medicacion_consulta);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de medicacionconsulta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $medicacion_consulta
     * @return \Illuminate\Http\Response
     */
    public function show(MedicacionConsulta $medicacion_consulta)
    {
        $modelo = new MedicacionConsultaResource($medicacion_consulta);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\MedicacionConsultaRequest  $request
     * @param  MedicacionConsulta  $medicacion_consulta
     * @return \Illuminate\Http\Response
     */
    public function update(MedicacionConsultaRequest $request, MedicacionConsulta $medicacion_consulta)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $medicacion_consulta->update($datos);
            $modelo = new MedicacionConsultaResource($medicacion_consulta->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de medicacionconsulta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  MedicacionConsulta  $medicacion_consulta
     * @return \Illuminate\Http\Response
     */
    public function destroy(MedicacionConsulta $medicacion_consulta)
    {
        try {
            DB::beginTransaction();
            $medicacion_consulta->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de medicacionconsulta' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
