<?php

namespace App\Models\Medico;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class HabitoToxico extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;

    protected $table = 'med_habitos_toxicos';
    protected $fillable = [
        'tipo_habito_toxico_id',
        'tiempo_consumo',
        'preocupacional_id'
    ];
    public function tipoHabitoToxico(){
        return $this->hasOne(TipoHabitoToxico::class,'id','tipo_habito_toxico_id');
    }
    public function preocupacional(){
        return $this->hasOne(Preocupacional::class,'id','preocupacional_id');
    }
}
