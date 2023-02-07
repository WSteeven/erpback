<?php

namespace App\Models\FondosRotativos\Permiso;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RolPermiso extends Model
{
    use HasFactory;
    protected $table = 'rol_permiso';
    protected $primaryKey = 'id';
}
