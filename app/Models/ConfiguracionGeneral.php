<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;

/**
 * App\Models\ConfiguracionGeneral
 *
 * @method static first()
 * @property int $id
 * @property string $nombre_empresa
 * @property string $logo_claro
 * @property string $logo_oscuro
 * @property string $logo_marca_agua
 * @property string $ruc
 * @property string|null $ciiu
 * @property string|null $representante
 * @property string|null $razon_social
 * @property string|null $nombre_comercial
 * @property string|null $direccion_principal
 * @property string|null $telefono
 * @property string $moneda
 * @property string $iva
 * @property string|null $tipo_contribuyente
 * @property string|null $celular1
 * @property string|null $celular2
 * @property string|null $correo_principal
 * @property string|null $correo_secundario
 * @property string|null $sitio_web
 * @property string|null $direccion_secundaria1
 * @property string|null $direccion_secundaria2
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \OwenIt\Auditing\Models\Audit> $audits
 * @property-read int|null $audits_count
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral query()
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereCelular1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereCelular2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereCiiu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereCorreoPrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereCorreoSecundario($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereDireccionPrincipal($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereDireccionSecundaria1($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereDireccionSecundaria2($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereLogoClaro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereLogoMarcaAgua($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereLogoOscuro($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereMoneda($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereNombreComercial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereNombreEmpresa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereRazonSocial($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereRepresentante($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereRuc($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereSitioWeb($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereTelefono($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereTipoContribuyente($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|ConfiguracionGeneral first($value)
 * @mixin \Eloquent
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
        'iva',
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
