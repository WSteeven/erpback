<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Empresa extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = 'empresas';
    protected $fillable = [
        'identificacion',
        'tipo_contribuyente',
        'razon_social',
        'nombre_comercial',
        'celular',
        'telefono',
        'correo',
        'canton_id',
        'ciudad',
        'direccion',
        'agente_retencion',
        'tipo_negocio',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    private static $whiteListFilter = ['*'];

    const NATURAL = 'NATURAL'; //persona natural
    const PRIVADA = 'PRIVADA'; //sociedad privada
    const PUBLICA = 'PUBLICA'; //sociedad publica

    //tipos de negocio
    const RIMPE_IVA = 'RIMPE CON IVA';
    const RIMPE_SIN_IVA = 'RIMPE SIN IVA';
    /**
     * Relacion uno a uno
     */
    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    public function proveedor()
    {
        return $this->hasOne(Proveedor::class);
    }
    /*
    public function telefonos(){
        return $this->hasMany(Telefono::class);
    } */

    //Relacion uno a muchos polimorfica
    public function telefonos()
    {
        return $this->morphMany('App\Models\Telefono', 'telefonable');
    }
    /**
     * Relación uno a muchos(inversa).
     */
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }
}
