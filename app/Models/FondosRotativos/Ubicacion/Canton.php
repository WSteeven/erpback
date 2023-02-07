<?php

namespace App\Models\FondosRotativos\Ubicacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Canton extends Model
{
    use HasFactory;
    protected $table = 'canton';
    protected $primaryKey = 'id';
}
