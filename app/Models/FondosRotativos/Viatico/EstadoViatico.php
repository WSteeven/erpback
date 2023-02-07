<?php

namespace App\Models\FondosRotativos\Viatico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstadoViatico extends Model
{
    use HasFactory;
    protected $table = 'estado_viatico';
    protected $primaryKey = 'id';
}
