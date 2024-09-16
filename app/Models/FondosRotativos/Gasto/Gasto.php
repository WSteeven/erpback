<?php

namespace App\Models\FondosRotativos\Gasto;

use App\Models\Canton;
use App\Models\Empleado;
use App\Models\FondosRotativos\Saldo\SaldoGrupo;
use App\Models\FondosRotativos\Saldo\Saldo;
use App\Models\Notificacion;
use App\Models\Proyecto;
use App\Models\Subtarea;
use App\Models\Tarea;
use App\Models\User;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

/**
 * App\Models\FondosRotativos\Gasto\Gasto
 *
 * @method static ignoreRequest(string[] $array)
 * @method static where(string $string, mixed $ruc)
 * @property int $id
 * @property string $fecha_viat
 * @property int $id_lugar
 * @property int|null $id_tarea
 * @property int|null $id_subtarea
 * @property int|null $id_proyecto
 * @property string $ruc
 * @property string|null $factura
 * @property string|null $num_comprobante
 * @property int $aut_especial
 * @property int $detalle
 * @property string $cantidad
 * @property string $valor_u
 * @property string $total
 * @property string $comprobante
 * @property string $comprobante2
 * @property string|null $observacion
 * @property int $id_usuario
 * @property int $estado
 * @property string|null $detalle_estado
 * @property string|null $observacion_anulacion
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Empleado|null $authEspecialUser
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FondosRotativos\Gasto\BeneficiarioGasto> $beneficiarioGasto
 * @property-read int|null $beneficiario_gasto_count
 * @property-read Canton|null $canton
 * @property-read \App\Models\FondosRotativos\Gasto\EstadoViatico|null $detalleEstado
 * @property-read \App\Models\FondosRotativos\Gasto\DetalleViatico|null $detalle_info
 * @property-read Empleado|null $empleado
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FondosRotativos\Gasto\BeneficiarioGasto> $empleadoBeneficiario
 * @property-read int|null $empleado_beneficiario_count
 * @property-read \App\Models\FondosRotativos\Gasto\EstadoViatico|null $estadoViatico
 * @property-read \App\Models\FondosRotativos\Gasto\GastoVehiculo|null $gastoVehiculo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Proyecto|null $proyecto
 * @property-read Saldo|null $saldoFondoRotativo
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\FondosRotativos\Gasto\SubDetalleViatico> $subDetalle
 * @property-read int|null $sub_detalle_count
 * @property-read Subtarea|null $subTarea
 * @property-read Tarea|null $tarea
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto acceptRequest(?array $request = null)
 * @method static \Database\Factories\FondosRotativos\Gasto\GastoFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto filter(?array $request = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto query()
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto setBlackListDetection(?array $black_list_detections = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto setCustomDetection(?array $object_custom_detect = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto setLoadInjectedDetection($load_default_detection)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereAutEspecial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereCantidad($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereComprobante($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereComprobante2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereDetalle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereDetalleEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereEstado($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereFactura($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereFechaViat($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereIdLugar($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereIdProyecto($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereIdSubtarea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereIdTarea($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereIdUsuario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereNumComprobante($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereObservacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereObservacionAnulacion($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereRuc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereTotal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Gasto whereValorU($value)
 * @mixin \Eloquent
 */
class Gasto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    use UppercaseValuesTrait;
    const APROBADO = 1;
    const RECHAZADO = 2;
    const PENDIENTE = 3;
    const ANULADO = 4;
    protected $table = 'gastos';
    protected $primaryKey = 'id';
    protected $fillable = [
        'id_lugar',
        'fecha_viat',
        'id_tarea',
        'id_subtarea',
        'id_proyecto',
        'ruc',
        'factura',
        'num_comprobante',
        'aut_especial',
        'detalle',
        'cantidad',
        'valor_u',
        'total',
        'comprobante',
        'comprobante2',
        'observacion',
        'id_usuario',
        'estado',
        'detalle_estado',
        'observacion_anulacion'
    ];

    private static $whiteListFilter = ['*'];
        // 'factura',
        // 'fecha_viat',
        // 'id_tarea',
        // 'subdetalle',
        // 'id_proyecto',
        // 'ruc',
        // 'factura',
        // 'aut_especial',
        // 'detalle',
        // 'cantidad',
        // 'valor_u',
        // 'total',
        // 'comprobante',
        // 'comprobante2',
        // 'observacion',
        // 'id_usuario',
        // 'estado',
        // 'detalle_estado',
        // 'usuario',
        // 'fecha_inicio',
        // 'fecha_fin',
        // 'ciudad',
        // 'id_lugar'
    // ];

    public function detalle_info()
    {
        return $this->hasOne(DetalleViatico::class, 'id', 'detalle');
    }

    /**
     * Relación one to many.
     * Un gasto tiene varios subdetalles asociados
     */
    public function subDetalle()
    {
        return $this->belongsToMany(SubDetalleViatico::class,'subdetalle_gastos', 'gasto_id', 'subdetalle_gasto_id');
    }
    public function empleadoBeneficiario()
    {
        return $this->belongsToMany(BeneficiarioGasto::class,'beneficiario_gastos', 'gasto_id', 'empleado_id');
    }

    public function authEspecialUser()
    {
        return $this->hasOne(Empleado::class, 'id', 'aut_especial');
    }
    public function estadoViatico()
    {
        return $this->hasOne(EstadoViatico::class, 'id', 'estado');
    }
    public function proyecto()
    {
        return $this->hasOne(Proyecto::class, 'id', 'id_proyecto');
    }
    public function tarea()
    {
        return $this->hasOne(Tarea::class, 'id', 'id_tarea')->with('centroCosto');
    }
    public function subTarea()
    {
        return $this->hasOne(Subtarea::class, 'id', 'id_subtarea');
    }
    public function canton()
    {
        return $this->hasOne(Canton::class, 'id', 'id_lugar');
    }
    public function empleado()
    {
        return $this->hasOne(Empleado::class, 'id', 'id_usuario')->with('user') ;
    }
    public function detalleEstado()
    {
        return $this->hasOne(EstadoViatico::class, 'id', 'detalle_estado');
    }
    public function gastoVehiculo()
    {
        return $this->hasOne(GastoVehiculo::class, 'id_gasto', 'id');
    }
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }
    public function beneficiarioGasto(){
        return $this->hasMany(BeneficiarioGasto::class, 'gasto_id', 'id')->with('empleado');
    }
    public function saldoFondoRotativo()
    {
        return $this->morphOne(Saldo::class, 'saldoable');
    }

    public static function empaquetar($gastos)
    {
        try{
            $results = [];
            $id = 0;
            $row = [];
            foreach ($gastos as $gasto) {
                $row['id'] = $gasto->id;
                $row['num_registro'] = $id+1;
                $row['fecha']= $gasto->fecha_viat;
                $row['fecha_autorizacion']= $gasto->updated_at;
                $row['lugar']= $gasto->canton?->canton;
                $row['factura']= $gasto->factura;
                $row['ruc'] = $gasto->ruc;
                $row['num_comprobante']= $gasto->num_comprobante;
                $row['empleado_info']= $gasto->empleado->user;
                $row['usuario'] = $gasto->empleado;
                $row['autorizador'] = $gasto->authEspecialUser->nombres . ' ' . $gasto->authEspecialUser->apellidos;
                $row['grupo'] =$gasto->empleado->grupo==null?'':$gasto->empleado->grupo->descripcion;
                $row['tarea'] = $gasto->tarea;
                $row['centro_costo'] = $gasto->tarea !== null ? $gasto->tarea?->centroCosto?->nombre:'';
                $row['sub_centro_costo'] = $gasto->empleado->grupo==null ?'':$gasto->empleado->grupo?->subCentroCosto?->nombre;
                $row['proyecto'] = $gasto->Proyecto;
                $row['detalle'] = $gasto->detalle_info == null ? 'SIN DETALLE' : $gasto->detalle_info->descripcion;
                $row['sub_detalle'] = $gasto->subDetalle;
                $row['cantidad'] = $gasto->cantidad;
                $row['valor_u'] = $gasto->valor_u;
                $row['sub_detalle_desc'] = $gasto->detalle_info == null ? 'SIN DETALLE' : $gasto->detalle_info->descripcion.': '.Gasto::subDetalleInform($gasto->subDetalle->toArray());
                $row['placa'] = $gasto->gastoVehiculo?->placa;
                $row['kilometraje'] = $gasto->gastoVehiculo?->kilometraje;
                $row['observacion'] = $gasto->observacion;
                $row['detalle_estado'] = $gasto->detalle_estado;
                $row['comprobante'] = $gasto->comprobante;
                $row['comprobante2'] = $gasto->comprobante2;
                $row['total']= $gasto->total;
                $results[$id] = $row;
                $id++;
            }
            return $results;
        }catch(Exception $e){
            Log::channel('testing')->info('Log', ['error modelo', $e->getMessage(), $e->getLine()]);
        }


    }
    private static function empleadoInform($empleado_info)
    {
        $descripcion = '';
        $i = 0;
        foreach ($empleado_info as $empleado) {
            $descripcion .= $empleado['nombres'] . ' ' . $empleado['apellidos'];
            $i++;
            if ($i !== count($empleado)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }
    private static function subDetalleInform($subdetalle_info)
    {
        $descripcion = '';
        $i = 0;
        foreach ($subdetalle_info as $sub_detalle) {
            $descripcion .= $sub_detalle['descripcion'];
            $i++;
            if ($i !== count($subdetalle_info)) {
                $descripcion .= ', ';
            }
        }
        return $descripcion;
    }
}
