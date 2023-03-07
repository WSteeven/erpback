<?php

namespace App\Models\FondosRotativos\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Estatus extends Model
{
    use HasFactory;
    protected $table = 'estatus';
    protected $primaryKey = 'id';
}
