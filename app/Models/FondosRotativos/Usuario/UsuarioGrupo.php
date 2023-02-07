<?php

namespace App\Models\FondosRotativos\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UsuarioGrupo extends Model
{
    use HasFactory;
    protected $table = 'usuario_grupo';
    protected $primaryKey = 'id';
}
