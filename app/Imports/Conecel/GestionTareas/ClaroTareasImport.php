<?php

namespace App\Imports\Conecel\GestionTareas;

use App\Models\Conecel\GestionTareas\Tarea;
use App\Models\Conecel\GestionTareas\TipoActividad;
use App\Models\Grupo;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Str;

class ClaroTareasImport implements ToModel, WithHeadingRow
{
    private string $nombreArchivo;
    private ?Grupo $grupo;
    private array $ordenesProcesadas = []; // Para evitar duplicados dentro del mismo archivo

    public function __construct($nombreArchivo, $grupoId = null)
    {
        $this->nombreArchivo = Str::beforeLast($nombreArchivo, '.');
        $this->grupo = $grupoId !== 0 ? Grupo::find($grupoId) : null;
    }

    /**
     * @param array $row
     *
     * @return Model|null
     */
    public function model(array $row)
    {
        // Se debe quitar las tildes para evitar problemas de comparación
        $tipoActividad = strtoupper(Str::ascii($row['tipo_de_actividad'] ?? ''));

        // Ignorar Almuerzo y Bodega
        if (in_array($tipoActividad, ['ALMUERZO', 'BODEGA INICIO DIA', 'BODEGA FIN DIA'])) {
            return null;
        }

        $ordenTrabajo = trim($row['orden_de_trabajo'] ?? '');
        if (empty($ordenTrabajo)) return null;

        // ⚡ Evitar duplicados dentro del mismo archivo
        if (in_array($ordenTrabajo, $this->ordenesProcesadas)) {
            return null;
        }

        // Guardar el número para evitar duplicados posteriores
        $this->ordenesProcesadas[] = $ordenTrabajo;

        // Datos para crear o actualizar
        $data = [
            'fecha' => Carbon::createFromFormat('d/m/y', $row['fecha'])->format('Y-m-d'),
            'registrador_id' => auth()->user()->empleado->id,
            'tipo_actividad_id' => TipoActividad::where('nombre', $tipoActividad)->first()->id,
            'grupo_id' => $this->grupo?->id ?? null,
            'asignada' => !is_null($this->grupo),
            'estado_tarea' => $row['estado_de_actividad'],
            'nombre_cliente' => $row['nombre'],
            'direccion' => $row['direccion'],
            'latitud' => $row['coordenada_y'] ?? null,
            'longitud' => $row['coordenada_x'] ?? null,
            'nombre_archivo' => $this->nombreArchivo,
        ];

        return Tarea::updateOrCreate(['orden_trabajo' => $ordenTrabajo], $data);
    }

}
