<?php

namespace App\Models\FondosRotativos\Viatico;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SaldoGrupo extends Model
{
    use HasFactory;
    protected $table = 'saldo_grupo';
    protected $primaryKey = 'id';
    protected $fillable = [
        'fecha',
        'tipo_saldo',
        'id_saldo',
        'id_tipo_fondo',
        'descripcion_saldo',
        'saldo_anterior',
        'saldo_depositado',
        'saldo_actual',
        'fecha_inicio',
        'fecha_fin',
        'id_usuario',
        'id_estatus',
        'transcriptor',
        'fecha_trans',
    ];
    private static $whiteListFilter = [
        'fecha_inicio',
    ];
}
