<?php

namespace App\Models\FondosRotativos\Viatico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoGrupo extends Model
{
    use HasFactory;
    protected $table = 'saldo_grupo';
    protected $primaryKey = 'id';
}
