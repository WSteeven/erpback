<?php

namespace App\Models\FondosRotativos\Viatico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoFondo extends Model
{
    use HasFactory;
    protected $table = 'tipo_fondo';
    protected $primaryKey = 'id';
}
