<?php

namespace App\Http\Controllers\Vehiculos;

use App\Exports\Vehiculos\ConductorLicenciaExport;
use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\ConductorRequest;
use App\Http\Resources\Vehiculos\ConductorResource;
use App\Models\ConfiguracionGeneral;
use App\Models\Vehiculos\Conductor;
use App\Models\Vehiculos\Licencia;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Facades\Excel;
use Src\App\Vehiculos\VehiculoService;
use Src\Shared\Utils;

class ConductorController extends Controller
{
    private $entidad = 'Conductor';
    private $vehiculoService;
    public function __construct()
    {
        $this->vehiculoService = new VehiculoService();
        $this->middleware('can:puede.ver.conductores')->only('index', 'show');
        $this->middleware('can:puede.crear.conductores')->only('store');
        $this->middleware('can:puede.editar.conductores')->only('update');
        $this->middleware('can:puede.eliminar.conductores')->only('destroy');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Conductor::filter()->get();
        $results = ConductorResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ConductorRequest $request)
    {
        Log::channel('testing')->info('Log', ['¿Todo bien en casa?', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only('empleado')['empleado'];
            $datos['tipo_licencia'] = Utils::convertArrayToString($request->tipo_licencia, ',');

            $conductor = Conductor::create($datos);
            $modelo = new ConductorResource($conductor);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                '500' => [Utils::obtenerMensajeError($e, 'Error al guardar el conductor')],
            ]);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Conductor  $conductor
     * @return \Illuminate\Http\Response
     */
    public function show(Conductor $conductor)
    {
        $modelo = new ConductorResource($conductor);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Conductor  $conductor
     * @return \Illuminate\Http\Response
     */
    public function update(ConductorRequest $request, Conductor $conductor)
    {
        Log::channel('testing')->info('Log', ['¿Todo bien en casa actualizacion?', $request->all()]);
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $datos['empleado_id'] = $request->safe()->only('empleado')['empleado'];
            $datos['tipo_licencia'] = Utils::convertArrayToString($request->tipo_licencia, ',');

            $conductor->update($datos);
            Log::channel('testing')->info('Log', [$datos['licencias']]);
            $tiposLicencias = array_column($datos['licencias'], 'tipo_licencia');
            $datos['licencias'] = array_map(function ($licencia) use ($conductor) {
                return [
                    'conductor_id' => $conductor->empleado_id,
                    'tipo_licencia' => $licencia['tipo_licencia'],
                    'inicio_vigencia' => $licencia['inicio_vigencia'],
                    'fin_vigencia' => $licencia['fin_vigencia'],
                ];
            }, $datos['licencias']);
            Licencia::upsert(
                $datos['licencias'],
                uniqueBy: ['conductor_id', 'tipo_licencia'],
                update: ['tipo_licencia', 'inicio_vigencia', 'fin_vigencia']
            );
            Licencia::eliminarObsoletos($conductor->empleado_id, $tiposLicencias);
            $modelo = new ConductorResource($conductor);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            $mensaje = '(' . $e->getLine() . ') Hubo un error al actualizar el conductor: ' . $e->getMessage();
            throw ValidationException::withMessages([
                '500' => [$mensaje],
            ]);
            return response()->json(compact('mensaje'), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Conductor  $conductor
     * @return \Illuminate\Http\Response
     */
    public function destroy(Conductor $conductor)
    {
        $conductor->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }

    /******************
     * REPORTES
     *****************/
    public function reporteConductorLicencia(Request $request)
    {
        Log::channel('testing')->info('Log',  ['reporteConductorLicencia', $request->all()]);
        $configuracion = ConfiguracionGeneral::first();
        $results = [];
        try {
            $results = [];
            if ($request->boolean('todos'))
                $results = Conductor::all();
            else
                $results = Conductor::where('empleado_id', $request->conductor)->get();
            $reporte = $results;
            $results =$this->vehiculoService->empaquetarInformacionConductores($results, $request->tipo_licencia);
            switch ($request->accion) {
                case 'excel':
                    throw new Exception('Esta caracteristica aún no está desarrollada');
                    // return Excel::download(new ConductorLicenciaExport(), 'reporte_conductor_licencia.xlsx');
                    break;
                case 'pdf':
                    throw new Exception('Esta caracteristica aún no está desarrollada');
                    // try {
                    //     $peticion = $request->all();
                    //     $pdf = Pdf::loadView('vehiculos.conductores.reporte_conductor_licencia', compact(['reporte', 'peticion', 'configuracion']));
                    //     $pdf->setPaper('A4', 'landscape');
                    //     $pdf->render();
                    //     return $pdf->stream();
                    // } catch (\Throwable $ex) {
                    //     throw $ex->getMessage() . '. ' . $ex->getLine();
                    // }
                    break;
                default:
                    // Log::channel('testing')->info('Log', ['reporteConductorLicencia->default', '¿Todo bien en casa?']);
            }
        } catch (Exception $ex) {
            throw ValidationException::withMessages([
                'Error al generar reporte' => [$ex->getMessage()],
            ]);
        }

        return response()->json(compact('results'));
    }
}
