<?php

namespace App\Http\Controllers\Vehiculos;

use App\Http\Controllers\Controller;
use App\Http\Requests\Vehiculos\BitacoraVehicularRequest;
use App\Http\Resources\Vehiculos\BitacoraVehicularResource;
use App\Models\Vehiculos\BitacoraVehicular;
use App\Models\Empleado;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class BitacoraVehicularController extends Controller
{
    private $entidad = 'Bitacora Vehicular';
    public function __construct()
    {
        $this->middleware('can:puede.ver.bitacoras_vehiculos')->only('index', 'show');
        $this->middleware('can:puede.crear.bitacoras_vehiculos')->only('store');
        $this->middleware('can:puede.editar.bitacoras_vehiculos')->only('update');
        $this->middleware('can:puede.eliminar.bitacoras_vehiculos')->only('destroy');
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (auth()->user()->hasRole(User::ROL_ADMINISTRADOR_VEHICULOS))
            $results = BitacoraVehicular::all();
        else {
            $results = BitacoraVehicular::where('chofer_id', auth()->user()->empleado->id)->get();
            $results = BitacoraVehicularResource::collection($results);
        }
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(BitacoraVehicularRequest $request)
    {   //Adaptación de foreign keys
        $datos = $request->validated();

        //Respuesta
        try {
            $chofer = Empleado::find($request->chofer_id);
            $chofer->bitacoras()->attach(
                $datos['vehiculo_id'],
                [
                    'fecha' => $datos['fecha'],
                    'hora_salida' => $datos['hora_salida'],
                    'hora_llegada' => $datos['hora_llegada'],
                    'km_inicial' => $datos['km_inicial'],
                    'km_final' => $datos['km_final'],
                    'tanque_inicio' => $datos['tanque_inicio'],
                    'tanque_final' => $datos['tanque_final'],
                    'firmada' => $datos['firmada'],
                ]
            );
            // $bitacora = BitacoraVehicular::create($datos);
            Log::channel('testing')->info('Log', ['BitacoraVehicularRecienCreada', $chofer->ultimaBitacora]);
            $modelo = new BitacoraVehicularResource($chofer->ultimaBitacora);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store', 'F');
        } catch (Exception $ex) {
            Log::channel('testing')->info('Log', ['Ha ocurrido un error al guardar la bitacora', $ex->getMessage(), $ex->getLine()]);
            throw Utils::obtenerMensajeErrorLanzable($ex);
        }
        return response()->json(compact('mensaje', 'modelo'), 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\BitacoraVehicular  $bitacoraVehicular
     * @return \Illuminate\Http\Response
     */
    public function show(BitacoraVehicular $bitacora)
    {
        Log::channel('testing')->info('Log', ['metodo show de bitacora: ...', $bitacora]);
        $modelo = new BitacoraVehicularResource($bitacora);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\BitacoraVehicular  $bitacoraVehicular
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, BitacoraVehicular $bitacoraVehicular)
    {
        try {
            //Validacion de datos
            $datos = $request->validated();

            DB::beginTransaction();
            $bitacoraVehicular->update($datos);
            $modelo = new BitacoraVehicularResource($bitacoraVehicular->refresh());
            $mensaje = Utils::obtenerMensaje($this->entidad, 'update');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollBack();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
        return response()->json(compact('modelo', 'mensaje'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\BitacoraVehicular  $bitacoraVehicular
     * @return \Illuminate\Http\Response
     */
    public function destroy(BitacoraVehicular $bitacora)
    {
        if (!$bitacora->firmada) {
            $bitacora->delete();
            $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
            return response()->json(compact('mensaje'), 200);
        } else {
            throw ValidationException::withMessages([
                'firmada' => ['No se puede eliminar una bitacora firmada!']
            ]);
            // return response()->json(['mensaje' => 'No se puede eliminar un registro ya firmado '], 422);
        }
    }
}
