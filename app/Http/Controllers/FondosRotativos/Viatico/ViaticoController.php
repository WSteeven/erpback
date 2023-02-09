<?php

namespace App\Http\Controllers\FondosRotativos\Viatico;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Viaticos\ViaticoResource;
use App\Models\FondosRotativos\Viatico\DetalleViatico;
use App\Models\FondosRotativos\Viatico\Viatico;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Src\App\RegistroTendido\GuardarImagenIndividual;
use Src\Config\RutasStorage;

class ViaticoController extends Controller
{

    public function __construct()
    {
        $this->middleware('can:puede.ver.fondo')->only('index', 'show');
        $this->middleware('can:puede.crear.fondo')->only('store');
        $this->middleware('can:puede.editar.fondo')->only('update');
        $this->middleware('can:puede.eliminar.fondo')->only('update');
    }
    public function index(Request $request)
    {
        $page = $request['page'];
        $results = [];

        $results = Viatico::ignoreRequest(['campos'])->with('detalles')->filter()->get();
        $results = ViaticoResource::collection($results);

        return response()->json(compact('results'));
    }
    public function store (Request $request)
    {
        //Adaptacion de foreign keys
        $datos = $request->all();
        $user = Auth::user();
        $detalle_viatico = DetalleViatico::where('id',$datos['detalle']);
        $datos['id_lugar'] = $request->lugar;
        $usuario_autorizado = User::where('id', $request->aut_especial)->first();
        $datos['id_usuario'] = $usuario_autorizado->id;
        $datos['fecha_ingreso']= date('Y-m-d');
        $datos['transcriptor'] = $user->name;
        $datos['detalle'] = $detalle_viatico->id;

        if ($request->hasFile('comprobante1')) $datos['comprobante'] = (new GuardarImagenIndividual($datos['comprobante1'], RutasStorage::COMPROBANTES_VIATICOS))->execute();
        if ($request->hasFile('comprobante2')) $datos['comprobante2'] = (new GuardarImagenIndividual($datos['comprobante2'], RutasStorage::COMPROBANTES_VIATICOS))->execute();

        $viatico = Viatico::create($datos);
        return response()->json($viatico, 201);
    }
}
