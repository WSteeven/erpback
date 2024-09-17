<?php

namespace App\Models;

use Carbon\Carbon;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;
use Throwable;

/**
 * App\Models\CorreoEnviado
 *
 * @property int $id
 * @property int|null $empleado_envia_id
 * @property string $remitente
 * @property string $correo_destinatario
 * @property string $fecha_hora
 * @property string $asunto
 * @property int $notificable_id
 * @property string $notificable_type
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Model|Eloquent $notificable
 * @method static Builder|CorreoEnviado acceptRequest(?array $request = null)
 * @method static Builder|CorreoEnviado filter(?array $request = null)
 * @method static Builder|CorreoEnviado ignoreRequest(?array $request = null)
 * @method static Builder|CorreoEnviado newModelQuery()
 * @method static Builder|CorreoEnviado newQuery()
 * @method static Builder|CorreoEnviado query()
 * @method static Builder|CorreoEnviado setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|CorreoEnviado setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|CorreoEnviado setLoadInjectedDetection($load_default_detection)
 * @method static Builder|CorreoEnviado whereAsunto($value)
 * @method static Builder|CorreoEnviado whereCorreoDestinatario($value)
 * @method static Builder|CorreoEnviado whereCreatedAt($value)
 * @method static Builder|CorreoEnviado whereEmpleadoEnviaId($value)
 * @method static Builder|CorreoEnviado whereFechaHora($value)
 * @method static Builder|CorreoEnviado whereId($value)
 * @method static Builder|CorreoEnviado whereNotificableId($value)
 * @method static Builder|CorreoEnviado whereNotificableType($value)
 * @method static Builder|CorreoEnviado whereRemitente($value)
 * @method static Builder|CorreoEnviado whereUpdatedAt($value)
 * @mixin Eloquent
 */
class CorreoEnviado extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'correos_enviados';
    protected $fillable =[
        'empleado_envia_id',
        'remitente', //persona que envía
        'correo_destinatario',
        'fecha_hora',
        'asunto',
    ];

    //Relación polimorfica
    public function notificable()
    {
        return $this->morphTo();
    }


    /**
     * La función `crearCorreoEnviado` crea un nuevo registro de correo electrónico enviado en la base
     * de datos con el remitente, el destinatario, el asunto y la entidad relacionada proporcionados.
     *
     * @param string $remitente El parámetro "remitente" representa el remitente del correo electrónico.
     * @param string $destinatario El parámetro "destinatario" representa el destinatario del correo
     * electrónico. Es la dirección de correo electrónico de la persona o entidad a quien se envía el
     * correo electrónico.
     * @param string $asunto El parámetro "asunto" representa el asunto del correo electrónico. Es una
     * cadena que contiene la línea de asunto del mensaje de correo electrónico.
     * @param Model $entidad El parámetro "entidad" es una instancia de una clase de modelo. Se utiliza
     * para crear un nuevo registro en la tabla de "correos" asociada al modelo dado.
     *
     * @return CorreoEnviado $correo el objeto `` creado.
     * @throws Exception|Throwable
     */
    public static function crearCorreoEnviado(string $remitente,string  $destinatario,string $asunto, Model $entidad){
        try {
            DB::beginTransaction();
            $correo = $entidad->correos()->create([
                'empleado_envia_id'=>auth()->user()->empleado->id,
                'remitente' => $remitente,
                'correo_destinatario' => $destinatario,
                'fecha_hora' => Carbon::now(),
                'asunto' =>$asunto

            ]);
            DB::commit();
            return $correo ;
        } catch (Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage().'. [LINE CODE ERROR]: '.$th->getLine(), $th->getCode());
        }
    }
}
