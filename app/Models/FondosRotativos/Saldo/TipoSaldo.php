<?php

namespace App\Models\FondosRotativos\Saldo;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TipoSaldo extends Model
{
    use HasFactory;
    protected $table = 'tipo_saldo';
    protected $primaryKey = 'id';
}
