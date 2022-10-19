<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;

class Contacto extends Model
{
    use HasFactory, UppercaseValuesTrait;

    protected $table = "contactos";
    protected $fillable = [
        "nombres", "apellidos",
        "identificador",
        "nombres",
        "apellidos",
        "celular",
        "provincia",
        "ciudad",
        "parroquia",
        "direccion",
        "referencias",
        "coordenadas",
    ];
}
