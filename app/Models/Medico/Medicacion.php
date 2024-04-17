<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class Medicacion extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_medicaciones';
    protected $fillable = [
        'nombre',
        'cantidad',
        'medicable_id',
        'medicable_type',
        'actividable_type'
    ];
    public function medicable()
    {
        return $this->morphTo();
    }
}
