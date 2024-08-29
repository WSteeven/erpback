<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;

/**
 * @method static first()
 */
class ConfiguracionGeneral extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait, AuditableModel;


    protected $table = 'configuraciones_generales';
    protected $fillable = [
        'logo_claro',
        'logo_oscuro',
        'logo_marca_agua',
        'ruc',
        'ciiu',
        'representante',
        'razon_social',
        'nombre_comercial',
        'direccion_principal',
        'telefono',
        'moneda',
        'tipo_contribuyente',
        'celular1',
        'celular2',
        'correo_principal',
        'correo_secundario',
        'sitio_web',
        'direccion_secundaria1',
        'direccion_secundaria2',
        'nombre_empresa',
    ];
}
