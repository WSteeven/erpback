<?php

namespace App\Models;

use App\Models\ComprasProveedores\CategoriaOfertaProveedor;
use App\Models\ComprasProveedores\ContactoProveedor;
use App\Models\ComprasProveedores\DetalleDepartamentoProveedor;
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
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
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

    public function calificacionesDepartamentos()
    {
        return $this->hasMany(DetalleDepartamentoProveedor::class);
    }

    public function ordenesCompras()
    {
        return $this->hasMany(OrdenCompra::class, 'proveedor_id');
    }

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

    private static function obtenerRegistrosCalificacionElegidos(Collection $datos)
    {
        $results = [];
        foreach ($datos as $anio) {
            foreach ($anio as $calificacion){
                if(is_null($calificacion->empleado_id)||is_null($calificacion->calificacion)||is_null($calificacion->fecha_calificacion)){
                return $anio;
                }
            }
        }
        return $results;
    }

    /**
     * @throws Exception
     */
    public static function guardarCalificacion(int $proveedor_id)
    {
        $proveedor = Proveedor::find($proveedor_id);
        $calificaciones_agrupadas_por_anio = $proveedor->calificacionesDepartamentos->groupBy(function ($item){
            return Carbon::parse($item->created_at)->year;
        });
        $calificaciones_elegidas = self::obtenerRegistrosCalificacionElegidos($calificaciones_agrupadas_por_anio);
        $calificaciones = [];
        foreach ($calificaciones_elegidas as $index => $calificacion) {
            if ($calificacion->calificacion != null) {
                $row['departamento_id'] = $calificacion->departamento_id;
                $row['calificacion'] = $calificacion->calificacion;
                $calificaciones[$index] = $row;
            }
        }
        $suma = self::calcularPesos($calificaciones);
        if (count($calificaciones) == count($calificaciones_elegidas))
            $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::CALIFICADO]);
        elseif (empty($calificaciones)) $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::SIN_CALIFICAR]);
        else $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::PARCIAL]);

        $proveedor->refresh();
    }

    /**
     * Actualiza la calificación y el estado de un proveedor en
     * función de las calificaciones otorgadas por los diferentes departamentos.
     *
     * @param int $proveedor_id El parámetro `proveedor_id` es el ID del proveedor para el que desea
     * guardar la calificación.
     * @throws Exception
     */
    public static function guardarCalificacionOld(int $proveedor_id, $recalificacion=false)
    {
        $proveedor = Proveedor::find($proveedor_id);

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

        $proveedor->refresh();
    }


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
        $departamento_financiero = Departamento::where('nombre', Departamento::DEPARTAMENTO_FINANCIERO)->first();

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
                    if ($d['departamento_id'] === $departamento_financiero->id) $suma += ($d['calificacion'] * .4);
                    else $suma += ($d['calificacion'] * .6);
                }
                return $suma;
            case 3:
                foreach ($data as $d) {
                    if ($d['departamento_id'] === $departamento_financiero->id) $suma += ($d['calificacion'] * .3);
                    else $suma += ($d['calificacion'] * .35);
                }
                return $suma;
            default:
                Log::channel('testing')->info('Log', ['Conteo de Calificaciones en metodo calcularPeso', count($data), ' departamento de compras: ', $departamento_financiero->id]);
                throw new Exception('No se puede hacer calculo para más de 3 departamentos', 500);
        }
        return 0;
    }

    public static function consultarProveedorRequireCalificacion(int $id)
    {
        $proveedor = Proveedor::find($id);
        switch ($proveedor->estado_calificado) { // en todos los casos que el proveedor no esté calificado se devolverá false ya que primero debe completarse la primera calificación para poder recalificar
            case Proveedor::SIN_CALIFICAR:
            case Proveedor::SIN_CONFIGURAR:
            case Proveedor::PARCIAL:
                return false;
            default: // Significa que el proveedor ha sido calificado
//                $ultimos_registros_calificacion = $proveedor->calificacionesDepartamentos()->groupBy('departamento_id')->orderBy('id', 'desc')->get();
                $registro_calificacion_no_calificado = $proveedor->calificacionesDepartamentos()->whereNull('fecha_calificacion')->count();
                if ($registro_calificacion_no_calificado > 0)
                    return true;
                return false;
        }
    }
}
