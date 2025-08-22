<?php

namespace App\Models;

use App\Models\ComprasProveedores\ContactoProveedor;
use App\Models\ComprasProveedores\DatoBancarioProveedor;
use App\Models\ComprasProveedores\LogisticaProveedor;
use App\Traits\UppercaseValuesTrait;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\Empresa
 *
 * @property int $id
 * @property string $identificacion
 * @property string|null $tipo_contribuyente
 * @property string $razon_social
 * @property string|null $nombre_comercial
 * @property string|null $sitio_web
 * @property string|null $correo
 * @property int|null $canton_id
 * @property string|null $direccion
 * @property string|null $antiguedad_proveedor
 * @property string|null $identificacion_representante
 * @property string|null $representante_legal
 * @property bool $agente_retencion
 * @property bool $lleva_contabilidad
 * @property bool $contribuyente_especial
 * @property string|null $actividad_economica
 * @property string|null $regimen_tributario
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property bool $es_proveedor
 * @property bool $es_cliente
 * @property-read Collection<int, Archivo> $archivos
 * @property-read int|null $archivos_count
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Canton|null $canton
 * @property-read Cliente|null $cliente
 * @property-read Collection<int, ContactoProveedor> $contactos
 * @property-read int|null $contactos_count
 * @property-read Collection<int, DatoBancarioProveedor> $datos_bancarios
 * @property-read int|null $datos_bancarios_count
 * @property-read LogisticaProveedor|null $logistica
 * @property-read Collection<int, Proveedor> $proveedores
 * @property-read int|null $proveedores_count
 * @method static Builder|Empresa acceptRequest(?array $request = null)
 * @method static Builder|Empresa filter(?array $request = null)
 * @method static Builder|Empresa ignoreRequest(?array $request = null)
 * @method static Builder|Empresa newModelQuery()
 * @method static Builder|Empresa newQuery()
 * @method static Builder|Empresa query()
 * @method static Builder|Empresa setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|Empresa setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|Empresa setLoadInjectedDetection($load_default_detection)
 * @method static Builder|Empresa whereActividadEconomica($value)
 * @method static Builder|Empresa whereAgenteRetencion($value)
 * @method static Builder|Empresa whereAntiguedadProveedor($value)
 * @method static Builder|Empresa whereCantonId($value)
 * @method static Builder|Empresa whereContribuyenteEspecial($value)
 * @method static Builder|Empresa whereCorreo($value)
 * @method static Builder|Empresa whereCreatedAt($value)
 * @method static Builder|Empresa whereDireccion($value)
 * @method static Builder|Empresa whereEsCliente($value)
 * @method static Builder|Empresa whereEsProveedor($value)
 * @method static Builder|Empresa whereId($value)
 * @method static Builder|Empresa whereIdentificacion($value)
 * @method static Builder|Empresa whereIdentificacionRepresentante($value)
 * @method static Builder|Empresa whereLlevaContabilidad($value)
 * @method static Builder|Empresa whereNombreComercial($value)
 * @method static Builder|Empresa whereRazonSocial($value)
 * @method static Builder|Empresa whereRegimenTributario($value)
 * @method static Builder|Empresa whereRepresentanteLegal($value)
 * @method static Builder|Empresa whereSitioWeb($value)
 * @method static Builder|Empresa whereTipoContribuyente($value)
 * @method static Builder|Empresa whereUpdatedAt($value)
 * @mixin Eloquent
 */
class Empresa extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = 'empresas';
    protected $fillable = [
        'identificacion',
        'tipo_contribuyente',
        'razon_social',
        'nombre_comercial',
        // 'celular',
        // 'telefono',
        'correo',
        'canton_id',
        // 'ciudad',
        'direccion',
        'agente_retencion',
        'regimen_tributario',
        'sitio_web',
        'lleva_contabilidad',
        'contribuyente_especial',
        'actividad_economica',
        'representante_legal',
        'identificacion_representante',
        'antiguedad_proveedor',
        'es_cliente',
        'es_proveedor',
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'agente_retencion'=>'boolean',
        'lleva_contabilidad'=>'boolean',
        'contribuyente_especial'=>'boolean',
        'es_cliente'=>'boolean',
        'es_proveedor'=>'boolean',
    ];

    private static array $whiteListFilter = ['*'];

    //Tipo de contribuyente
    const NATURAL = 'PERSONA NATURAL'; //persona natural
    const SOCIEDAD = 'SOCIEDAD'; //sociedad privada
    const PUBLICA = 'PUBLICA'; //sociedad publica

    //regimen tributario
    const RIMPE_EMPRENDEDOR = 'RIMPE EMPRENDEDOR';
    const RIMPE_NEGOCIOS_POPULARES = 'RIMPE NEGOCIOS POPULARES';
    const GENERAL = 'GENERAL';
    /**
     * Relacion uno a uno
     */
    public function cliente()
    {
        return $this->hasOne(Cliente::class);
    }

    public function proveedores()
    {
        return $this->hasMany(Proveedor::class);
    }
    public function contactos()
    {
        return $this->hasMany(ContactoProveedor::class);
    }
    public function datos_bancarios()
    {
        return $this->hasMany(DatoBancarioProveedor::class);
    }
    public function logistica()
    {
        return $this->hasOne(LogisticaProveedor::class);
    }

    /**
     * Relación uno a muchos(inversa).
     */
    public function canton()
    {
        return $this->belongsTo(Canton::class);
    }

     /**
     * Relacion polimorfica con Archivos uno a muchos.
     *
     */
    public function archivos(){
        return $this->morphMany(Archivo::class, 'archivable');
    }
}
