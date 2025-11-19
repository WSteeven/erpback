<?php

namespace App\Models;

use App\Models\Tareas\Etapa;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Throwable;

/**
 * App\Models\Sucursal
 *
 * @property int $id
 * @property string $lugar
 * @property int|null $cliente_id
 * @property string|null $telefono
 * @property int|null $extension
 * @property string|null $correo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, ActivoFijo> $activos
 * @property-read int|null $activos_count
 * @property-read User|null $administrador
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Cliente|null $cliente
 * @property-read ControlStock|null $control_stocks
 * @property-read Collection<int, Devolucion> $devoluciones
 * @property-read int|null $devoluciones_count
 * @property-read Collection<int, Empleado> $empleados
 * @property-read int|null $empleados_count
 * @property-read Inventario|null $inventarios
 * @property-read Collection<int, Pedido> $pedidos
 * @property-read int|null $pedidos_count
 * @property-read int|null $perchas_count
 * @property-read Collection<int, TransaccionBodega> $transacciones
 * @property-read int|null $transacciones_count
 * @property-read Collection<int, Transferencia> $transferencias
 * @property-read int|null $transferencias_count

 * @property-read int|null $traspasos_count
 * @method static Builder|Sucursal acceptRequest(?array $request = null)
 * @method static Builder|Sucursal filter(?array $request = null)
 * @method static Builder|Sucursal ignoreRequest(?array $request = null)
 * @method static Builder|Sucursal newModelQuery()
 * @method static Builder|Sucursal newQuery()
 * @method static Builder|Sucursal query()
 * @method static Builder|Sucursal setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Sucursal setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Sucursal setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Sucursal whereClienteId($value)
 * @method static Builder|Sucursal whereCorreo($value)
 * @method static Builder|Sucursal whereCreatedAt($value)
 * @method static Builder|Sucursal whereExtension($value)
 * @method static Builder|Sucursal whereId($value)
 * @method static Builder|Sucursal whereLugar($value)
 * @method static Builder|Sucursal whereTelefono($value)
 * @method static Builder|Sucursal whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Sucursal extends Model implements Auditable
{
    use HasFactory;
    use UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = "sucursales";
    protected $fillable = ['lugar', 'telefono', 'extension', 'correo', 'cliente_id', 'activo'];
    // protected $fillable = ['lugar', 'telefono', 'correo', 'administrador_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean'
    ];

    private static array $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * La función "clientes" devuelve una relación entre el objeto actual y la clase "Cliente".
     *
     * @return BelongsTo relación entre el modelo actual y el modelo Cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relacion uno a muchos
     * Obtener los control de stock para una sucursal
     */
    public function control_stocks()
    {
        return $this->hasOne(ControlStock::class);
    }

    /**
     * Relación uno a muchos.
     * Una sucursal tiene muchos empleados
     */
    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }


    /**
     * Relacion uno a uno
     * Una sucursal tiene muchas inventarios
     */
    public function inventarios()
    {
        return $this->hasOne(Inventario::class);
    }

    /**
     * Relación uno a muchos.
     * Una sucursal tiene uno o muchos activos fijos.
     */
    public function activos()
    {
        return $this->hasMany(ActivoFijo::class);
    }

    /**
     * Relacion uno a muchos.
     * En una sucursal se realizan varias transacciones
     */
    public function transacciones()
    {
        return $this->hasMany(TransaccionBodega::class);
    }

    /**
     * Relacion uno a muchos.
     * En una sucursal se realizan varias devoluciones
     */
    public function devoluciones()
    {
        return $this->hasMany(Devolucion::class);
    }



    /**
     * Relación uno a muchos .
     * Una sucursal puede uno o varios pedidos
     */
    public function pedidos()
    {
        return $this->hasMany(Pedido::class);
    }

    /**
     * Relación uno a muchos .
     * Una sucursal puede tener una o varias transferencias
     */
    public function transferencias()
    {
        return $this->hasMany(Transferencia::class);
    }

    /**
     * Relación uno a uno.
     * Una sucursal tiene un adminitrador
     */
    public function administrador()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     * @throws Throwable
     */
    public static function crearSucursalProyectoEtapa(Etapa $etapa)
    {
        try {
            DB::beginTransaction();
            $sucursal = Sucursal::create([
                'lugar' => $etapa->proyecto->canton->canton . ' - ' . $etapa->nombre . ' - ' . $etapa->proyecto->cliente->empresa->razon_social,
                'cliente_id' => $etapa->proyecto->cliente_id
            ]);

            DB::commit();
            return $sucursal;
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }

    /**
     * @throws Throwable
     */
    public static function modificarSucursalProyectoEtapa(Etapa $etapa, $nombre)
    {
        try {
            DB::beginTransaction();
            $sucursal = Sucursal::where('lugar', 'like', '%' . $etapa->proyecto->canton->canton . ' - ' . $nombre . ' - ' . $etapa->proyecto->cliente->empresa->razon_social . '%')->first();
            if ($sucursal) {
                $sucursal->update([
                    'lugar' => $etapa->proyecto->canton->canton . ' - ' . $etapa->nombre . ' - ' . $etapa->proyecto->cliente->empresa->razon_social,
                    'cliente_id' => $etapa->proyecto->cliente_id
                ]);
            }

            DB::commit();
            return $sucursal;
        } catch (Throwable $th) {
            DB::rollBack();
            throw $th;
        }
    }
    // public static function eliminarSucursalProyectoEtapa(Etapa $etapa){
    //     try {
    //         DB::beginTransaction();
    //         $sucursal  = Sucursal::create([
    //             'lugar' => $etapa->proyecto->canton->canton .' - '.$etapa->nombre .' - '.$etapa->proyecto->cliente->empresa->razon_social,
    //             'cliente_id'=>$etapa->proyecto->cliente_id
    //         ]);

    //         DB::commit();
    //         return $sucursal;
    //     } catch (\Throwable $th) {
    //         DB::rollBack();
    //         throw $th;
    //     }
    // }
}
