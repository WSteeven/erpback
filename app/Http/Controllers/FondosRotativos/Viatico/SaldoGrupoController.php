<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\SaldoGrupoResource;
use App\Models\FondosRotativos\Usuario\Estatus;
use App\Models\FondosRotativos\Viatico\SaldoGrupo;
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
        $results = SaldoGrupo::with('tipoFondo','estatus','usuario')->ignoreRequest(['campos'])->filter()->get();
        $results = SaldoGrupoResource::collection($results);
        return response()->json(compact('results'));
    }
    public function show($id)
    {
        $SaldoGrupo = SaldoGrupo::where('id',$id)->first();
        $modelo = new SaldoGrupoResource($SaldoGrupo);
        return response()->json(compact('modelo'), 200);
    }
    public function store(Request $request)
    {
        $request->validate([
            'descripcion' => 'required',
            'autorizacion' => 'required',
            'estatus' => 'required',
        ]);
        $user = Auth::user();
        $estatus = Estatus::where('descripcion', $request->estatus)->first();
        $datos['autorizacion'] = $request->autorizacion;
        $datos['id_detalle_viatico']= $request->detalle_viatico;
        $datos['transcriptor']= $user->name;
        $datos['id_estatus'] = $estatus->id;
        $datos['descripcion'] = $request->descripcion;
        $datos['fecha_trans'] =date('Y-m-d');

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
