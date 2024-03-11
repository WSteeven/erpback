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
        // $medico = Role::firstOrCreate(['name' => User::ROL_MEDICO]);

        // Gestionar pacientes
        Permission::firstOrCreate(['name' => 'puede.acceder.gestionar_pacientes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => 'puede.ver.gestionar_pacientes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => 'puede.editar.gestionar_pacientes'])->syncRoles([$medico]);

        Permission::firstOrCreate(['name' => 'puede.ver.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => 'puede.crear.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => 'puede.editar.solicitudes_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => 'puede.autorizar.solicitudes_examenes']); // yloja
        Permission::firstOrCreate(['name' => 'puede.ver.reporte_cuestionarios_psicosocial'])->syncRoles([$medico]);

        Permission::firstOrCreate(['name' => 'puede.rechazar.citas_medicas'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => 'puede.ver.cies'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => 'puede.ver.registros_empleados_examenes'])->syncRoles([$medico]);
        Permission::firstOrCreate(['name' => "{self::CREAR} registros_empleados_examenes"])->syncRoles([$medico]);
    }
}
