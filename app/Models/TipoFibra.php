<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class TipoFibra extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;

    protected $table = 'tipo_fibras';
    protected $fillable = ['nombre'];


    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
    ];

    /**
     * Relacion uno a uno.
     * Un tipo de fibra esta en 1 fibra
     */
    public function fibra(){
        return $this->hasOne(Fibra::class);
    }
}
