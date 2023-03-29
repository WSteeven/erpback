<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Notificacion extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;
    protected $table = 'notificaciones';
    protected $fillable = [
        'mensaje',
        'link',
        'per_originador_id',
        'per_destinatario_id',
        'leida',
        'tipo_notificacion',
    ];
    /* protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ]; */

    private static $whiteListFilter = ['*'];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */

    /**
     * Relación uno a muchos(inversa).
     * Una persona puede ser originador de una notificacion a la vez.
     */
    public function originador()
    {
        return $this->belongsTo(Empleado::class, 'per_originador_id', 'id');
    }
    /**
     * Relación uno a muchos(inversa).
     * Una persona puede ser destinatario de una notificacion a la vez.
     */
    public function destinatario()
    {
        return $this->belongsTo(Empleado::class, 'per_destinatario_id', 'id');
    }

    /**
     * It creates a notification in the database
     * 
     * @param mensaje The message to be displayed
     * @param ruta the route to the page where the notification will be displayed
     * @param tipo 1 =&gt; 'notificacion', 2 =&gt; 'mensaje', 3 =&gt; 'alerta'
     * @param originador the user who created the notification
     * @param destinatario The user who will receive the notification
     * 
     * @return The return is a collection of notifications.
     */
    public static function crearNotificacion($mensaje,$ruta,$tipo, $originador, $destinatario){
        $notificacion = Notificacion::create([
            'mensaje'=>$mensaje,
            'link'=>$ruta,
            'per_originador_id'=>$originador,
            'per_destinatario_id'=>$destinatario,
            'tipo_notificacion'=>$tipo,
        ]);
        return $notificacion;
    }


}
