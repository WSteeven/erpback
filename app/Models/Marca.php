<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Marca extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    protected $table = "marcas";// <-- El nombre personalizado

    protected $fillable = ['nombre'];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];


    /**
     * Relacion uno a muchos
     * Una marca tiene muchos modelos
     */
    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }
}
