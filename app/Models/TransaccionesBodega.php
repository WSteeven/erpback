<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TransaccionesBodega extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    public $table = 'transacciones_bodega';
    public $fillable = [
        'justificacion',
        'fecha_limite',
        'solicitante_id',
        'subtipo_id',
        'sucursal_id',
        'per_autoriza_id',
        'per_entrega_id',
        'lugar_destino',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    /* Una transaccion tiene varios estados de autorizacion durante su ciclo de vida */
    public function autorizaciones()
    {
        return $this->belongsToMany(Autorizacion::class, 'tiempo_autorizacion_transaccion', 'transaccion_id', 'autorizacion_id')->withPivot('observacion')->withTimestamps();
    }

    /* Una transaccion tiene varios estados durante su ciclo de vida */
    public function estados()
    {
        return $this->belongsToMany(EstadosTransaccion::class, 'tiempo_estado_transaccion', 'transaccion_id', 'estado_id')->withPivot('observacion')->withTimestamps();//->wherePivot('created_at','orderBy','desc');

    }

    //Una transaccion tiene varios productos solicitados
    public function productos()
    {
        return $this->belongsToMany(Producto::class);
    }

    /**
     * Relacion uno a muchos (inversa)
     * Una o varias transacciones pertenece a un solicitante 
     */
    public function solicitante()
    {
        return $this->belongsTo(User::class);
    }

    /* Una o varias transacciones tienen un solo tipo de transaccion*/
    public function tipoTransaccion()
    {
        return $this->hasOne(TipoTransaccion::class);
    }
    /**
     * Obtener los movimientos para la transaccion
     */
    public function movimientos()
    {
        return $this->hasMany(MovimientosProductos::class);
    }

    /* Funciones */
    /**
     * Obtener la ultima autorizacion de una transaccion 
     */
    public static function obtenerUltimaAutorizacion($transaccion_id, $autorizacion_id){
        $transaccion = TransaccionesBodega::find($transaccion_id);
        $autorizacion= Autorizacion::find($autorizacion_id);
        /* $ultima_act = Autorizacion::with(['autorizaciones'=>function ($query) use($id){
            $query->where('transaccion_id', $id);
        }])->whereHas('autorizacion_id', function($query) use($id){
            $query->where('transaccion_id', $id);
        })->get(); */
        $ultima_act = $transaccion->autorizaciones()
            ->orderBy('created_at', 'asc')->first();

        //$ultima = TiemposAutorizacionTransaccion::where('transaccion_id', $transaccion->id)->orderBy('created_at', 'desc')->limit(1)->get();
        $ultima = TiemposAutorizacionTransaccion::where('transaccion_id', $transaccion->id)->latest()->limit(1)->get();
        //return [$transaccion->nombre, $ultima->observacion];
        $aut=null;

        if(!$ultima){
            //
        }

        foreach($ultima as $ult){
            $aut = $ult->autorizacion_id;
            $obs = $ult->observacion;
        }
        Log::channel('testing')->info('Log', ['ultima?:', $aut]);
        $nombre = Autorizacion::find($aut);
        return [
            $nombre->nombre, 
            $obs];
    }
}
