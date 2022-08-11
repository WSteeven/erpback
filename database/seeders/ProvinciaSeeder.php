<?php

namespace Database\Seeders;

use App\Models\Provincia;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinciaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Provincia::create([
            'id'=> 1,
            'provincia'=> 'AZUAY',
            'cod_provincia' => '01'
        ]);

        Provincia::create( [
    		'id'=>2,
    		'provincia'=>'BOLIVAR',
    		'cod_provincia'=>'02'
    	] );

    	Provincia::create( [
    		'id'=>3,
    		'provincia'=>'CAÑAR',
    		'cod_provincia'=>'03'
    	] );

    	Provincia::create( [
    		'id'=>4,
    		'provincia'=>'CARCHI',
    		'cod_provincia'=>'04'
    	] );

    	Provincia::create( [
    		'id'=>5,
    		'provincia'=>'COTOPAXI',
    		'cod_provincia'=>'05'
    	] );

    	Provincia::create( [
    		'id'=>6,
    		'provincia'=>'CHIMBORAZO',
    		'cod_provincia'=>'06'
    	] );

    	Provincia::create( [
    		'id'=>7,
    		'provincia'=>'EL ORO',
    		'cod_provincia'=>'07'
    	] );

    	Provincia::create( [
    		'id'=>8,
    		'provincia'=>'ESMERALDAS',
    		'cod_provincia'=>'08'
    	] );

    	Provincia::create( [
    		'id'=>9,
    		'provincia'=>'GUAYAS',
    		'cod_provincia'=>'09'
    	] );

    	Provincia::create( [
    		'id'=>10,
    		'provincia'=>'IMBABURA',
    		'cod_provincia'=>'10'
    	] );

    	Provincia::create( [
    		'id'=>11,
    		'provincia'=>'LOJA',
    		'cod_provincia'=>'11'
    	] );

    	Provincia::create( [
    		'id'=>12,
    		'provincia'=>'LOS RIOS',
    		'cod_provincia'=>'12'
    	] );

    	Provincia::create( [
    		'id'=>13,
    		'provincia'=>'MANABI',
    		'cod_provincia'=>'13'
    	] );

    	Provincia::create( [
    		'id'=>14,
    		'provincia'=>'MORONA SANTIAGO',
    		'cod_provincia'=>14
    	] );

    	Provincia::create( [
    		'id'=>15,
    		'provincia'=>'NAPO',
    		'cod_provincia'=>'15'
    	] );

    	Provincia::create( [
    		'id'=>16,
    		'provincia'=>'PASTAZA',
    		'cod_provincia'=>'16'
    	] );

    	Provincia::create( [
    		'id'=>17,
    		'provincia'=>'PICHINCHA',
    		'cod_provincia'=>'17'
    	] );

    	Provincia::create( [
    		'id'=>18,
    		'provincia'=>'TUNGURAHUA',
    		'cod_provincia'=>'18'
    	] );

    	Provincia::create( [
    		'id'=>19,
    		'provincia'=>'ZAMORA CHINCHIPE',
    		'cod_provincia'=>'19'
    	] );

    	Provincia::create( [
    		'id'=>20,
    		'provincia'=>'GALAPAGOS',
    		'cod_provincia'=>'20'
    	] );

    	Provincia::create( [
    		'id'=>21,
    		'provincia'=>'SUCUMBIOS',
    		'cod_provincia'=>'21'
    	] );

    	Provincia::create( [
    		'id'=>22,
    		'provincia'=>'ORELLANA',
    		'cod_provincia'=>'22'
    	] );

    	Provincia::create( [
    		'id'=>23,
    		'provincia'=>'SANTO DOMINGO DE LOS TSACHILAS',
    		'cod_provincia'=>'23'
    	] );

		Provincia::create( [
    		'id'=>24,
    		'provincia'=>'SANTA ELENA',
    		'cod_provincia'=>'24'
    	] );

    	Provincia::create( [
    		'id'=>25,
    		'provincia'=>'ZONAS NO DELIMITADAS',
    		'cod_provincia'=>'90'
    	] );
    }
}
