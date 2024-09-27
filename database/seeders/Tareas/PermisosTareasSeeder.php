<?php

namespace Database\Seeders\RecursosHumanos\Tareas;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Src\Config\Permisos;

class PermisosTareasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     * php artisan db:seed --class="Database\Seeders\Tareas\PermisosTareasSeeder"
     * @return void
     */
    public function run()
    {
        $jefeTecnico = Role::firstOrCreate(['name' => User::ROL_JEFE_TECNICO]);
        $bodega = Role::firstOrCreate(['name' => User::ROL_BODEGA]);
        $coordinadorBodega = Role::firstOrCreate(['name' => User::ROL_COORDINADOR_BODEGA]);
        $coordinador = Role::firstOrCreate(['name' => User::ROL_COORDINADOR]); // Coordinador Tecnico
        $coordinadorBackup = Role::firstOrCreate(['name' => User::ROL_COORDINADOR_BACKUP]);
        $administrador = Role::firstOrCreate(['name' => User::ROL_ADMINISTRADOR]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'reportes_materiales_utilizados'])->syncRoles([$jefeTecnico, $bodega, $coordinadorBodega, $coordinador, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'reportes_materiales_utilizados'])->syncRoles([$jefeTecnico, $bodega, $coordinadorBodega, $coordinador, $administrador]);

        Permission::firstOrCreate(['name' => Permisos::ACCEDER . 'alimentacion_grupo'])->syncRoles([$jefeTecnico, $coordinador, $coordinadorBackup, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::VER . 'alimentacion_grupo'])->syncRoles([$jefeTecnico, $coordinador, $coordinadorBackup, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::CREAR . 'alimentacion_grupo'])->syncRoles([$jefeTecnico, $coordinador, $coordinadorBackup, $administrador]);
        Permission::firstOrCreate(['name' => Permisos::EDITAR . 'alimentacion_grupo'])->syncRoles([$jefeTecnico, $coordinador, $coordinadorBackup, $administrador]);
    }
}
