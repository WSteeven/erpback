<?php

namespace App\Models\FondosRotativos\Ubicacion;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Parroquia extends Model
{
    use HasFactory;
    protected $table = 'parroquia';
    protected $primaryKey = 'id';
}
