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

        $fecha_fin = $request->fecha_fin ? Carbon::createFromFormat('d-m-Y', $request->fecha_fin)->endOfDay() : Carbon::now();

        $results = Audit::where('user_id', $empleado->usuario_id)
            ->whereBetween(
                'created_at',
                [
                    Carbon::createFromFormat('d-m-Y', $request->fecha_inicio)->startOfDay(),
                    $fecha_fin
                ]
            )->orderBy('updated_at', 'desc')->get();

        // Log::channel('testing')->info('Log', ['Empleado audits', $results]);

        $results = AuditResource::collection($results);

        return response()->json(compact('results'));
    }
}
