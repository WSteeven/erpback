<?php

namespace App\Models\FondosRotativos\Viatico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubDetalleViatico extends Model
{
    use HasFactory;
    protected $table = 'sub_detalle_viatico';
    protected $primaryKey = 'id';
}
