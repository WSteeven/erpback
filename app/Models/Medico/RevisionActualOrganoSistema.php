<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;


class RevisionActualOrganoSistema extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_revisiones_organos_sistemas';
    protected $fillable = [
        'organo_sistema_id',
        'descripcion',
        'revisionable_id',
        'revisionable_type',
    ];

    public function organoSistema()
    {
        return $this->hasOne(SistemaOrganico::class);
    }

    public function revisionable()
    {
        return $this->morphTo();
    }
}
