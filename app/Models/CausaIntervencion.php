<?php

namespace App\Models;

use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class CausaIntervencion extends Model
{
    use HasFactory, AuditableModel, Filterable, UppercaseValuesTrait;

    protected $table = 'causas_intervenciones';
    protected $fillable = ['nombre', 'tipo_trabajo_id'];
}
