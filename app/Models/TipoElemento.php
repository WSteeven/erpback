<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\UppercaseValuesTrait;

class TipoElemento extends Model
{
    use HasFactory, UppercaseValuesTrait;

    protected $table = "tipos_elementos";
    protected $fillable = [
        'nombre',
    ];
}
