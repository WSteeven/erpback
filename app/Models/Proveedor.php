<?php

namespace App\Models;

use App\Models\ComprasProveedores\CategoriaOfertaProveedor;
use App\Models\ComprasProveedores\ContactoProveedor;
use App\Models\ComprasProveedores\OfertaProveedor;
use App\Models\ComprasProveedores\OrdenCompra;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Proveedor
 *
 * @property int $id
 * @property int $empresa_id
 * @property bool $estado
 * @property string|null $causa_inactivacion
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property string $sucursal
 * @property int|null $parroquia_id
 * @property string $direccion
 * @property string|null $correo
 * @property string|null $celular
 * @property string|null $telefono
 * @property float|null $calificacion
 * @property string|null $estado_calificado
 * @property string|null $referencia
 * @property string|null $forma_pago
 * @property string|null $plazo_credito
 * @property string|null $anticipos
 * @property int $notificado
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, CategoriaOfertaProveedor> $categorias_ofertadas
 * @property-read int|null $categorias_ofertadas_count
 * @property-read Collection<int, ContactoProveedor> $contactos
 * @property-read int|null $contactos_count
 * @property-read Collection<int, Departamento> $departamentos_califican
 * @property-read int|null $departamentos_califican_count
 * @property-read Empresa|null $empresa
 * @property-read Notificacion|null $latestNotificacion
 * @property-read Collection<int, Notificacion> $notificaciones
 * @property-read int|null $notificaciones_count
 * @property-read Collection<int, OrdenCompra> $ordenesCompras
 * @property-read int|null $ordenes_compras_count
 * @property-read Parroquia|null $parroquia
 * @property-read Collection<int, OfertaProveedor> $servicios_ofertados
 * @property-read int|null $servicios_ofertados_count
 * @method static Builder|Proveedor acceptRequest(?array $request = null)
 * @method static Builder|Proveedor filter(?array $request = null)
 * @method static Builder|Proveedor ignoreRequest(?array $request = null)
 * @method static Builder|Proveedor newModelQuery()
 * @method static Builder|Proveedor newQuery()
 * @method static Builder|Proveedor query()
 * @method static Builder|Proveedor setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Proveedor setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Proveedor setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Proveedor whereAnticipos($value)
 * @method static Builder|Proveedor whereCalificacion($value)
 * @method static Builder|Proveedor whereCausaInactivacion($value)
 * @method static Builder|Proveedor whereCelular($value)
 * @method static Builder|Proveedor whereCorreo($value)
 * @method static Builder|Proveedor whereCreatedAt($value)
 * @method static Builder|Proveedor whereDireccion($value)
 * @method static Builder|Proveedor whereEmpresaId($value)
 * @method static Builder|Proveedor whereEstado($value)
 * @method static Builder|Proveedor whereEstadoCalificado($value)
 * @method static Builder|Proveedor whereFormaPago($value)
 * @method static Builder|Proveedor whereId($value)
 * @method static Builder|Proveedor whereNotificado($value)
 * @method static Builder|Proveedor whereParroquiaId($value)
 * @method static Builder|Proveedor wherePlazoCredito($value)
 * @method static Builder|Proveedor whereReferencia($value)
 * @method static Builder|Proveedor whereSucursal($value)
 * @method static Builder|Proveedor whereTelefono($value)
 * @method static Builder|Proveedor whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Proveedor extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = "proveedores";
    protected $fillable = [
        "empresa_id",
        "estado",
        "sucursal",
        "parroquia_id",
        "direccion",
        "celular",
        "telefono",
        "calificacion",
        "estado_calificado",
        "forma_pago",
        "referencia",
        "plazo_credito",
        "anticipos",
        "correo",
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean',
    ];

    const CALIFICADO = 'CALIFICADO'; // cuando está calificado por todos los departamentos
    const PARCIAL = 'PARCIAL';  // cuando al menos un departamento ha calificado el proveedor
    const SIN_CALIFICAR = 'SIN CALIFICAR';  // cuando aún no califica ningun departamento
    const SIN_CONFIGURAR = 'SIN CONFIGURAR'; //cuando no se ha enlazado departamentos calificadores


    private static array $whiteListFilter = [
        '*',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // public function canton(){
    //     return $this->belongsTo(Canton::class)
    // }
    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class);
    }

//    public function contactos()
//    {
//        return $this->hasMany(ContactoProveedor::class);
//    }

    public function servicios_ofertados()
    {
        return $this->belongsToMany(OfertaProveedor::class, 'detalle_oferta_proveedor', 'proveedor_id', 'oferta_id')
            ->withTimestamps();
    }

    public function categorias_ofertadas()
    {
        return $this->belongsToMany(CategoriaOfertaProveedor::class, 'detalle_categoria_proveedor', 'proveedor_id', 'categoria_id')
            ->withTimestamps();
    }

    public function departamentos_califican()
    {
        return $this->belongsToMany(Departamento::class, 'detalle_departamento_proveedor', 'proveedor_id', 'departamento_id')
            ->withPivot(['id', 'empleado_id', 'calificacion', 'fecha_calificacion'])
            ->withTimestamps();
    }

//    public function ordenesCompras()
//    {
//        return $this->hasMany(OrdenCompra::class, 'proveedor_id');
//    }

    /**
     * Relacion polimorfica a una notificacion.
     * Un proveedor puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

    /**
     * Actualiza la calificación y el estado de un proveedor en
     * función de las calificaciones otorgadas por los diferentes departamentos.
     *
     * @param int $proveedor_id El parámetro `proveedor_id` es el ID del proveedor para el que desea
     * guardar la calificación.
     * @throws Exception
     */
    public static function guardarCalificacion(int $proveedor_id)
    {
        $proveedor = Proveedor::find($proveedor_id);
//        if ($proveedor->departamentos_califican->count() == 2) {
            $calificaciones = [];
            foreach ($proveedor->departamentos_califican as $index => $departamento) {
                if ($departamento->pivot->calificacion != null) {
                    $row['departamento_id'] = $departamento->id;
                    $row['calificacion'] = $departamento->pivot->calificacion;
                    $calificaciones[$index] = $row;
                }
            }
            $suma = self::calcularPesos($calificaciones);
            if (count($calificaciones) == $proveedor->departamentos_califican->count())
                $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::CALIFICADO]);
            elseif (empty($calificaciones)) $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::SIN_CALIFICAR]);
            else $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::PARCIAL]);
//        }
//        if ($proveedor->departamentos_califican->count() == 3) {
//            $calificaciones = [];
//            foreach ($proveedor->departamentos_califican as $index => $departamento) {
//                if ($departamento->pivot->calificacion != null) {
//                    $row['departamento_id'] = $departamento->id;
//                    $row['calificacion'] = $departamento->pivot->calificacion;
//                    $calificaciones[$index] = $row;
//                }
//            }
//            $suma = self::calcularPesos($calificaciones);
//            if (count($calificaciones) == $proveedor->departamentos_califican->count())
//                $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::CALIFICADO]);
//            elseif (empty($calificaciones)) $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::SIN_CALIFICAR]);
//            else $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::PARCIAL]);
//        }
        $proveedor->refresh();
    }

    // public static function obtenerCalificacion($proveedor_id)
    // {
    //     $proveedor = Proveedor::find($proveedor_id);
    //     if ($proveedor->departamentos_califican->count() == 2) {
    //         $calificaciones = [];
    //         foreach ($proveedor->departamentos_califican as $index => $departamento) {
    //             if ($departamento->pivot->calificacion != null) {
    //                 $row['departamento_id'] = $departamento->id;
    //                 $row['calificacion'] = $departamento->pivot->calificacion;
    //                 $calificaciones[$index] = $row;
    //             }
    //         }
    //         $suma = self::calcularPesos($calificaciones);
    //         if (count($calificaciones) == $proveedor->departamentos_califican->count()) {
    //             return [$suma, 'CALIFICADO'];
    //         } elseif (empty($calificaciones)) return [$suma, 'SIN CALIFICAR'];
    //         else return [$suma, 'PARCIAL'];
    //         Log::channel('testing')->info('Log', ['Calificaciones', $calificaciones, 'Suma de notas: ', $suma]);
    //     }
    //     if ($proveedor->departamentos_califican->count() == 3) {
    //         // Log::channel('testing')->info('Log', ['Proveedor tiene 3 departamentos']);
    //         $calificaciones = [];
    //         foreach ($proveedor->departamentos_califican as $index => $departamento) {
    //             if ($departamento->pivot->calificacion != null) {
    //                 $row['departamento_id'] = $departamento->id;
    //                 $row['calificacion'] = $departamento->pivot->calificacion;
    //                 $calificaciones[$index] = $row;
    //             }
    //         }
    //         $suma = self::calcularPesos($calificaciones);
    //         if (count($calificaciones) == $proveedor->departamentos_califican->count()) {
    //             return [$suma, 'CALIFICADO'];
    //         } elseif (empty($calificaciones)) return [$suma, 'SIN CALIFICAR'];
    //         else return [$suma, 'PARCIAL'];
    //     }
    //     // Log::channel('testing')->info('Log', ['Proveedor tiene ' . $proveedor->departamentos_califican->count() . ' departamentos']);
    //     return [0, 'SIN CONFIGURAR'];
    // }

    /**
     * La función "calcularPesos" calcula los pesos en base al número de departamentos y sus
     * respectivas valoraciones.
     * Si son 3 departamentos la distribucion de pesos es la siguiente:
     *     Area especializada 1 = 35 %
     *     Area especializada 2 = 35 %
     *     Area financiera(compras)     = 30 %
     * Si son 2 departamentos la distribucion de pesos es la siguiente:
     *     Area especializada  = 60 %
     *     Area financiera(compras)     = 40 %
     *
     * @param array|Collection $data El parámetro `$data` es una matriz que contiene información sobre los departamentos
     * y sus respectivas calificaciones. Cada elemento de la matriz representa un departamento y tiene
     * la siguiente estructura: [departamento_id, calificacion]
     *
     * @return float|int suma calculada de pesos basada en los datos dados.
     * @throws Exception
     */
    private static function calcularPesos(Collection|array $data)
    {
        $user_compras = User::where('email', 'yloja@jpconstrucred.com')->with('empleado')->whereHas("roles", function ($q) {
            $q->where("name", User::ROL_COMPRAS);
        })->first();
        // Log::channel('testing')->info('Log', ['Conteo de Calificaciones', count($data), ' departamento de compras: ', $user_compras->empleado->departamento_id]);
        $suma = 0;
        switch (count($data)) {
            case 0:
                return 0;
            case 1:
                foreach ($data as $d)
                    return $d['calificacion'];
                break;
            case 2:
                foreach ($data as $d) {
                    if ($d['departamento_id'] === $user_compras->empleado->departamento_id) $suma += ($d['calificacion'] * .4);
                    else $suma += ($d['calificacion'] * .6);
                }
                return $suma;
            case 3:
                foreach ($data as $d) {
                    if ($d['departamento_id'] === $user_compras->empleado->departamento_id) $suma += ($d['calificacion'] * .3);
                    else $suma += ($d['calificacion'] * .35);
                }
                return $suma;
            default:
                Log::channel('testing')->info('Log', ['Conteo de Calificaciones en metodo calcularPeso', count($data), ' departamento de compras: ', $user_compras->empleado->departamento_id]);
                throw new Exception('No se puede hacer calculo para más de 3 departamentos', 500);
        }
        return 0;
    }
}
