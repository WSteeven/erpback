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

    public function __construct()
    {
        $this->middleware('can:puede.acceder.auditorias')->only('index');
    }



    public function index(Request $request)
    {
        // Log::channel('testing')->info('Log', ['Request', $request->all()]);
        $empleado = Empleado::find($request->empleado);
        $request['user_id'] = $empleado?->usuario_id;

        //formato para obtener las fechas al inicio del día y al final del día
        $fecha_inicio = $request->fecha_inicio ? Carbon::parse($request->fecha_inicio)->startOfDay() : Carbon::now();
        $fecha_fin = $request->fecha_fin ? Carbon::parse($request->fecha_fin)->endOfDay() : Carbon::now();
        // $request['created_at[start]'] = $fecha_inicio;
        // $request['auditable_type[like]'] = $request->auditable_type;
        $request->merge([
            'created_at' => [
                'start' => $fecha_inicio,
                'end' => $fecha_fin,
            ]
        ]);

        // $results = Audit::when($request->empleado_id, function ($q) use ($empleado) {
        //     $q->where('user_id', $empleado->usuario_id);
        //     })
        //     ->when($request->auditable_id, function ($q) use ($request) {
        //         $q->where('auditable_id', $request->auditable_id);
        //     })
        //     ->when($request->auditable_type, function ($q) use ($request) {
        //         $q->where('auditable_type', 'like', '%' . $request->auditable_type);
        //     })
        //     ->when($request->fecha_inicio, function ($q) use ($fecha_inicio, $fecha_fin) {
        //         $q->whereBetween('created_at', [$fecha_inicio, $fecha_fin]);
        //     })
        //     ->orderBy('updated_at', 'desc')->get();
        // Log::channel('testing')->info('Log', ['Request antes de filtrar', $request->all()]);
        $results = Audit::ignoreRequest(['isComponentFilesModified','empleado', 'fecha_inicio', 'fecha_fin', 'auditable_type'])->filter()
            ->when($request->auditable_type, function ($q) use ($request) {
                $q->where('auditable_type', 'like', '%' . $request->auditable_type);
            })
            ->orderBy('updated_at', 'desc')->get();
        $modelosAfectados = $results->unique('auditable_type')->pluck('auditable_type')->toArray();
        $modelos = array_map(function ($model) {
            return class_basename($model);
        }, $modelosAfectados);

        // Log::channel('testing')->info('Log', ['Empleado audits', $results->count()]);
        // Log::channel('testing')->info('Log', ['Modelos', $modelos]);

        $results = AuditResource::collection($results);

        // return response()->json(compact('results', 'modelos'));
        return response()->json(compact('results'));
    }

    public function modelos()
    {
        $results = [];
        $modelos = Audit::groupBy('auditable_type')->pluck('auditable_type')->toArray();
        $results = array_map(function ($model) {
            return class_basename($model);
        }, $modelos);

        // Log::channel('testing')->info('Log', ['metodo modelos', $results]);
        return response()->json(compact('results'));
    }
}
