<?php

namespace App\Models\Medico;

use App\Models\Empleado;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use App\Traits\UppercaseValuesTrait;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class RespuestaCuestionarioEmpleado extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, Filterable, AuditableModel;

    protected $table = 'med_respuestas_cuestionarios_empleados';
    protected $fillable = [
        'cuestionario_id',
        'empleado_id'
    ];
    private static $whiteListFilter = ['*'];

    public function cuestionario()
    {
        return $this->belongsTo(Cuestionario::class, 'cuestionario_id')->with('pregunta');
    }

    public function empleado()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id');
    }
    public static function empaquetar($results_data)
    {
        $results = [];
        $cont = 0;
        foreach ($results_data as $result) {
            $row['id'] =  $result->id;
            $row['empleado'] = $result->apellidos .' '.  $result->nombres;
            $row['ciudad'] = $result->canton->canton;
            $row['provincia'] = $result->canton->provincia->provincia;
            $cuestionario = RespuestaCuestionarioEmpleado::obtenerCuestionario($result->id);
            $row['fecha_creacion'] = count($cuestionario)>0?Carbon::parse($cuestionario[0]['fecha_creacion'])->format('d-m-Y H:i:s'):'';
            $row['cuestionario'] = $cuestionario;
            $row['area'] =  $result->area->nombre;
            $row['nivel_academico'] =$result->nivel_academico;
            $row['edad'] =Carbon::now()->diffInYears($result->fecha_nacimiento).' AÑOS';
            $row['antiguedad'] =Carbon::now()->diffInYears($result->fecha_vinculacion).' AÑOS';
            $row['genero'] =$result->genero ==='M'?'MASCULINO':'FEMENINO';
            $results[$cont] = $row;
            $cont++;
        }
        return $results;
    }
    private static function obtenerCuestionario($empleado_id){
        $respuesta_cuestionario = RespuestaCuestionarioEmpleado::where('empleado_id', $empleado_id)->with('cuestionario')->get();
        if ( $respuesta_cuestionario) {
            $cuestionarios = array_map(function ($cuestionario) {
                $respuesta =Respuesta::find($cuestionario['cuestionario']['respuesta_id']);
                $new_cuestionario = ["pregunta_id" => $cuestionario['cuestionario']['pregunta_id'],'respuesta'=>$respuesta,'fecha_creacion'=>$cuestionario['created_at']];
                return $new_cuestionario;
            }, $respuesta_cuestionario->toArray());
            return $cuestionarios;
        }
        return null;

    }
}
