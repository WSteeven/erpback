<?php

namespace App\Models\Tareas;

use App\Mail\Tareas\EnviarMailCentroCosto;
use App\Models\Cliente;
use App\Models\Tarea;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Throwable;

/**
 * App\Models\Tareas\CentroCosto
 *
 * @property int $id
 * @property string $nombre
 * @property int|null $cliente_id
 * @property bool $activo
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Cliente|null $cliente
 * @property-read Collection<int, SubcentroCosto> $subcentros
 * @property-read int|null $subcentros_count
 * @property-read Collection<int, Tarea> $tareas
 * @property-read int|null $tareas_count
 * @method static Builder|CentroCosto acceptRequest(?array $request = null)
 * @method static Builder|CentroCosto filter(?array $request = null)
 * @method static Builder|CentroCosto ignoreRequest(?array $request = null)
 * @method static Builder|CentroCosto newModelQuery()
 * @method static Builder|CentroCosto newQuery()
 * @method static Builder|CentroCosto query()
 * @method static Builder|CentroCosto setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|CentroCosto setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|CentroCosto setLoadInjectedDetection($load_default_detection)
 * @method static Builder|CentroCosto whereActivo($value)
 * @method static Builder|CentroCosto whereClienteId($value)
 * @method static Builder|CentroCosto whereCreatedAt($value)
 * @method static Builder|CentroCosto whereId($value)
 * @method static Builder|CentroCosto whereNombre($value)
 * @method static Builder|CentroCosto whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CentroCosto extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait, Filterable;

    protected $table = 'tar_centros_costos';
    protected $fillable = ['nombre', 'cliente_id', 'activo'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'activo' => 'boolean',
    ];

    private static array $whiteListFilter = [
        '*',
    ];

    /**
     * Relación uno a muchos.
     * Un centro de costos pertenece a una o varias tareas.
     */
    public function tareas()
    {
        return $this->hasMany(Tarea::class);
    }

    /**
     * Relación uno a uno.
     * Un centro de costos pertenece a un cliente.
     */
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }

    /**
     * Relación uno a muchos.
     * Un centro de costos tiene uno o más subcentros de costos.
     */
    public function subcentros()
    {
        return $this->hasMany(SubcentroCosto::class);
    }


    /**
     * La función crea un nuevo objeto CentroCosto con los parámetros dados y devuelve su id.
     *
     * @param string $nombre El parámetro "nombre" es una cadena que representa el nombre del centro de
     * costo, comunmente asociado con el nombre de la tarea.
     * @param int $cliente_id El parámetro "cliente_id" es un número entero que representa el ID del
     * cliente asociado al centro de costo.
     * @param bool $activo El parámetro "activo" es un valor booleano que indica si el centro de costo
     * está activo o no. Si el valor es verdadero, significa que el centro de costo está activo. Si el
     * valor es falso, significa que el centro de costo está inactivo.
     *
     * @return int el ID del objeto CentroCosto recién creado.
     * @throws Throwable
     */
    public static function crearCentroCosto(string $nombre, int $cliente_id, bool $activo)
    {
        try {
            DB::beginTransaction();
            $centro = CentroCosto::create([
                'nombre' => 'CC-' . $nombre,
                'cliente_id' => $cliente_id,
                'activo' => $activo,
            ]);

            if ($centro) Mail::to('contabilidad@jpconstrucred.com')->send(new EnviarMailCentroCosto($centro));

            DB::commit();
            return $centro->id;
        } catch (Throwable $th) {
            Log::channel('testing')->error('Log', ['Excepcion en Centro de Costo', $th->getMessage()]);
            DB::rollBack();
            throw $th;
        }
    }
}
