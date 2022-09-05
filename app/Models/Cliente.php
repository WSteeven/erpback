<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Cliente extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = "clientes";
    protected $fillable = ['empresa_id', 'parroquia_id', 'requiere_bodega', 'estado'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'requiere_bodega' => 'boolean'
    ];

    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class);
    }

    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    public function nombres()
    {
        return $this->belongsToMany(NombreProducto::class);
    }
}
