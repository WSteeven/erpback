<?php

namespace App\Models;

use Carbon\Carbon;
use DateTime;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

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
     * @param string remitente El parámetro "remitente" representa el remitente del correo electrónico.
     * @param string destinatario El parámetro "destinatario" representa el destinatario del correo
     * electrónico. Es la dirección de correo electrónico de la persona o entidad a quien se envía el
     * correo electrónico.
     * @param string asunto El parámetro "asunto" representa el asunto del correo electrónico. Es una
     * cadena que contiene la línea de asunto del mensaje de correo electrónico.
     * @param Model entidad El parámetro "entidad" es una instancia de una clase de modelo. Se utiliza
     * para crear un nuevo registro en la tabla de "correos" asociada al modelo dado.
     *
     * @return el objeto `` creado.
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
        } catch (\Throwable $th) {
            DB::rollBack();
            throw new Exception($th->getMessage().'. [LINE CODE ERROR]: '.$th->getLine(), $th->getCode());
        }
    }
}
