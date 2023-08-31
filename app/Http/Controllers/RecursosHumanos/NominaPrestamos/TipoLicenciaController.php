<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\TipoLicencia;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class TipoLicenciaController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:puede.ver.tipo_licencia')->only('index', 'show');
        $this->middleware('can:puede.crear.tipo_licencia')->only('store');
        $this->middleware('can:puede.editar.tipo_licencia')->only('update');
        $this->middleware('can:puede.eliminar.tipo_licencia')->only('update');
    }

  /**
   * La función de índice recupera una lista de objetos TipoLicencia según ciertas condiciones y los
   * devuelve como una respuesta JSON.
   *
   * @param Request request El parámetro  es una instancia de la clase Request, que representa
   * una solicitud HTTP. Contiene información sobre la solicitud, como el método de solicitud,
   * encabezados y datos de entrada.
   *
   * @return una respuesta JSON que contiene la variable 'resultados'.
   */
    public function index(Request $request)
    {
        $user = Auth::user()->empleado;
        $results = TipoLicencia::ignoreRequest(['campos'])->where('id', '!=', $user->genero == 'F' ? 2 : 1)->filter()->get();
        if ($user->genero == 'F') {
            $results = $results->map(function ($tipoLicencia) {
                if ($tipoLicencia->id === 11) {
                    $tipoLicencia->num_dias = 94;
                }
                return $tipoLicencia;
            });
        }

        return response()->json(compact('results'));
    }
    public function show(Request $request, TipoLicencia $tipo_licencia)
    {
        return response()->json(compact('tipo_licencia'));
    }
    public function store(Request $request)
    {
        $tipo_licencia = new TipoLicencia();
        $tipo_licencia->nombre = $request->nombre;
        $tipo_licencia->save();
        return $tipo_licencia;
    }
    public function update(Request $request, TipoLicencia $tipo_licencia)
    {
        $tipo_licencia->nombre = $request->nombre;
        $tipo_licencia->save();
        return $tipo_licencia;
    }
    public function destroy(Request $request, TipoLicencia $tipo_licencia)
    {
        $tipo_licencia->delete();
        return response()->json(compact('tipo_licencia'));
    }
}
