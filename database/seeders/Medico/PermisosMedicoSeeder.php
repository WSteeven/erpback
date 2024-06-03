<?php

namespace Database\Seeders\Medico;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermisosMedicoSeeder extends Seeder
{
    const CREAR = 'puede.crear';
    const ACCEDER = 'puede.acceder'; // Formulario
    const VER = 'puede.ver'; // Consultar index y show
    const EDITAR = 'puede.editar';
    const RECHAZAR = 'puede.rechazar';
    const AUTORIZAR = 'puede.autorizar';
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /***************
         Modulo médico
         ***************/
        // php artisan db:seed --class=PermisosMedicoSeeder

        $medico = Role::firstOrCreate(['name' => User::ROL_MEDICO]);
        $empleado = Role::firstOrCreate(['name' => User::ROL_EMPLEADO]);
        $compras = Role::firstOrCreate(['name' => User::ROL_COMPRAS]);

        // Modulo medico
        Permission::firstOrCreate(['name' => self::VER . '.modulo_medico'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.modulo_medico'])->syncRoles([$empleado]);

        // Tipos de examenes
        Permission::firstOrCreate(['name' => self::VER . '.tipos_examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.tipos_examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.tipos_examenes'])->syncRoles([$empleado]);

        // Examenes
        Permission::firstOrCreate(['name' => self::VER . '.examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.examenes'])->syncRoles([$empleado]);

        // Categorias de examenes
        Permission::firstOrCreate(['name' => self::VER . '.categorias_examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.categorias_examenes'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.categorias_examenes'])->syncRoles([$empleado]);

        // Tipos de vacunas
        Permission::firstOrCreate(['name' => self::VER . '.tipos_vacunas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.tipos_vacunas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.tipos_vacunas'])->syncRoles([$empleado]);

        // Esquemas de vacunas
        Permission::firstOrCreate(['name' => self::VER . '.esquemas_vacunas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.esquemas_vacunas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.esquemas_vacunas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . '.esquemas_vacunas'])->syncRoles([$empleado]);

        // Gestionar pacientes
        Permission::firstOrCreate(['name' => self::VER . '.gestionar_pacientes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.gestionar_pacientes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.gestionar_pacientes'])->syncRoles([$medico]);

        // tipos_cuestionarios
        Permission::firstOrCreate(['name' => self::VER . '.tipos_cuestionarios'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.tipos_cuestionarios'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . '.tipos_cuestionarios'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.tipos_cuestionarios'])->syncRoles([$medico]);

        // Cuestionarios
        Permission::firstOrCreate(['name' => self::VER . '.cuestionarios'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.cuestionarios'])->syncRoles([$empleado]);

        // Reporte de cuestionarios
        Permission::firstOrCreate(['name' => self::VER . '.reportes_cuestionarios'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.reportes_cuestionarios'])->syncRoles([$medico]);

        // Cuestionarios psicosocial
        Permission::firstOrCreate(['name' => self::VER . '.cuestionarios_psicosocial'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.cuestionarios_psicosocial'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . '.cuestionarios_psicosocial'])->syncRoles([$empleado]);

        // Cuestionario diagnostico consumo drogas
        Permission::firstOrCreate(['name' => self::VER . '.cuestionario_diagnostico_consumo_drogas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.cuestionario_diagnostico_consumo_drogas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . '.cuestionario_diagnostico_consumo_drogas'])->syncRoles([$empleado]);

        // respuestas_cuestionarios_empleados
        Permission::firstOrCreate(['name' => self::VER . '.respuestas_cuestionarios_empleados'])->syncRoles([$empleado]);
        // Permission::firstOrCreate(['name' => self::ACCEDER . '.respuestas_cuestionarios_empleados'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . '.respuestas_cuestionarios_empleados'])->syncRoles([$empleado]);

        // Preguntas
        Permission::firstOrCreate(['name' => self::VER . '.preguntas'])->syncRoles([$empleado]);
        // Permission::firstOrCreate(['name' => self::ACCEDER . '.preguntas'])->syncRoles([$empleado]);

        // Citas medicas
        Permission::firstOrCreate(['name' => self::RECHAZAR . '.citas_medicas'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::VER . '.citas_medicas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.citas_medicas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . '.citas_medicas'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.citas_medicas'])->syncRoles([$empleado]);

        // Diagnosticos y recetas
        Permission::firstOrCreate(['name' => self::VER . '.diagnosticos_recetas'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR . '.diagnosticos_recetas'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.diagnosticos_recetas'])->syncRoles([$medico]);

        // Recetas
        Permission::firstOrCreate(['name' => self::VER . '.recetas'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR . '.recetas'])->syncRoles([$medico]);

        // Diagnostico cita medica
        Permission::firstOrCreate(['name' => self::VER . '.diagnosticos_citas_medicas'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR . '.diagnosticos_citas_medicas'])->syncRoles([$medico]);

        // Cies
        Permission::firstOrCreate(['name' => self::VER . '.cies'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.cies'])->syncRoles([$medico]);

        // Laboratorios clinicos
        Permission::firstOrCreate(['name' => self::VER . '.laboratorios_clinicos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.laboratorios_clinicos'])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . '.laboratorios_clinicos'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.laboratorios_clinicos'])->syncRoles([$medico]);

        // Registros empleados examenes
        Permission::firstOrCreate(['name' => self::VER . '.registros_empleados_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR . ".registros_empleados_examenes"])->syncRoles([$medico]);

        //- Solicitudes de examenes (Agrupa los examenes solicitados)
        Permission::firstOrCreate(['name' => self::VER . '.solicitudes_examenes'])->syncRoles([$medico, $compras]);
        Permission::firstOrCreate(['name' => self::ACCEDER . '.solicitudes_examenes'])->syncRoles([$medico, $compras]);
        Permission::firstOrCreate(['name' => self::CREAR . '.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::EDITAR . '.solicitudes_examenes'])->syncRoles([$medico, $compras]);
        Permission::firstOrCreate(['name' => self::AUTORIZAR . '.solicitudes_examenes'])->syncRoles([$compras]); // yloja
        //-- Estados solicitudes examenes (Examenes solicitados)
        Permission::firstOrCreate(['name' => self::CREAR . ".estados_solicitudes_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::VER . ".estados_solicitudes_examenes"])->syncRoles([$medico]);

        //- Configuraciones examenes categorias (Configuracion de formularios tabla para llenado de datos de exámenes)
        Permission::firstOrCreate(['name' => self::VER . ".configuraciones_examenes_categorias"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".configuraciones_examenes_categorias"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR . ".configuraciones_examenes_categorias"])->syncRoles([$medico]);
        //-- Configuraciones examenes campos (Configuracion de formularios tabla para llenado de datos de exámenes)
        Permission::firstOrCreate(['name' => self::VER . ".configuraciones_examenes_campos"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".configuraciones_examenes_campos"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR . ".configuraciones_examenes_campos"])->syncRoles([$medico]);

        //- Detalles resultados examenes (Sirve para agrupar los resultados_examenes)
        Permission::firstOrCreate(['name' => self::VER . ".detalles_resultados_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".detalles_resultados_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR . ".detalles_resultados_examenes"])->syncRoles([$medico]);
        //-- Resultados de examenes (Llenado de los datos de resultados de exámenes en los formularios tablas generados por Configuracion examenes categorias)
        Permission::firstOrCreate(['name' => self::CREAR . ".resultados_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::VER . ".resultados_examenes"])->syncRoles([$medico]);

        // Consultas medicas
        Permission::firstOrCreate(['name' => self::VER . ".consultas_medicas"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".consultas_medicas"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::CREAR . ".consultas_medicas"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => self::EDITAR . ".consultas_medicas"])->syncRoles([$medico]);

        // Tipos aptitudes medicas laborales
        Permission::firstOrCreate(['name' => self::VER . ".tipos_aptitudes_medicas_laborales"])->syncRoles([$empleado]);

        // Tipos evaluaciones medicas retiros
        Permission::firstOrCreate(['name' => self::VER . ".tipos_evaluaciones_medicas_retiros"])->syncRoles([$empleado]);

        // Firmar fichas medicas
        Permission::firstOrCreate(['name' => self::VER . ".firmar_fichas_medicas"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".firmar_fichas_medicas"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . ".firmar_fichas_medicas"])->syncRoles([$empleado]);

        // Fichas aptitudes
        Permission::firstOrCreate(['name' => self::VER . ".fichas_aptitudes"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".fichas_aptitudes"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . ".fichas_aptitudes"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . ".fichas_aptitudes"])->syncRoles([$empleado]);

        // Fichas periodicas
        Permission::firstOrCreate(['name' => self::VER . ".fichas_preocupacionales"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".fichas_preocupacionales"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . ".fichas_preocupacionales"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . ".fichas_preocupacionales"])->syncRoles([$empleado]);

        // Fichas periodicas
        Permission::firstOrCreate(['name' => self::VER . ".fichas_periodicas"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".fichas_periodicas"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . ".fichas_periodicas"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . ".fichas_periodicas"])->syncRoles([$empleado]);

        // Fichas reintegro
        Permission::firstOrCreate(['name' => self::VER . ".fichas_reintegro"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".fichas_reintegro"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . ".fichas_reintegro"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . ".fichas_reintegro"])->syncRoles([$empleado]);

        // Fichas retiro
        Permission::firstOrCreate(['name' => self::VER . ".fichas_retiros"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::ACCEDER . ".fichas_retiros"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::CREAR . ".fichas_retiros"])->syncRoles([$empleado]);
        Permission::firstOrCreate(['name' => self::EDITAR . ".fichas_retiros"])->syncRoles([$empleado]);

        // Religiones
        Permission::firstOrCreate(['name' => self::VER . ".religiones"])->syncRoles([$empleado]);

        // Orientaciones sexuales
        Permission::firstOrCreate(['name' => self::VER . ".orientaciones_sexuales"])->syncRoles([$empleado]);

        // Identidades generos
        Permission::firstOrCreate(['name' => self::VER . ".identidades_generos"])->syncRoles([$empleado]);

        // tipos_habitos_toxicos
        Permission::firstOrCreate(['name' => self::VER . ".tipos_habitos_toxicos"])->syncRoles([$empleado]);

        // tipos_antecedentes
        Permission::firstOrCreate(['name' => self::VER . ".tipos_antecedentes"])->syncRoles([$empleado]);

        // tipos_antecedentes_familiares
        Permission::firstOrCreate(['name' => self::VER . ".tipos_antecedentes_familiares"])->syncRoles([$empleado]);

        // categorias_factores_riesgos
        Permission::firstOrCreate(['name' => self::VER . ".categorias_factores_riesgos"])->syncRoles([$empleado]);

        // tipos_factores_riesgos
        Permission::firstOrCreate(['name' => self::VER . ".tipos_factores_riesgos"])->syncRoles([$empleado]);

        // sistemas_organicos
        Permission::firstOrCreate(['name' => self::VER . ".sistemas_organos"])->syncRoles([$empleado]);

        // regiones_cuerpo
        Permission::firstOrCreate(['name' => self::VER . ".regiones_cuerpo"])->syncRoles([$empleado]);

        // categorias_examenes_fisicos
        Permission::firstOrCreate(['name' => self::VER . ".categorias_examenes_fisicos"])->syncRoles([$empleado]);
    }
}
