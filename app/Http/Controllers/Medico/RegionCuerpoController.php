<?php

namespace App\Http\Controllers\Medico;

use App\Http\Controllers\Controller;
use App\Http\Requests\Medico\RegionCuerpoRequest;
use App\Http\Resources\Medico\RegionCuerpoResource;
use App\Models\Medico\RegionCuerpo;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class RegionCuerpoController extends Controller
{
    private $entidad = 'Region del Cuerpo';

    public function __construct()
    {
        $this->middleware('can:puede.ver.regiones_cuerpo')->only('index', 'show');
        $this->middleware('can:puede.crear.regiones_cuerpo')->only('store');
        $this->middleware('can:puede.editar.regiones_cuerpo')->only('update');
        $this->middleware('can:puede.eliminar.regiones_cuerpo')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = [];
        $results = RegionCuerpo::ignoreRequest(['campos'])->filter()->get();
        $results = RegionCuerpoResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(RegionCuerpoRequest $request)
    {
        try {
            $datos = $request->validated();
            DB::beginTransaction();
            $region_cuerpo = RegionCuerpo::create($datos);
            $modelo = new RegionCuerpoResource($region_cuerpo);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de cie' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  RegionCuerpo  $region_cuerpo
     * @return \Illuminate\Http\Response
     */
    public function show($region_cuerpo)
    {
        $modelo = new RegionCuerpoResource($region_cuerpo);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  RegionCuerpo  $region_cuerpo
     * @return \Illuminate\Http\Response
     */
    public function update(RegionCuerpoRequest $request, $region_cuerpo)
    {
        try {
            DB::beginTransaction();
            $datos = $request->validated();
            $region_cuerpo->update($datos);
            $modelo = new RegionCuerpoResource($region_cuerpo->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
            return response()->json(compact('mensaje', 'modelo'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de cie' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  RegionCuerpo  $region_cuerpo
     * @return \Illuminate\Http\Response
     */
    public function destroy($region_cuerpo)
    {
        try {
            DB::beginTransaction();
            $region_cuerpo->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            DB::commit();
            return response()->json(compact('mensaje'));
        } catch (Exception $e) {
            DB::rollBack();
            throw ValidationException::withMessages([
                'Error al insertar registro' => [$e->getMessage()],
            ]);
            return response()->json(['mensaje' => 'Ha ocurrido un error al insertar el registro de cie' . $e->getMessage() . ' ' . $e->getLine()], 422);
        }
    }
}
