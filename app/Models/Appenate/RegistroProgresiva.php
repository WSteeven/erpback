<?php

namespace App\Models\Appenate;

use App\Traits\UppercaseValuesTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;

class RegistroProgresiva extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use UppercaseValuesTrait;

    protected $table = 'appenate_registros_progresivas';
    protected $fillable = [
        'progresiva_id',
        'num_elemento',
        'propietario',
        'elemento',
        'tipo_poste',
        'material_poste',
        'ubicacion_gps',
        'foto',
        'observaciones',
        'tiene_control_cambio',
        'observacion_cambio',
        'foto_cambio',
        'hora_cambio',
    ];

    protected $casts = [
        'tiene_control_cambio' => 'boolean',
    ];

    public function progresiva()
    {
        return $this->belongsTo(Progresiva::class);
    }

    public function materiales()
    {
        return $this->hasMany(MaterialUtilizadoProgresiva::class, 'registro_id');
    }
}
