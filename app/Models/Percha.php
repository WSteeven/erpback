<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Percha extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    
    protected $table = 'perchas';
    protected $fillable = ['nombre', 'sucursal_id'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];



    public function localidad()
    {
        return $this->belongsTo(Localidad::class);
    }

    public function pisos()
    {
        return $this->belongsToMany(Piso::class);
    }

    public function ubicaciones()
    {
        return $this->hasMany(Ubicacion::class);
    }
}
