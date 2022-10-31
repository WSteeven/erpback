<?php

namespace Database\Seeders;

use App\Models\Sucursal;
use App\Models\User;
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
        //Localidad
        $localidad_machala = Sucursal::create(['lugar' => 'MACHALA', 'telefono' => '0965421', 'correo' => 'oficina_matriz@jp.com']);
        $localidad_sto_domingo = Sucursal::create(['lugar' => 'SANTO DOMINGO', 'telefono' => '0965421', 'correo' => 'oficina_santo_domingo@jp.com']);
        $localidad_cuenca = Sucursal::create(['lugar' => 'CUENCA', 'telefono' => '0965421', 'correo' => 'oficina_cuenca@jp.com']);
        $localidad_guayaquil = Sucursal::create(['lugar' => 'GUAYAQUIL', 'telefono' => '0965421', 'correo' => 'oficina_guayaquil@jp.com']);

        // SuperAdministrador
        $admin = User::create([
            'name' => 'Superusuario Administrador',
            'email' => 'admin@admin.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_ADMINISTRADOR);
        $admin->empleado()->create([
            'nombres' => 'Superusuario',
            'apellidos' => 'Administrador',
            'sucursal_id' => $localidad_machala->id
        ]);


        // Gerente
        $gerente = User::create([
            'name' => 'Patricio Pazmiño',
            'email' => 'gerente@jp.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_GERENTE);
        $gerente->empleado()->create([
            'nombres' => 'PATRICIO',
            'apellidos' => 'PAZMIÑO',
            'identificacion' => '0702875618001',
            'telefono' => '0987456748',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);


        // Coordinador
        $coordinador = User::create([
            'name' => 'MARILÚ',
            'email' => 'mjaramillo@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_COORDINADOR, User::ROL_EMPLEADO);
        $coordinador->empleado()->create([
            'nombres' => 'MARILÚ',
            'apellidos' => 'JARAMILLO',
            'identificacion' => '0745125487',
            'telefono' => '0987456',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // RR HH
        $recursos_humanos = User::create([
            'name' => 'LUIS MANUEL PESANTEZ MORA',
            'email' => 'manuel@jp.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_RECURSOS_HUMANOS);
        $recursos_humanos->empleado()->create([
            'nombres' => 'LUIS MANUEL',
            'apellidos' => 'PESANTEZ MORA',
            'identificacion' => '0784956230',
            'telefono' => '0987456747',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Coordinador
        $coordinador_sto_domingo = User::create([
            'name' => 'Bryan Chamba',
            'email' => 'bchamba@jp.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_COORDINADOR, User::ROL_BODEGA);
        $coordinador_sto_domingo->empleado()->create([
            'nombres' => 'BRYAN',
            'apellidos' => 'CHAMBA',
            'identificacion' => '0745185287',
            'telefono' => '0987456741',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_sto_domingo->id
        ]);

        // Coordinador
        $coordinador_telconet = User::create([
            'name' => 'Dario Loja',
            'email' => 'dloja@jp.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_COORDINADOR);
        $coordinador_telconet->empleado()->create([
            'nombres' => 'DARIO',
            'apellidos' => 'LOJA',
            'identificacion' => '0745124163',
            'telefono' => '0987456785',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Asistente de bodega 1
        $bodeguero1 = User::create([
            'name' => 'Cristian Albarracin',
            'email' => 'asistentebodega1@jp.com',
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_BODEGA, User::ROL_EMPLEADO);
        $bodeguero1->empleado()->create([
            'nombres' => 'CRISTIAN',
            'apellidos' => 'ALBARRACIN',
            'identificacion' => '0708549625',
            'telefono' => '0987456112',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Asistente de bodega 2
        $bodeguero2 = User::create([
            'name' => 'Juan Jose Torres',
            'email' => 'asistentebodega2@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_BODEGA);
        $bodeguero2->empleado()->create([
            'nombres' => 'JUAN JOSE',
            'apellidos' => 'TORRES',
            'identificacion' => '0704126352',
            'telefono' => '0987456965',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Departamento de compras
        $compras = User::create([
            'name' => 'Ingrid Lima',
            'email' => 'asistentecompras@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_COMPRAS);
        $compras->empleado()->create([
            'nombres' => 'INGRID',
            'apellidos' => 'LIMA',
            'identificacion' => '0708541325',
            'telefono' => '0984568596',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Personal administrativo
        $administrativo = User::create([
            'name' => 'Santiago Sarmiento',
            'email' => 'santiago@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_ADMINISTRATIVO);
        $administrativo->empleado()->create([
            'nombres' => 'SANTIAGO',
            'apellidos' => 'SARMIENTO',
            'identificacion' => '0745963251',
            'telefono' => '0987456235',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '5',
            'sucursal_id' => $localidad_guayaquil->id
        ]);


        // Activos fijos
        $activos_fijos = User::create([
            'name' => 'Pedro Aguilar',
            'email' => 'pedro@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles([User::ROL_ADMINISTRATIVO, User::ROL_ACTIVOS_FIJOS, User::ROL_BODEGA]);
        $activos_fijos->empleado()->create([
            'nombres' => 'PEDRO',
            'apellidos' => 'AGUILAR',
            'identificacion' => '0702041526',
            'telefono' => '0989857463',
            'fecha_nacimiento' => '1993-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        // Contabilidad
        $contabilidad = User::create([
            'name' => 'Isabel Valarezo',
            'email' => 'isa@jp.com',
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_CONTABILIDAD);
        $contabilidad->empleado()->create([
            'nombres' => 'ISABEL',
            'apellidos' => 'VALAREZO',
            'identificacion' => '0780401526',
            'telefono' => '0987457845',
            'fecha_nacimiento' => '2000-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id
        ]);

        /** TECNICOS */
        // Tecnico lider
        $tecnico = User::create([
            'name' => 'Pedro Ramirez',
            'email' => 'pramirez@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_TECNICO_LIDER);
        $tecnico->empleado()->create([
            'nombres' => 'PEDRO',
            'apellidos' => 'RAMIREZ',
            'identificacion' => '0707415236',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'Andres Rogel',
            'email' => 'andres@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_TECNICO_LIDER);
        $tecnico->empleado()->create([
            'nombres' => 'ANDRES',
            'apellidos' => 'ROGEL',
            'identificacion' => '0707474236',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2010-05-12',
            'jefe_id' => '6',
            'sucursal_id' => $localidad_machala->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'Carlos Fernandez',
            'email' => 'cfernandez@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'CARLOS',
            'apellidos' => 'FERNANDEZ',
            'identificacion' => '0707415231',
            'telefono' => '0987456331',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'Omar Gutierrez',
            'email' => 'ogutierrez@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'OMAR',
            'apellidos' => 'GUTIERREZ',
            'identificacion' => '0707415232',
            'telefono' => '0987456332',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 1,
        ]);

        $tecnico = User::create([
            'name' => 'Leo Armijos',
            'email' => 'larmijos@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'LEONARLO',
            'apellidos' => 'ARMIJOS',
            'identificacion' => '0707415233',
            'telefono' => '0987456333',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 2,
        ]);

        $tecnico = User::create([
            'name' => 'Polo Carrión',
            'email' => 'pcarrion@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_TECNICO);
        $tecnico->empleado()->create([
            'nombres' => 'POLO',
            'apellidos' => 'CARRIÓN',
            'identificacion' => '0707415234',
            'telefono' => '0987456334',
            'fecha_nacimiento' => '2019-05-12',
            'jefe_id' => '3',
            'sucursal_id' => $localidad_cuenca->id,
            'grupo_id' => 3,
        ]);

        /**
         * -----------
         * Informatica
         * -----------
         * Coordinador
         */
        $coordinador = User::create([
            'name' => 'Yefraina Lovera',
            'email' => 'ylovera@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->assignRole(User::ROL_COORDINADOR);
        $coordinador->empleado()->create([
            'nombres' => 'YEFRAINA',
            'apellidos' => 'LOVERA',
            'identificacion' => '0707417879',
            'telefono' => '0987498564',
            'fecha_nacimiento' => '2000-05-12',
            'jefe_id' => '2',
            'sucursal_id' => $localidad_machala->id,
        ]);

        $tecnico = User::create([
            'name' => 'Wilson Córdova',
            'email' => 'wcordova@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_ADMINISTRATIVO);
        $tecnico->empleado()->create([
            'nombres' => 'WILSON',
            'apellidos' => 'CORDOVA',
            'identificacion' => '0750360919',
            'telefono' => '0992200572',
            'fecha_nacimiento' => '1997-06-29',
            'jefe_id' => '19',
            'sucursal_id' => $localidad_machala->id,
        ]);
        $tecnico = User::create([
            'name' => 'Juan Cuesta',
            'email' => 'jcuesta@jp.com',
            'email_verified_at' => date("Y-m-d"),
            'password' => bcrypt('password'),
        ])->syncRoles(User::ROL_EMPLEADO, User::ROL_ADMINISTRATIVO);
        $tecnico->empleado()->create([
            'nombres' => 'JUAN',
            'apellidos' => 'CUESTA',
            'identificacion' => '0701234567',
            'telefono' => '0998474965',
            'fecha_nacimiento' => '1996-05-12',
            'jefe_id' => '19',
            'sucursal_id' => $localidad_machala->id,
        ]);

        //

        // $tecnico = User::create([, 'password' => bcrypt('password')])->syncRoles(User::ROL_EMPLEADO, User::ROL_TECNICO);
        //INSERT INTO usuario (nombre,apellido,correo_electronico) VALUES
	 /* $usuarios = [
     ['ANTHONY DANIEL BERSOZA SANCHEZ','abersoza@jeanpazmino.com', date("Y-m-d"),
	 ['ALBERTO ALFREDO MORENO GARINO','amoreno@jeanpazmino.com', date("Y-m-d")],
	 ['DIEGO HERNAN INAMAGUA LALA','dinamagua@jeanpazmino.com', date("Y-m-d")],
	 ['ELVIS AGUSTIN BRICEÑO ARMIJOS','ebriceno@jeanpazmino.com', date("Y-m-d")],
	 ['EYDER ESTEBAN PEREIRA NAVAS','epereira@jeanpazmino.com', date("Y-m-d")],
	 ['FREDY GEOVANNY QUITUISACA SANCHEZ','fquituisaca@jeanpazmino.com', date("Y-m-d")],
	 ['FRANCISCO WLADIMIR SALAZAR SOLORZANO','fsalazar@jeanpazmino.com', date("Y-m-d")],
	 ['JONATHAN FRANCISCO AGUILAR ALCIVAR','jaguilar@jeanpazmino.com', date("Y-m-d")],
	 ['JOSEPH CARLOS CHONILLO SARMIENTO','jchonillo@jeanpazmino.com', date("Y-m-d")],
	 ['JORGE GUILLERMO LEITON RIZZO','jleiton@jeanpazmino.com', date("Y-m-d")],
	 ['JIMMY JAVIER MACAS CHUCHUCA','jmacas@jeanpazmino.com', date("Y-m-d")],
	 ['JAIME RENAN MENDOZA ALCIVAR','jmendoza@jeanpazmino.com', date("Y-m-d")],
	 ['JONNATHAN ISMAEL PACHECO VACA','jpacheco@jeanpazmino.com', date("Y-m-d")],
	 ['JONATHAN ADRIAN PANCHI DIAZ','jpanchi@jeanpazmino.com', date("Y-m-d")],
	 ['JONATHAN ANTONIO TENESACA AZOGUE','jtenesaca@jeanpazmino.com', date("Y-m-d")],
	 ['JACKSON JAVIER VALENCIA ARTURO','jvalencia@jeanpazmino.com', date("Y-m-d")],
	 ['ANGEL GEOVANNY CHAMBA ENRIQUEZ','ACHAMBA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['LUIS  ALBERTO GONZABAY PEÑFIEL','lgonzabay@jeanpazmino.com', date("Y-m-d")],
	 ['DANIEL PATRICK APOLO RAMIREZ','papolo@jeanpazmino.com', date("Y-m-d")],
	 ['PETER ANTONIO FIGUEROA CASTRO','pfigueroa@jeanpazmino.com', date("Y-m-d")],
	 ['STIVEN JEFFERSON BONE MOTTA','sbone@jeanpazmino.com', date("Y-m-d")],
	 ['SIMON GABRIEL CARBO PACHECO','scarbo@jeanpazmino.com', date("Y-m-d")],
	 ['SANTIAGO ALEJANDRO GRANDA GRANDA','sgranda@jeanpazmino.com', date("Y-m-d")],
	 ['VICTOR HUGO RIERA GAVILANEZ','vriera@jeanpazmino.com', date("Y-m-d")],
	 ['WILSON RODRIGO GRANDA FAJARDO','wgranda@jeanpazmino.com', date("Y-m-d")],
	 ['WILMER FABIAN LEMA','wlema@jeanpazmino.com', date("Y-m-d")],
	 ['XAVIER FERNANDO SIRANAULA JIMENEZ','xsiranaula@jeanpazmino.com', date("Y-m-d")],
	 ['JEFFERSON RAFAEL ABAD ZUMBA','JABAD@JEANPAZMINO.COM', date("Y-m-d")],
	 ['CHRISTIAN PAUL TAPIA IDROVO','CTAPIA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['JORDAN DAVID CEDEÑO VERGARA','JCEDENO@JEANPAZMINO.COM', date("Y-m-d")],
	 ['JEISON ENRIQUE SATAMA GARZON','JSATAMA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['SALLY ANDREA CAICEDO PARRA','SCAICEDO@JEANPAZMINO.COM', date("Y-m-d")],
	 ['DARWIN JOSE MERO CASTILLO','DMERO@JEANPAZMINO.COM', date("Y-m-d")],
	 ['BRYAN STEVEN LOPEZ ORMAZA','BLOPEZ@JEANPAZMINO.COM', date("Y-m-d")],
	 ['JOSE NIBALDO MOREIRA GONZALES','JMOREIRA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['JONATHAN AURELIO JORDAN HIDALGO','JJORDAN@JEANPAZMINO.COM', date("Y-m-d")],
	 ['LEONARDO ENRIQUE BAZURTO BRAVO','LBAZURTO@JEANPAZMINO.COM', date("Y-m-d")],
	 ['LUIS MANUEL PESANTEZ MORA','LPESANTEZ@JENAPAZMINO.COM', date("Y-m-d")],
	 ['LUIS FERNANDO BAJAÑA CHONILLO','LBAJANA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['MIGUEL FERNANDO LINO QUIMIS','MLINO@JEANPAZMINO.COM', date("Y-m-d")],
	 ['MIGUEL ANGEL ENGRACIA GARCIA','MENGRACIA@JENAPAZMINO.COM', date("Y-m-d")],
	 ['RONALD DAVID VIVANCO CARPIO','RVIVANCO@JEANPAZMINO.COM', date("Y-m-d")],
	 ['DAVID IVAN CHAMBA MORA','DCHAMBA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['EDISON MARTIN PALMA','EPALMA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['JOSE LUIS ANTEPARA CARDENAS','JANTEPARA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['OSWALDO INA CEDEÑO','OMOLINA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['JORGE LEONARDO MEDINA SOLEDISPA','JMEDINA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['DIEGO HERNAN CRUZ ESPINOZA','DCRUZ@JEANPAZMINO.COM', date("Y-m-d")],
	 ['JHON EDUARDO SAETAMA SANMARTIN','JSAETAMA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['ANGEL DARIO CEDEÑO BRAVO','ACEDENO@JEANPAZMINO.COM', date("Y-m-d")],
	 ['ALBERTO DANIEL SALAS REINA','ASALAS@JEANPAZMINO.COM', date("Y-m-d")],
	 ['ALEXANDER LEONEL TOAPANTA ARROYO','ATOAPANTA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['FIDEL ANTONIO CORTEZ CASTRO','FCASTRO@JEANPAZMINO.COM', date("Y-m-d")],
	 ['JOSE JACINTO PALMA CHOEZ','JPALMA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['JIMMY MARCOS BAQUE PIN','JBAQUE@JEANPAZMINO.COM', date("Y-m-d")],
	 ['WILLIAM SANTIAGO ALDAS OCHOA','WALDAS', date("Y-m-d")],
	 ['ALEXANDER LORENZO TIGUA PILLASAGUA','ATIGUA@JEANPAZMINO.COM', date("Y-m-d")],
	 ['RONALD MAURICIO ALCOCER PIN','RALCOCER@JEANPAZMINO.COM', date("Y-m-d")],
	 ['ANGELO ENEZ','AJIMENEZ@JEANPAZMINO.COM', date("Y-m-d")],
	 ['ANGEL CIRINO MERELLO SANCHEZ','AMERELLO@JEANPAZMINO.COM', date("Y-m-d")],
	 ['LEIVER JOAO CELI GONZALEZ', 'LCELI', date("Y-m-d")]];

        
        //

        foreach ($datos as $fila) {
            DB::insert('INSERT INTO usuario (name, email, email_verified_at) VALUES(?,?,?)', $fila);
        }*/
    }
}
