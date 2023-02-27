<?php

namespace App\Http\Controllers\FondosRotativos\Saldo;

use App\Http\Controllers\Controller;
use App\Http\Resources\FondosRotativos\Saldo\SaldoGrupoResource;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Viatico\EstadoViatico;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Facades\Excel;
use Src\Shared\Utils;
use App\Exports\SaldoActualExport;
class SaldoGrupoController extends Controller
{
    private $entidad = 'saldo_grupo';
    public function __construct()
    {
        $this->middleware('can:puede.ver.saldo')->only('index', 'show');
        $this->middleware('can:puede.crear.saldo')->only('store');
        $this->middleware('can:puede.editar.saldo')->only('update');
        $this->middleware('can:puede.eliminar.saldo')->only('update');
        $this->middleware('can:puede.ver.reporte_saldo_actual')->only('saldo_actual');
    }
    public function index(Request $request)
    {
        $results = [];
        $results = SaldoGrupo::with('usuario')->ignoreRequest(['campos'])->filter()->get();
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
        $datos_usuario_add_saldo = User::where('id', $request->usuario)->first();
        $datos_saldo_inicio_sem = SaldoGrupo::where('id_usuario', $request->usuario)->orderBy('id', 'desc')->first();
        $user = Auth::user();
        $fechaIni = date("Y-m-d", strtotime($request->fecha . "-$rest days"));
        $fechaFin = date("Y-m-d", strtotime($request->fecha . "+$sum days"));
        //Adaptacion de campos
        $datos = $request->all();
        $datos['id_usuario'] = $request->usuario;
        $datos['saldo_anterior'] = $datos_saldo_inicio_sem != null ? $datos_saldo_inicio_sem->saldo_actual : 0;
        $datos['fecha'] = date('Y-m-d H:i:s');
        $datos['fecha_inicio'] = $fechaIni;
        $datos['fecha_fin'] = $fechaFin;
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
    public function saldo_actual_usuario($id)
    {
        $saldo_actual = SaldoGrupo::where('id_usuario', $id)->orderBy('id', 'desc')->first();
        $saldo_actual = $saldo_actual != null ? $saldo_actual->saldo_actual : 0;

        return response()->json(compact('saldo_actual'));
    }
    public function saldo_actual(Request $request, $tipo)
    {
        try {
            $id = $request->usuario != null ?  $request->usuario : 0;
            $saldos_actual_user = $request->usuario == null ?
            SaldoGrupo::with('usuario')->whereIn('id', function ($sub) {
                $sub->selectRaw('max(id)')->from('saldo_grupo')->groupBy('id_usuario');
            })->get()
                : SaldoGrupo::with('usuario')->where('id_usuario', $id)->orderBy('id', 'desc')->first();
            $tipo_reporte = $request->usuario != null ? 'usuario' : 'todos';           ;
            $results = SaldoGrupo::empaquetarListado($saldos_actual_user, $tipo_reporte);
            $nombre_reporte = 'reporte_saldoActual';
            $reportes =  ['saldos' => $results];
            switch ($tipo) {
                case 'excel':
                    return Excel::download(new SaldoActualExport($reportes), $nombre_reporte.'.xlsx');
                    break;
                case 'pdf':
                    Log::channel('testing')->info('Log', ['variable que se envia a la vista', $reportes]);
                    $pdf = Pdf::loadView('exports.reportes.reporte_saldo_actual', $reportes);
                    return $pdf->download($nombre_reporte . '.pdf');
                    break;
            }
        } catch (Exception $e) {
            Log::channel('testing')->info('Log', ['error', $e->getMessage(), $e->getLine()]);
        }
    }
}
