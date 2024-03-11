<?php

namespace Database\Seeders;

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
        $medico = Role::firstOrCreate(['name' => User::ROL_MEDICO]);

        // Gestionar pacientes
        Permission::firstOrCreate(['name' => '{self::VER}.gestionar_pacientes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => '{self::VER}.gestionar_pacientes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => '{self::EDITAR}.gestionar_pacientes'])->syncRoles([$medico]);

        Permission::firstOrCreate(['name' => '{self::VER}.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => '{self::CREAR}.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => '{self::EDITAR}.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => '{self::AUTORIZAR}.solicitudes_examenes']); // yloja
        Permission::firstOrCreate(['name' => '{self::VER}.reporte_cuestionarios_psicosocial'])->syncRoles([$medico]);

        Permission::firstOrCreate(['name' => '{self::RECHAZAR}.citas_medicas'])->syncRoles([$medico]);
        
        Permission::firstOrCreate(['name' => '{self::VER}.cies'])->syncRoles([$medico]);

        Permission::firstOrCreate(['name' => '{self::VER}.registros_empleados_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => "{self::CREAR}.registros_empleados_examenes"])->syncRoles([$medico]);

        Permission::firstOrCreate(['name' => "{self::CREAR}.estados_solicitudes_examenes"])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => "{self::VER}.estados_solicitudes_examenes"])->syncRoles([$medico]);
    }
}
