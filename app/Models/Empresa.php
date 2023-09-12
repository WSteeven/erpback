<?php

namespace App\Models;

use App\Models\ComprasProveedores\ContactoProveedor;
use App\Models\ComprasProveedores\DatoBancarioProveedor;
use App\Models\ComprasProveedores\LogisticaProveedor;
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
        // 'celular',
        // 'telefono',
        'correo',
        'canton_id',
        // 'ciudad',
        'direccion',
        'agente_retencion',
        'regimen_tributario',
        'sitio_web',
        'lleva_contabilidad',
        'contribuyente_especial',
        'actividad_economica',
        'representante_legal',
        'identificacion_representante',
        'antiguedad_proveedor',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'agente_retencion'=>'boolean',
        'lleva_contabilidad'=>'boolean',
        'contribuyente_especial'=>'boolean',
    ];

    private static $whiteListFilter = ['*'];

    //Tipo de contribuyente
    const NATURAL = 'PERSONA NATURAL'; //persona natural
    const SOCIEDAD = 'SOCIEDAD'; //sociedad privada
    const PUBLICA = 'PUBLICA'; //sociedad publica

    //regimen tributario
    const RIMPE_EMPRENDEDOR = 'RIMPE EMPRENDEDOR';
    const RIMPE_NEGOCIOS_POPULARES = 'RIMPE NEGOCIOS POPULARES';
    const GENERAL = 'GENERAL';
    /**
     * Relacion uno a uno
     */
    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    public function proveedores()
    {
        return $this->hasMany(Proveedor::class);
    }
    public function contactos()
    {
        return $this->hasMany(ContactoProveedor::class);
    }
    public function datos_bancarios()
    {
        return $this->hasMany(DatoBancarioProveedor::class);
    }
    public function logistica()
    {
        return $this->hasOne(LogisticaProveedor::class);
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

     /**
     * Relacion polimorfica con Archivos uno a muchos.
     * 
     */
    public function archivos(){
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
