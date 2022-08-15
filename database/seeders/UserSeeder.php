<?php

namespace Database\Seeders;

use App\Models\Localidad;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Coordinador
		$coordinador = User::create([
			'name' => 'MARILÚ',
			'email' => 'mjaramillo@jp.com',
			'email_verified_at' => date("Y-m-d"),
			'password' => bcrypt('password'),
        ]);//->assignRole(User::ROL_ADMINISTRADOR);

        $localidad_coordinador = Localidad::create(['lugar' => 'MACHALA', 'telefono' => '0965421', 'correo' => 'CORREO@GMAIL.COM']);
        $coordinador->empleados()->create(['nombres' => 'MARILÚ', 'apellidos' => 'JARAMILLO', 'identificacion' => '0745125487', 'telefono' => '0987456', 'fecha_nacimiento' => '2019-05-12', 'localidad_id' => $localidad_coordinador->id]);

        // SuperAdministrador
		$user = User::create([
			'name' => 'Sebastian',
			'email' => 'admin@admin.com',
			'password' => bcrypt('password'),
		]);
        // Gerente
		$user = User::create([
			'name' => 'Patricio Pazmiño',
			'email' => 'gerente@jp.com',
			'password' => bcrypt('password'),
		]);
        // Coordinador
		$user = User::create([
			'name' => 'Bryan Chamba',
			'email' => 'bchamba@jp.com',
			'password' => bcrypt('password'),
		]);
        // Coordinador
		$user = User::create([
			'name' => 'Dario Loja',
			'email' => 'dloja@jp.com',
			'password' => bcrypt('password'),
		]);
        // Asistente de bodega 1
		$user = User::create([
			'name' => 'Cristian Albarracin',
			'email' => 'asistentebodega1@jp.com',
			'password' => bcrypt('password'),
		]);
        // Asistente de bodega 2
		$user = User::create([
			'name' => 'Juan Jose Torres',
			'email' => 'asistentebodega2@jp.com',
			'email_verified_at' => date("Y-m-d"),
			'password' => bcrypt('password'),
		]);
        // Tecnico lider
		$user = User::create([
			'name' => 'Pedro Ramirez',
			'email' => 'pramirez@jp.com',
			'email_verified_at' => date("Y-m-d"),
			'password' => bcrypt('password'),
		]);
        // Departamento de compras
		$user = User::create([
			'name' => 'Ingrid Lima',
			'email' => 'asistentecompras@jp.com',
			'email_verified_at' => date("Y-m-d"),
			'password' => bcrypt('password'),
		]);
        // Personal administrativo
		$user = User::create([
			'name' => 'Santiago Sarmiento',
			'email' => 'santiago@jp.com',
			'email_verified_at' => date("Y-m-d"),
			'password' => bcrypt('password'),
		]);
    }
}
