<?php

use App\Models\Empresa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empresas', function (Blueprint $table) {
            $table->dropColumn('celular', 'telefono', 'ciudad');
            $table->renameColumn('tipo_negocio', 'regimen_tributario');
            // $table->enum('regimen_tributario', [Empresa::RIMPE_EMPRENDEDOR, Empresa::RIMPE_NEGOCIOS_POPULARES, Empresa::GENERAL])->default(null)->after('agente_retencion')->change();
            // $table->enum('tipo_contribuyente', [Empresa::NATURAL, Empresa::SOCIEDAD])->change();
            $table->boolean('lleva_contabilidad')->after('agente_retencion')->default(false);
            $table->boolean('contribuyente_especial')->after('lleva_contabilidad')->default(false);
            $table->text('actividad_economica')->after('contribuyente_especial')->nullable();
        });

        DB::statement("ALTER TABLE empresas CHANGE regimen_tributario regimen_tributario ENUM('RIMPE EMPRENDEDOR', 'RIMPE NEGOCIOS POPULARES', 'GENERAL', 'RIMPE CON IVA', 'RIMPE SIN IVA')");
        DB::statement("ALTER TABLE empresas CHANGE tipo_contribuyente tipo_contribuyente ENUM('PERSONA NATURAL', 'SOCIEDAD', 'NATURAL', 'PRIVADA')");

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
