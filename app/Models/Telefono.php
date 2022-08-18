<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Telefono extends Model
{
    use HasFactory;
    protected $table = "telefonos";// <-- El nombre personalizado

    public function telefonable()
    {
        return $this->morphTo();
    }

    /*
    * Get the user that owns the phone
    */
    /* public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    } */
}
