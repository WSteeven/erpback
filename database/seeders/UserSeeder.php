<?php

namespace Database\Seeders;

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
        // Administrador
		$user = User::create([
			'name' => 'MARILÚ',
			'apellidos' => 'JARAMILLO',
			'email' => 'admin@admin.com',
			'email_verified_at' => date("Y-m-d"),
			'password' => bcrypt('password'),
        ]);//->assignRole(User::ROL_ADMINISTRADOR);
    }
}
