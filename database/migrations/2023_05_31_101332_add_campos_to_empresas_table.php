<?php

use App\Models\Empresa;
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
        Schema::table('empresas', function (Blueprint $table) {
            $table->string('celular')->nullable()->after('nombre_comercial');
            $table->unsignedBigInteger('canton_id')->nullable()->after('correo');
            $table->string('ciudad')->nullable()->after('canton_id');
            $table->boolean('agente_retencion')->after('direccion')->default(false);
            $table->enum('tipo_negocio', [Empresa::RIMPE_IVA, Empresa::RIMPE_SIN_IVA])->default(null)->after('agente_retencion');


            $table->foreign('canton_id')->references('id')->on('cantones');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empresas', function (Blueprint $table) {
            //
        });
    }
};
