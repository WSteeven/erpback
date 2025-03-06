<?php

namespace Database\Seeders\Seguridad;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Seguridad\PermisosSeeder"
     * @return void
     */
    public function run()
    {
        $supervisor_guardias = Role::firstOrCreate(['name' => User::ROL_SUPERVISOR_GUARDIAS]);
        $guardia = Role::firstOrCreate(['name' => User::ROL_GUARDIA]);
        $consulta = Role::firstOrCreate(['name' => User::ROL_CONSULTA]);
        $gerente = Role::firstOrCreate(['name' => User::ROL_GERENTE]);

        // Modulo Seguridad
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'modulo_seguridad'])->syncRoles([$supervisor_guardias, $guardia, $consulta, $gerente]);

        // Bitacoras
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'bitacoras'])->syncRoles([$supervisor_guardias, $guardia, $consulta, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'bitacoras'])->syncRoles([$supervisor_guardias, $guardia, $consulta, $gerente]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'bitacoras'])->syncRoles([$guardia]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'bitacoras'])->syncRoles([$guardia]);

        // Tipos de eventos
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'tipos_eventos_bitacoras'])->syncRoles([$supervisor_guardias, $gerente, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'tipos_eventos_bitacoras'])->syncRoles([$supervisor_guardias, $gerente, $consulta, $guardia]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'tipos_eventos_bitacoras'])->syncRoles([$consulta, $supervisor_guardias]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'tipos_eventos_bitacoras'])->syncRoles([$consulta, $supervisor_guardias]);

        // Zonas
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'zonas'])->syncRoles([$supervisor_guardias, $gerente, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'zonas'])->syncRoles([$supervisor_guardias, $gerente, $consulta, $guardia]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'zonas'])->syncRoles([$consulta, $supervisor_guardias]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'zonas'])->syncRoles([$consulta, $supervisor_guardias]);

        // Prendas zonas
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'prendas_zonas'])->syncRoles([$supervisor_guardias, $gerente, $consulta]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'prendas_zonas'])->syncRoles([$supervisor_guardias, $gerente, $consulta, $guardia]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'prendas_zonas'])->syncRoles([$consulta]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'prendas_zonas'])->syncRoles([$consulta]);

        // Informacion visitantes
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'informacion_visitantes'])->syncRoles([$supervisor_guardias, $gerente, $consulta, $guardia]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'informacion_visitantes'])->syncRoles([$supervisor_guardias, $gerente, $consulta, $guardia]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'informacion_visitantes'])->syncRoles([$guardia]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'informacion_visitantes'])->syncRoles([$guardia]);

        // Actividades bitácoras
        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'actividades_bitacoras'])->syncRoles([$supervisor_guardias, $gerente, $consulta, $guardia]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'actividades_bitacoras'])->syncRoles([$supervisor_guardias, $gerente, $consulta, $guardia]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'actividades_bitacoras'])->syncRoles([$guardia]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'actividades_bitacoras'])->syncRoles([$guardia]);
    }
}
