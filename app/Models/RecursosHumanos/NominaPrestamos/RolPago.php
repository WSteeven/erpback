<?php

namespace App\Models\RecursosHumanos\NominaPrestamos;

use App\Models\Empleado;
use Carbon\Carbon;
use Eloquent;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OwenIt\Auditing\Auditable as AuditableModel;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Models\Audit;

/**
 * App\Models\RecursosHumanos\NominaPrestamos\RolPago
 *
 * @property int $id
 * @property string $mes
 * @property int $empleado_id
 * @property string $salario
 * @property string $dias
 * @property string $sueldo
 * @property string $decimo_tercero
 * @property string $decimo_cuarto
 * @property string $fondos_reserva
 * @property string|null $bonificacion
 * @property string $total_ingreso
 * @property string $comisiones
 * @property string $iess
 * @property string $anticipo
 * @property string $prestamo_quirorafario
 * @property string $prestamo_hipotecario
 * @property string $extension_conyugal
 * @property string $prestamo_empresarial
 * @property string $supa
 * @property string|null $bono_recurente
 * @property string $total_egreso
 * @property string $total
 * @property string $estado
 * @property bool|null $medio_tiempo
 * @property int $rol_pago_id
 * @property string $rol_firmado
 * @property int|null $porcentaje_quincena
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property bool $es_vendedor_medio_tiempo
 * @property bool $sueldo_quincena_modificado
 * @property-read Collection<int, Audit> $audits
 * @property-read int|null $audits_count
 * @property-read Collection<int, EgresoRolPago> $egreso_rol_pago
 * @property-read int|null $egreso_rol_pago_count
 * @property-read Empleado|null $empleado_info
 * @property-read Collection<int, IngresoRolPago> $ingreso_rol_pago
 * @property-read int|null $ingreso_rol_pago_count
 * @property-read RolPagoMes|null $rolPagoMes
 * @method static Builder|RolPago acceptRequest(?array $request = null)
 * @method static Builder|RolPago filter(?array $request = null)
 * @method static Builder|RolPago ignoreRequest(?array $request = null)
 * @method static Builder|RolPago newModelQuery()
 * @method static Builder|RolPago newQuery()
 * @method static Builder|RolPago query()
 * @method static Builder|RolPago setBlackListDetection(?array $black_list_detections = null)
 * @method static Builder|RolPago setCustomDetection(?array $object_custom_detect = null)
 * @method static Builder|RolPago setLoadInjectedDetection($load_default_detection)
 * @method static Builder|RolPago whereAnticipo($value)
 * @method static Builder|RolPago whereBonificacion($value)
 * @method static Builder|RolPago whereBonoRecurente($value)
 * @method static Builder|RolPago whereComisiones($value)
 * @method static Builder|RolPago whereCreatedAt($value)
 * @method static Builder|RolPago whereDecimoCuarto($value)
 * @method static Builder|RolPago whereDecimoTercero($value)
 * @method static Builder|RolPago whereDias($value)
 * @method static Builder|RolPago whereEmpleadoId($value)
 * @method static Builder|RolPago whereEsVendedorMedioTiempo($value)
 * @method static Builder|RolPago whereEstado($value)
 * @method static Builder|RolPago whereExtensionConyugal($value)
 * @method static Builder|RolPago whereFondosReserva($value)
 * @method static Builder|RolPago whereId($value)
 * @method static Builder|RolPago whereIess($value)
 * @method static Builder|RolPago whereMedioTiempo($value)
 * @method static Builder|RolPago whereMes($value)
 * @method static Builder|RolPago wherePorcentajeQuincena($value)
 * @method static Builder|RolPago wherePrestamoEmpresarial($value)
 * @method static Builder|RolPago wherePrestamoHipotecario($value)
 * @method static Builder|RolPago wherePrestamoQuirorafario($value)
 * @method static Builder|RolPago whereRolFirmado($value)
 * @method static Builder|RolPago whereRolPagoId($value)
 * @method static Builder|RolPago whereSalario($value)
 * @method static Builder|RolPago whereSueldo($value)
 * @method static Builder|RolPago whereSueldoQuincenaModificado($value)
 * @method static Builder|RolPago whereSupa($value)
 * @method static Builder|RolPago whereTotal($value)
 * @method static Builder|RolPago whereTotalEgreso($value)
 * @method static Builder|RolPago whereTotalIngreso($value)
 * @method static Builder|RolPago whereUpdatedAt($value)
 * @mixin Eloquent
 */
class RolPago extends Model implements Auditable
{
    use HasFactory;
    use AuditableModel;
    use Filterable;

    protected $table = 'rol_pago';
    const CREADO = 'CREADO';
    const EJECUTANDO = 'EJECUTANDO';
    const CANCELADO = 'CANCELADO';
    const REALIZADO = 'REALIZADO';
    const FINALIZADO = 'FINALIZADO';
    protected $fillable = [
        'empleado_id',
        'mes',
        'dias',
        'salario',
        'sueldo',
        'supa',
        'anticipo',
        'bonificacion',
        'bono_recurente',
        'decimo_tercero',
        'decimo_cuarto',
        'prestamo_quirorafario',
        'prestamo_hipotecario',
        'prestamo_empresarial',
        'fondos_reserva',
        'total_ingreso',
        'iess',
        'total_egreso',
        'total',
        'estado',
        'rol_pago_id',
        'rol_firmado',
        'medio_tiempo',
        'es_vendedor_medio_tiempo',
        'fondos_reserva',
        'porcentaje_quincena',
        'sueldo_quincena_modificado',
    ];
    private static array $whiteListFilter = [
        'id',
        'mes',
        'empleado',
        'bonificacion',
        'bono_recurente',
        'dias',
        'sueldo',
        'salario',
        'total_ingreso',
        'total_egreso',
        'total',
        'estado',
        'rol_pago_id',
        'rol_firmado',
        'fondos_reserva',
        'medio_tiempo',
        'es_vendedor_medio_tiempo',
        'porcentaje_quincena',
        'sueldo_quincena_modificado',
    ];
    protected $casts = [
        'medio_tiempo' => 'boolean',
        'es_vendedor_medio_tiempo' => 'boolean',
        'sueldo_quincena_modificado' => 'boolean',
    ];

    /**
     * @return array
     */
    public static function getDatosBancariosDefault(): array
    {
        $row['tipo_pago'] = 'PA';
        $row['numero_cuenta_empresa'] = '02653010903';
        $row['moneda'] = 'USD';
        $row['forma_pago'] = 'CTA';
        $row['codigo_banco'] = '0036';
        $row['tipo_cuenta'] = 'AHO';
        $row['tipo_documento_empleado'] = 'C';
        return $row;
    }

    public function empleado_info()
    {
        return $this->belongsTo(Empleado::class, 'empleado_id', 'id')->with('cargo', 'user');
    }

    public function egreso_rol_pago()
    {
        return $this->hasMany(EgresoRolPago::class, 'id_rol_pago', 'id')->with('descuento', 'empleado');
    }

    public function ingreso_rol_pago()
    {
        return $this->hasMany(IngresoRolPago::class, 'id_rol_pago', 'id')->with('concepto_ingreso_info');
    }

    // Relacion uno a muchos (inversa)
    public function rolPagoMes()
    {
        return $this->hasOne(RolPagoMes::class, 'id', 'rol_pago_id');
    }

    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

    /**
     * La función "empaquetarListado" toma una matriz de objetos "roles de pagos" y devuelve una matriz ordenada
     * de datos formateados.
     *
     *
     * @return array serie de resultados.
     */
    public static function empaquetarListado(Collection|RolPago $rol_pagos)
    {
        // Aseguramos que lo recibido siempre sea una coleccion a pesar de recibirse un solo registro.
        $rol_pagos = $rol_pagos instanceof Collection ? $rol_pagos : collect([$rol_pagos]);

        //Función para formatear números
        $formatNumber = fn($value) => number_format((float)$value, 2, '.', '');

        // Mapear la coleccion a un array con los datos formateados
        $results = $rol_pagos->map(function ($rol_pago, $index) use ($formatNumber) {
            return [
                'item' => $index + 1,
                'id' => $rol_pago->id,
                'empleado_info' => $rol_pago->empleado_info->apellidos . ' ' . $rol_pago->empleado_info->nombres,
                'cedula' => $rol_pago->empleado_info->identificacion,
                'salario' => $rol_pago->empleado_info->salario,
                'mes' => ucfirst(Carbon::createFromFormat('m-Y', $rol_pago->mes)->locale('es')->translatedFormat('F \d\e Y')),
                'identificacion_empleado' => $rol_pago->empleado_info->identificacion,
                'cargo' => $rol_pago->empleado_info->cargo != null ? $rol_pago->empleado_info->cargo->nombre : '',
                'departamento' => $rol_pago->empleado_info->departamento != null ? $rol_pago->empleado_info->departamento->nombre : '',
                'ciudad' => $rol_pago->empleado_info->canton != null ? $rol_pago->empleado_info->canton->canton : '',
                'dias_laborados' => (int)$rol_pago->dias,
                'sueldo' => $formatNumber($rol_pago->sueldo),
                'decimo_tercero' => $formatNumber($rol_pago->decimo_tercero),
                'decimo_cuarto' => $formatNumber($rol_pago->decimo_cuarto),
                'fondos_reserva' => $formatNumber($rol_pago->fondos_reserva),
                'bonificacion' => $formatNumber($rol_pago->bonificacion),
                'bono_recurente' => $formatNumber($rol_pago->bono_recurente),
                'iess' => $formatNumber($rol_pago->iess),
                'anticipo' => $formatNumber($rol_pago->anticipo),
                'prestamo_quirorafario' => $formatNumber($rol_pago->prestamo_quirorafario),
                'prestamo_hipotecario' => $formatNumber($rol_pago->prestamo_hipotecario),
                'extension_conyugal' => $formatNumber($rol_pago->extension_conyugal),
                'prestamo_empresarial' => $formatNumber($rol_pago->prestamo_empresarial),
                'total_ingreso' => $formatNumber($rol_pago->total_ingreso),
                'total_egreso' => $formatNumber($rol_pago->total_egreso),
                'total' => $formatNumber($rol_pago->total),
                'supa' => $formatNumber($rol_pago->supa),
                'ingresos' => $rol_pago->ingreso_rol_pago,
                'egresos' => $rol_pago->egreso_rol_pago,
                'egresos_cantidad_columna' => count($rol_pago->egreso_rol_pago),
                'ingresos_cantidad_columna' => count($rol_pago->ingreso_rol_pago),
                'rol_firmado' => $rol_pago->rol_firmado ? json_decode($rol_pago->rol_firmado)->ruta : null,
            ];
        })->toArray();

        usort($results, __CLASS__ . "::ordenar_por_nombres_apellidos");

        return array_values($results); //Reindexar el array
    }
    /*public static function empaquetarListado($rol_pagos)
    {
        $results = [];
        $id = 0;
        $row = [];

        foreach ($rol_pagos as $rol_pago) {
            $row['item'] = $id + 1;
            $row['id'] =  $rol_pago->id;
            $row['empleado_info'] =  $rol_pago->empleado_info->apellidos . ' ' . $rol_pago->empleado_info->nombres;
            $row['cedula'] =  $rol_pago->empleado_info->identificacion;
            $row['salario'] =  $rol_pago->empleado_info->salario;
            $row['mes'] =  ucfirst(Carbon::createFromFormat('m-Y', $rol_pago->mes)->locale('es')->translatedFormat('F \d\e Y'));
            $row['identificacion_empleado'] =  $rol_pago->empleado_info->identificacion;
            $row['cargo'] = $rol_pago->empleado_info->cargo != null ? $rol_pago->empleado_info->cargo->nombre : '';
            $row['departamento'] = $rol_pago->empleado_info->departamento != null ? $rol_pago->empleado_info->departamento->nombre : '';
            $row['ciudad'] = $rol_pago->empleado_info->canton != null ? $rol_pago->empleado_info->canton->canton : '';
            $row['dias_laborados'] = intval($rol_pago->dias);
            $row['sueldo'] =  str_replace(",", "", number_format($rol_pago->sueldo, 2));
            $row['decimo_tercero'] = str_replace(",", "", number_format($rol_pago->decimo_tercero, 2));
            $row['decimo_cuarto'] = str_replace(",", "", number_format($rol_pago->decimo_cuarto, 2));
            $row['fondos_reserva'] = str_replace(",", "", number_format($rol_pago->fondos_reserva, 2));
            $row['bonificacion'] = str_replace(",", "", number_format($rol_pago->bonificacion, 2));
            $row['bono_recurente'] = str_replace(",", "", number_format($rol_pago->bono_recurente, 2));
            $row['iess'] = str_replace(",", "", number_format($rol_pago->iess, 2));
            $row['anticipo'] = str_replace(",", "", number_format($rol_pago->anticipo, 2));
            $row['prestamo_quirorafario'] = str_replace(",", "", number_format($rol_pago->prestamo_quirorafario, 2));
            $row['prestamo_hipotecario'] = str_replace(",", "", number_format($rol_pago->prestamo_hipotecario, 2));
            $row['extension_conyugal'] = str_replace(",", "", number_format($rol_pago->extension_conyugal, 2));
            $row['prestamo_empresarial'] = str_replace(",", "", number_format($rol_pago->prestamo_empresarial, 2));
            $row['total_ingreso'] = str_replace(",", "", number_format($rol_pago->total_ingreso, 2));
            $row['total_egreso'] = str_replace(",", "", number_format($rol_pago->total_egreso, 2));
            $row['total'] = str_replace(",", "", number_format($rol_pago->total, 2));
            $row['supa'] = str_replace(",", "", number_format($rol_pago->supa, 2));
            $row['ingresos'] = $rol_pago->ingreso_rol_pago;
            $row['egresos'] = $rol_pago->egreso_rol_pago;
            $row['egresos_cantidad_columna'] = count($rol_pago->egreso_rol_pago);
            $row['ingresos_cantidad_columna'] = count($rol_pago->ingreso_rol_pago);
            $row['rol_firmado'] = $rol_pago->rol_firmado ? json_decode($rol_pago->rol_firmado)->ruta : null;
            $results[$id] = $row;
            $id++;
        }
        usort($results, __CLASS__ . "::ordenar_por_nombres_apellidos");

        return $results;
    }*/

    /**
     * La función "empaquetarCash" toma un conjunto de "rol_pagos" y devuelve un conjunto ordenado de
     * datos relacionados con pagos en efectivo.
     *
     *
     * @return array serie de resultados.
     */
    public static function empaquetarCash($rol_pagos)
    {
        $results = [];
        $id = 0;

        foreach ($rol_pagos as $rol_pago) {
            $cuenta_bancarea_num = intval($rol_pago->empleado_info->num_cuenta_bancaria);
            if ($cuenta_bancarea_num > 0) {
                $referencia = $rol_pago->rolPagoMes->es_quincena ? 'PAGO ROL PRIMERA QUINCENA MES ' : 'PAGO ROL FIN DE MES ';
                $row = self::getDatosBancariosDefault();
                $row['item'] = $id + 1;
                $row['empleado_info'] = $rol_pago->empleado_info->apellidos . ' ' . $rol_pago->empleado_info->nombres;
                $row['departamento'] = $rol_pago->empleado_info->departamento->nombre;
                $row['numero_cuenta_bancareo'] = $rol_pago->empleado_info->num_cuenta_bancaria;
                $row['email'] = $rol_pago->empleado_info->user->email;
                $row['referencia'] = strtoupper($referencia . ucfirst(Carbon::createFromFormat('m-Y', $rol_pago->mes)->locale('es')->translatedFormat('F')));
                $row['identificacion'] = $rol_pago->empleado_info->identificacion;
                $row['total'] = str_replace(".", "", number_format($rol_pago->total, 2, ',', '.'));
                $results[$id] = $row;

                $id++;
            }
        }
        usort($results, __CLASS__ . "::ordenar_por_nombres_apellidos");

        return $results;
    }

    /**
     * La función "ordenar_por_nombres_apellidos" ordena una matriz de empleados según sus nombres y
     * apellidos.
     *
     *
     * @return int resultado de la comparación entre los nombres concatenados de los dos empleados.
     */
    private static function ordenar_por_nombres_apellidos($a, $b)
    {
        $nameA = $a['empleado_info'] . ' ' . $a['empleado_info'];
        $nameB = $b['empleado_info'] . ' ' . $b['empleado_info'];
        return strcmp($nameA, $nameB);
    }
}
