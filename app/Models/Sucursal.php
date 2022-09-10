<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Sucursal extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = "sucursales";
    protected $fillable = ['lugar', 'telefono', 'correo'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relacion uno a muchos
     * Obtener los control de stock para una sucursal 
     */
    public function control_stocks()
    {
        return $this->hasMany(ControlStock::class);
    }

    public function empleados()
    {
        return $this->hasMany(Empleado::class);
    }

    public function perchas()
    {
        return $this->hasMany(Percha::class);
    }
}
