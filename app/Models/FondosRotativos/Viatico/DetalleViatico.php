<?php

namespace App\Models\FondosRotativos\Viatico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetalleViatico extends Model
{
    use HasFactory;
    protected $table = 'detalle_viatico';
    protected $primaryKey = 'id';
}
