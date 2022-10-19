<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Grupo extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    protected $table = 'grupos';
    protected $fillable = ['nombre', 'empleado_id', 'estado'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];



    public function empleados()
    {
        return $this->belongsToMany(Empleado::class);
    }

    public function tareas(){
        return $this->belongsToMany(Tarea::class);
    }

}
