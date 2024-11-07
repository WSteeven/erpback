<?php

namespace App\Http\Controllers\RecursosHumanos\NominaPrestamos;

use App\Http\Controllers\Controller;
use App\Models\RecursosHumanos\NominaPrestamos\TipoLicencia;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Src\Shared\Utils;

class TipoLicenciaController extends Controller
{
    private string $entidad = 'Tipo de Licencia';
    private array $reglas = [
        'nombre' =>'required|string',
        'num_dias' =>'required|integer|min:1|max:365',
        'estado'=>'boolean'
    ];

    public function __construct()
    {
        $this->middleware('can:puede.ver.tipos_licencias')->only('index', 'show');
        $this->middleware('can:puede.crear.tipos_licencias')->only('store');
        $this->middleware('can:puede.editar.tipos_licencias')->only('update');
        $this->middleware('can:puede.eliminar.tipos_licencias')->only('destroy');
    }

  /**
   * La función de índice recupera una lista de objetos TipoLicencia según ciertas condiciones y los
   * devuelve como una respuesta JSON.
   *
   * @return JsonResponse respuesta JSON que contiene la variable 'resultados'.
   */
    public function index()
    {
        $user = Auth::user()->empleado;
        if(Auth::user()->hasRole(User::ROL_RECURSOS_HUMANOS)){
            $results = TipoLicencia::ignoreRequest(['campos'])->filter()->get();
        }else{
            $results = TipoLicencia::ignoreRequest(['campos'])->where('id', '!=', $user->genero == 'F' ? 2 : 1)->filter()->get();
        }
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

    public function store(Request $request)
    {

        $datos = $request->validate($this->reglas);

        $tipo = TipoLicencia::create($datos);
        $modelo = $tipo;
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');

        return response()->json(compact('mensaje', 'modelo'));
    }

    public function show(TipoLicencia $tipo)
    {
        $modelo = $tipo;
        return response()->json(compact('modelo'));
    }

    public function update(Request $request, TipoLicencia $tipo)
    {
        $tipo->update($request->validate($this->reglas));
        $modelo = $tipo->refresh();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'update');

        return response()->json(compact('mensaje', 'modelo'));
    }

    /**
     * @throws ValidationException
     */
    public function destroy()
    {
        throw ValidationException::withMessages(['error'=>'Método no desarrollado, por favor contacta al departamento de Informática para más información.']);
    }
}
