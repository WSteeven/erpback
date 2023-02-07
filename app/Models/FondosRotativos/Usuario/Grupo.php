<?php

namespace App\Models\FondosRotativos\Usuario;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Grupo extends Model
{
    use HasFactory;
    protected $table = 'grupo';
    protected $primaryKey = 'id';
}
