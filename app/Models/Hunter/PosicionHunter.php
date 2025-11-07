<?php

namespace App\Models\Hunter;

use App\Traits\UppercaseValuesTrait;
use DB;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class PosicionHunter extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable;
    use AuditableModel;

    protected $table = 'hunt_posiciones_hunter';
    protected $fillable = [
        'source',
        'imei',
        'placa',
        'lat',
        'lng',
        'velocidad', // expr en KMs
        'rumbo', // esto es en grados de brujula (90-180 o algo más )
        'alt',
        'fecha',
        'encendido',
        'direccion',
        'tipo_reporte',
        'estado',
        'flags_binarios',
        'flags',
        'raw_data',
        'received_at',

    ];

    protected $casts = [
        'received_at' => 'datetime',
        'encendido' => 'boolean',
        'flags_binarios' => 'array',
        'flags' => 'array',
        'fecha' => 'datetime',

    ];

// Scope en tu modelo PosicionHunter
    public function scopeLatestPerVehicle($query)
    {
        return $query->join(DB::raw('(
        SELECT
            placa,
            MAX(fecha) as max_fecha,
            MAX(id) as max_id
        FROM hunt_posiciones_hunter
        -- QUITAMOS USE INDEX porque el índice (placa, fecha) NO existe
        GROUP BY placa
    ) latest'), function ($join) {
            $join->on('hunt_posiciones_hunter.placa', '=', 'latest.placa')
                ->on('hunt_posiciones_hunter.fecha', '=', 'latest.max_fecha')
                ->on('hunt_posiciones_hunter.id', '=', 'latest.max_id');
        })
            ->select('hunt_posiciones_hunter.*');
    }


    public static function mapearCoordenadas($posicion)
    {
        $encendido = $posicion->tipo_reporte[0] ? 'SI' : 'NO';
        return [
            'lat' => $posicion->lat,
            'lng' => $posicion->lng,
            'titulo' => $posicion->placa,
            'descripcion' => "Encendido: $encendido,  $posicion->tipo_reporte",
        ];
    }
}
