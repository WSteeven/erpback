<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\SaldoGrupoResource;
use App\Models\FondosRotativos\Viatico\EstadoViatico;
use App\Models\FondosRotativos\Viatico\SaldoGrupo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\Shared\Utils;

class SaldoGrupoController extends Controller
{
    private $entidad = 'saldo_grupo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.saldo')->only('index', 'show');
        $this->middleware('can:puede.crear.saldo')->only('store');
        $this->middleware('can:puede.editar.saldo')->only('update');
        $this->middleware('can:puede.eliminar.saldo')->only('update');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = SaldoGrupo::with('tipo_fondo','tipo_saldo', 'estatus', 'usuario')->ignoreRequest(['campos'])->filter()->get();
        $results = SaldoGrupoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show($id)
    {
        $SaldoGrupo = SaldoGrupo::where('id', $id)->first();
        $modelo = new SaldoGrupoResource($SaldoGrupo);
        return response()->json(compact('modelo'), 200);
    }
    public function store(Request $request)
    {
        $array_dias['Sunday'] = 0;
        $array_dias['Monday'] = 1;
        $array_dias['Tuesday'] = 2;
        $array_dias['Wednesday'] = 3;
        $array_dias['Thursday'] = 4;
        $array_dias['Friday'] = 5;
        $array_dias['Saturday'] = 6;

        $dia_actual = $array_dias[date('l', strtotime($request->fecha))];

        $rest = $dia_actual + 1;
        $sum = 5 - $dia_actual;
        $estatus_viatico = EstadoViatico::where('descripcion', 'like', '%APROBADO%')->first();
        $datos_usuario_add_saldo = User::where('id', $request->usuario)->first();
        $datos_saldo_inicio_sem = SaldoGrupo::where('id_usuario', $request->usuario)->first();
        $user = Auth::user();
        $fechaIni = date("Y-m-d", strtotime($request->fecha . "-$rest days"));
        $fechaFin = date("Y-m-d", strtotime($request->fecha. "+$sum days"));
        //Adaptacion de campos
            $datos = $request->all();
            $datos['id_tipo_fondo'] = $request->tipo_fondo;
            $datos['id_tipo_saldo'] = $request->tipo_saldo;
            $datos['id_usuario'] = $request->usuario;
            $datos['id_estatus'] = $estatus_viatico->id;
            $datos['saldo_anterior'] = $datos_saldo_inicio_sem!=null ? $datos_saldo_inicio_sem->saldo_actual : 0;
            $datos['fecha'] = date('Y-m-d H:i:s');
            $datos['fecha_inicio'] = $fechaIni;
            $datos['fecha_fin'] = $fechaFin;
            $datos['fecha_trans'] = date("Y-m-d", strtotime($request->fecha));
            $datos['transcriptor']= $user->name;
        $modelo = SaldoGrupo::create($datos);
        $modelo = new SaldoGrupoResource($modelo);
        $mensaje = Utils::obtenerMensaje($this->entidad, 'store');
        return response()->json(compact('mensaje', 'modelo'));
    }


    public function destroy(SaldoGrupo $SaldoGrupo)
    {
        $SaldoGrupo->delete();
        $mensaje = Utils::obtenerMensaje($this->entidad, 'destroy');
        return response()->json(compact('mensaje'));
    }
}
