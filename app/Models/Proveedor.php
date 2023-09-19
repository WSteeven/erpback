<?php

namespace App\Models;

use App\Models\ComprasProveedores\ContactoProveedor;
use App\Models\ComprasProveedores\OfertaProveedor;
use App\Traits\UppercaseValuesTrait;
use eloquentFilter\QueryFilter\ModelFilters\Filterable;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use OwenIt\Auditing\Contracts\Auditable;
use OwenIt\Auditing\Auditable as AuditableModel;

class Proveedor extends Model implements Auditable
{
    use HasFactory, UppercaseValuesTrait;
    use AuditableModel;
    use Filterable;

    protected $table = "proveedores";
    protected $fillable = [
        "empresa_id",
        "estado",
        "sucursal",
        "parroquia_id",
        "direccion",
        "celular",
        "telefono",
        "calificacion",
        "estado_calificado",
        "forma_pago",
        "referencia",
        "plazo_credito",
        "anticipos",
    ];
    protected $casts = [
        'created_at' => 'datetime:Y-m-d h:i:s a',
        'updated_at' => 'datetime:Y-m-d h:i:s a',
        'estado' => 'boolean',
    ];

    const CALIFICADO = 'CALIFICADO'; // cuando está calificado por todos los departamentos
    const PARCIAL = 'PARCIAL';  // cuando al menos un departamento ha calificado el proveedor
    const SIN_CALIFICAR = 'SIN CALIFICAR';  // cuando aún no califica ningun departamento
    const SIN_CONFIGURAR = 'SIN CONFIGURAR'; //cuando no se ha enlazado departamentos calificadores


    private static $whiteListFilter = [
        'empresa.razon_social',
        'empresa.nombre_comercial',
        '*',
    ];

    /**
     * ______________________________________________________________________________________
     * RELACIONES CON OTRAS TABLAS
     * ______________________________________________________________________________________
     */
    public function empresa()
    {
        return $this->belongsTo(Empresa::class);
    }

    // public function canton(){
    //     return $this->belongsTo(Canton::class)
    // }
    public function parroquia()
    {
        return $this->belongsTo(Parroquia::class);
    }

    public function contactos()
    {
        return $this->hasMany(ContactoProveedor::class);
    }
    public function servicios_ofertados()
    {
        return $this->belongsToMany(OfertaProveedor::class, 'detalle_oferta_proveedor', 'proveedor_id', 'oferta_id')
            ->withTimestamps();
    }
    public function categorias_ofertadas()
    {
        return $this->belongsToMany(Categoria::class, 'detalle_categoria_proveedor', 'proveedor_id', 'categoria_id')
            ->withTimestamps();
    }
    public function departamentos_califican()
    {
        return $this->belongsToMany(Departamento::class, 'detalle_departamento_proveedor', 'proveedor_id', 'departamento_id')
            ->withPivot(['calificacion', 'fecha_calificacion'])
            ->withTimestamps();
    }

    /**
     * Relacion polimorfica a una notificacion.
     * Un proveedor puede tener una o varias notificaciones.
     */
    public function notificaciones()
    {
        return $this->morphMany(Notificacion::class, 'notificable');
    }

    /**
     * Relación para obtener la ultima notificacion de un modelo dado.
     */
    public function latestNotificacion()
    {
        return $this->morphOne(Notificacion::class, 'notificable')->latestOfMany();
    }


    /**
     * ______________________________________________________________________________________
     * FUNCIONES
     * ______________________________________________________________________________________
     */

    public static function guardarCalificacion($proveedor_id)
    {
        $proveedor = Proveedor::find($proveedor_id);
        if ($proveedor->departamentos_califican->count() == 2) {
            $calificaciones = [];
            foreach ($proveedor->departamentos_califican as $index => $departamento) {
                if ($departamento->pivot->calificacion != null) {
                    $row['departamento_id'] = $departamento->id;
                    $row['calificacion'] = $departamento->pivot->calificacion;
                    $calificaciones[$index] = $row;
                }
            }
            $suma = self::calcularPesos($calificaciones);
            if (count($calificaciones) == $proveedor->departamentos_califican->count()) {
                $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::CALIFICADO]);
            } elseif (empty($calificaciones)) {
                $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::SIN_CALIFICAR]);
            } else {
                $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::PARCIAL]);
            }
        }
        if ($proveedor->departamentos_califican->count() == 3) {
            $calificaciones = [];
            foreach ($proveedor->departamentos_califican as $index => $departamento) {
                if ($departamento->pivot->calificacion != null) {
                    $row['departamento_id'] = $departamento->id;
                    $row['calificacion'] = $departamento->pivot->calificacion;
                    $calificaciones[$index] = $row;
                }
            }
            $suma = self::calcularPesos($calificaciones);
            if (count($calificaciones) == $proveedor->departamentos_califican->count()) {
                $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::CALIFICADO]);
            } elseif (empty($calificaciones)) $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::SIN_CALIFICAR]);
            else $proveedor->update(['calificacion' => $suma, 'estado_calificado' => Proveedor::PARCIAL]);
        }
        $proveedor->refresh();
    }

    public static function obtenerCalificacion($proveedor_id)
    {
        $proveedor = Proveedor::find($proveedor_id);
        if ($proveedor->departamentos_califican->count() == 2) {
            $calificaciones = [];
            foreach ($proveedor->departamentos_califican as $index => $departamento) {
                if ($departamento->pivot->calificacion != null) {
                    $row['departamento_id'] = $departamento->id;
                    $row['calificacion'] = $departamento->pivot->calificacion;
                    $calificaciones[$index] = $row;
                }
            }
            $suma = self::calcularPesos($calificaciones);
            if (count($calificaciones) == $proveedor->departamentos_califican->count()) {
                return [$suma, 'CALIFICADO'];
            } elseif (empty($calificaciones)) return [$suma, 'SIN CALIFICAR'];
            else return [$suma, 'PARCIAL'];
            Log::channel('testing')->info('Log', ['Calificaciones', $calificaciones, 'Suma de notas: ', $suma]);
        }
        if ($proveedor->departamentos_califican->count() == 3) {
            // Log::channel('testing')->info('Log', ['Proveedor tiene 3 departamentos']);
            $calificaciones = [];
            foreach ($proveedor->departamentos_califican as $index => $departamento) {
                if ($departamento->pivot->calificacion != null) {
                    $row['departamento_id'] = $departamento->id;
                    $row['calificacion'] = $departamento->pivot->calificacion;
                    $calificaciones[$index] = $row;
                }
            }
            $suma = self::calcularPesos($calificaciones);
            if (count($calificaciones) == $proveedor->departamentos_califican->count()) {
                return [$suma, 'CALIFICADO'];
            } elseif (empty($calificaciones)) return [$suma, 'SIN CALIFICAR'];
            else return [$suma, 'PARCIAL'];
        }
        // Log::channel('testing')->info('Log', ['Proveedor tiene ' . $proveedor->departamentos_califican->count() . ' departamentos']);
        return [0, 'SIN CONFIGURAR'];
    }

    /**
     * La función "calcularPesos" calcula los pesos en base al número de departamentos y sus
     * respectivas valoraciones.
     * Si son 3 departamentos la distribucion de pesos es la siguiente:
     *     Area especializada 1 = 35 %
     *     Area especializada 2 = 35 %
     *     Area financiera(compras)     = 30 %
     * Si son 2 departamentos la distribucion de pesos es la siguiente:
     *     Area especializada  = 60 %
     *     Area financiera(compras)     = 40 %
     *
     * @param data El parámetro `$data` es una matriz que contiene información sobre los departamentos
     * y sus respectivas calificaciones. Cada elemento de la matriz representa un departamento y tiene
     * la siguiente estructura: [departamento_id, calificacion]
     *
     * @return la suma calculada de pesos basada en los datos dados.
     */
    private static function calcularPesos($data)
    {
        $user_compras = User::with('empleado')->whereHas("roles", function ($q) {
            $q->where("name", User::ROL_COMPRAS);
        })->first();
        Log::channel('testing')->info('Log', ['Conteo de Calificaciones', count($data), ' departamento de compras: ', $user_compras->empleado->departamento_id]);
        $suma = 0;
        switch (count($data)) {
            case 0:
                return 0;
                break;
            case 1:
                foreach ($data as $d)
                    return $d['calificacion'];
                break;
            case 2:
                foreach ($data as $d) {
                    if ($d['departamento_id'] === $user_compras->empleado->departamento_id) $suma += ($d['calificacion'] * .4);
                    else $suma += ($d['calificacion'] * .6);
                }
                return $suma;
                break;
            case 3:
                foreach ($data as $d) {
                    if ($d['departamento_id'] === $user_compras->empleado->departamento_id) $suma += ($d['calificacion'] * .3);
                    else $suma += ($d['calificacion'] * .35);
                }
                return $suma;
                break;
            default:
                Log::channel('testing')->info('Log', ['Conteo de Calificaciones en metodo calcularPeso', count($data), ' departamento de compras: ', $user_compras->empleado->departamento_id]);
                throw new Exception('No se puede hacer calculo para más de 3 departamentos', 500);
                break;
        }
    }
}
