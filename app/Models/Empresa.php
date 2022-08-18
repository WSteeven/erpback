<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empresa extends Model
{
    use HasFactory;
    public $table = 'empresas';

    const NATURAL = 'NATURAL';
    const JURIDICA = 'JURIDICA';

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
