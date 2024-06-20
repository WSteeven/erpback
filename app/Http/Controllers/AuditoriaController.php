<?php

namespace App\Http\Controllers;

use App\Http\Resources\AuditResource;
use App\Models\Empleado;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Models\Audit;

class AuditoriaController extends Controller
{
    public function index(Request $request)
    {
        $empleado = Empleado::find($request->empleado);

        //formato para obtener las fechas al inicio del día y al final del día
        $fecha_inicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio)->startOfDay() : Carbon::now();
        $fecha_fin = $request->fecha_fin ? Carbon::parse($request->fecha_fin)->endOfDay() : Carbon::now();


        $results = Audit::where('user_id', $empleado->usuario_id)->whereBetween('created_at', [$fecha_inicio, $fecha_fin])->orderBy('updated_at', 'desc')->get();
        $modelosAfectados = $results->unique('auditable_type')->pluck('auditable_type')->toArray();
        $modelos = array_map(function ($model) {
            return class_basename($model);
        }, $modelosAfectados);

        Log::channel('testing')->info('Log', ['Empleado audits', $results]);
        Log::channel('testing')->info('Log', ['Modelos', $modelos]);

        $results = AuditResource::collection($results);

        // return response()->json(compact('results', 'modelos'));
        return response()->json(compact('results'));
    }

    public function modelos()
    {
        $results = [];
        $modelos = Audit::groupBy('auditable_type')->pluck('auditable_type')->toArray();
        $modelos = array_map(function ($model) {
            return class_basename($model);
        }, $modelos);

        Log::channel('testing')->info('Log', ['metodo modelos', $modelos]);
        return response()->json(compact('results'));
    }
}
