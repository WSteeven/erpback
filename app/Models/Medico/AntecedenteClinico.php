<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class AntecedenteClinico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table  = 'med_antecedentes_clinicos';
    protected $fillable = [
        'descripcion',
        'antecedentable_id',
        'antecedentable_type',
    ];
    private static $whiteListFilter = ['*'];

    // Relación polimorfica
    public function antecedentable()
    {
        return $this->morphTo();
    }
}
