<?php

namespace App\Http\Controllers\RecursosHumanos\SeleccionContratacion;

use App\Http\Controllers\Controller;
use App\Http\Requests\RecursosHumanos\SeleccionContratacion\PostulacionRequest;
use App\Http\Resources\RecursosHumanos\SeleccionContratacion\PostulacionResource;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulacion;
use App\Models\RecursosHumanos\SeleccionContratacion\Postulante;
use App\Models\RecursosHumanos\SeleccionContratacion\Vacante;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Src\Shared\Utils;
use Throwable;

class PostulacionController extends Controller
{
    private string $entidad = 'Postulación';

    public function __construct()
    {
        // Asegura que el usuario esté autenticado en todas las acciones
        $this->middleware('check.user.logged.in');

        // $this->middleware('can:puede.crear.rrhh_vacantes')->only('store');
        $this->middleware('can:puede.editar.rrhh_vacantes')->only('update');
        $this->middleware('can:puede.eliminar.rrhh_vacantes')->only('destroy');
    }


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results = Postulacion::ignoreRequest(['campos'])->filter()->orderBy('id', 'desc')->get();
        $results = PostulacionResource::collection($results);
        return response()->json(compact('results'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PostulacionRequest $request)
    {
        Log::channel('testing')->info('Log', ['request en Postulacion->store', $request->all()]);
        $datos = $request->validated();

        try {
            DB::beginTransaction();
            if (auth()->user()) {
                $postulacion = auth()->user()->postulacion()->create($datos);
            } elseif (auth()->guard('user_external')->user()) {
                $postulacion = auth()->guard('user_external')->user()->postulacion()->create($datos);

                //Postulante hace referencia a la tabla postulante, que es equivalente a la tabla empleados, solo que en este caso es para usuarios externos
                $postulante = Postulante::find($postulacion->user_id);
                if (is_null($postulante->correo_personal)) $postulante->correo_personal = $datos['correo_personal'];
                if (is_null($postulante->direccion)) $postulante->direccion = $datos['direccion'];
                if (is_null($postulante->fecha_nacimiento)) $postulante->fecha_nacimiento = $datos['fecha_nacimiento'];
                if (is_null($postulante->genero)) $postulante->genero = $datos['genero'];
                if (is_null($postulante->identidad_genero_id)) $postulante->identidad_genero_id = $datos['identidad_genero_id'];
                if (is_null($postulante->pais_id)) $postulante->pais_id = $datos['pais_id'];
                $postulante->save();
            }
            if ($postulacion->vacante_id) {
                $vacante = Vacante::find($postulacion->vacante_id);
                if ($vacante) {
                    $vacante->update(['numero_postulantes' => $vacante->numero_postulantes + 1]);
                    $vacante->save();
                }
            }
            // throw new Exception('Excepcion controlada');
            $modelo = new PostulacionResource($vacante);
            $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
            DB::commit();
        } catch (Throwable $e) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($e);
        }

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(Postulacion $postulacion)
    {
        $modelo = new PostulacionResource($postulacion);
        return response()->json(compact('modelo'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(PostulacionRequest $request, Postulacion $postulacion)
    {
        try {
            DB::beginTransaction();
            throw new Exception('Metodo no configurado aún');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            throw new Exception('Metodo no configurado aún. Comunícate con Dept. Informático.');
            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            throw Utils::obtenerMensajeErrorLanzable($th);
        }
    }
}
