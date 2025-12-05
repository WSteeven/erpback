<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
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
        Schema::table('rrhh_cp_horario_laboral', function (Blueprint $table) {
            $table->json('dias')->nullable()->after('nombre')->comment('Días de la semana en formato JSON');
            $table->boolean('es_turno_de_noche')->default(false)->after('dias')->comment('Si es turno noche');
            $table->dropColumn('dia');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rrhh_cp_horario_laboral', function (Blueprint $table) {
            $table->string('dia')->after('nombre')->comment('Día de la semana');
            $table->dropColumn(['dias', 'es_turno_de_noche']);
        });
    }
};
