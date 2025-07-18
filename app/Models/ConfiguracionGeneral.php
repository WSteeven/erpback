<?php

namespace App\Models;

use Eloquent;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use App\Traits\UppercaseValuesTrait;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\ConfiguracionGeneral
 *
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
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @method static Builder|ConfiguracionGeneral newModelQuery()
 * @method static Builder|ConfiguracionGeneral newQuery()
 * @method static Builder|ConfiguracionGeneral query()
 * @method static Builder|ConfiguracionGeneral whereCelular1($value)
 * @method static Builder|ConfiguracionGeneral whereCelular2($value)
 * @method static Builder|ConfiguracionGeneral whereCiiu($value)
 * @method static Builder|ConfiguracionGeneral whereCorreoPrincipal($value)
 * @method static Builder|ConfiguracionGeneral whereCorreoSecundario($value)
 * @method static Builder|ConfiguracionGeneral whereCreatedAt($value)
 * @method static Builder|ConfiguracionGeneral whereDireccionPrincipal($value)
 * @method static Builder|ConfiguracionGeneral whereDireccionSecundaria1($value)
 * @method static Builder|ConfiguracionGeneral whereDireccionSecundaria2($value)
 * @method static Builder|ConfiguracionGeneral whereId($value)
 * @method static Builder|ConfiguracionGeneral whereLogoClaro($value)
 * @method static Builder|ConfiguracionGeneral whereLogoMarcaAgua($value)
 * @method static Builder|ConfiguracionGeneral whereLogoOscuro($value)
 * @method static Builder|ConfiguracionGeneral whereMoneda($value)
 * @method static Builder|ConfiguracionGeneral whereNombreComercial($value)
 * @method static Builder|ConfiguracionGeneral whereNombreEmpresa($value)
 * @method static Builder|ConfiguracionGeneral whereRazonSocial($value)
 * @method static Builder|ConfiguracionGeneral whereRepresentante($value)
 * @method static Builder|ConfiguracionGeneral whereRuc($value)
 * @method static Builder|ConfiguracionGeneral whereSitioWeb($value)
 * @method static Builder|ConfiguracionGeneral whereTelefono($value)
 * @method static Builder|ConfiguracionGeneral whereTipoContribuyente($value)
 * @method static Builder|ConfiguracionGeneral whereUpdatedAt($value)
 * @method static Builder|ConfiguracionGeneral first()
 * @mixin Eloquent
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
        'sitio_web_erp',
        'link_app_movil',
        'direccion_secundaria1',
        'direccion_secundaria2',
        'nombre_empresa',
    ];
}
