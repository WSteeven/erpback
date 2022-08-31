<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory, UppercaseValuesTrait;
    protected $table = 'empresas';
    protected $fillable = ['identificacion','tipo_contribuyente','razon_social','nombre_comercial', 'correo','direccion'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    const NATURAL = 'NATURAL'; //persona natural
    const PRIVADA = 'PRIVADA'; //sociedad privada
    const PUBLICA = 'PUBLICA'; //sociedad publica

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
}
