<?php

namespace App\Http\Controllers\Ventas;

use App\Http\Controllers\Controller;
use App\Models\Ventas\Ventas;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardVentasController extends Controller
{
    public function index()
    {
         // Obtencion de parametros
         $idEmpleado = request('empleado_id');
         $fechaInicio = request('fecha_inicio');
         $fechaFin = request('fecha_fin');
           // Conversion de fechas
        $fecha_inicio = Carbon::createFromFormat('d-m-Y', $fechaInicio)->format('Y-m-d');
        $fecha_fin = Carbon::createFromFormat('d-m-Y', $fechaFin)->addDay()->toDateString();
        $query_ventas = Ventas::whereBetween('fecha_activ', [$fecha_inicio, $fecha_fin])->where('vendedor_id',$idEmpleado)->with('vendedor', 'producto');
        $cantidad_ventas = $query_ventas->get()->count();
        $cantidad_ventas_instaladas = $query_ventas->where('estado_activ','APROBADO')->get()->count();
        $cantidad_ventas_por_instalar = $query_ventas->where('estado_activ','PENDIENTE')->get()->count();
        $cantidad_ventas_por_rechazadas = $query_ventas->where('estado_activ','RECHAZADA')->get()->count();
        $ventasPorEstado= $query_ventas->get();
        $results = compact('cantidad_ventas','cantidad_ventas_instaladas','cantidad_ventas_por_instalar','cantidad_ventas_por_rechazadas','ventasPorEstado');
        return $results;
    }
}
