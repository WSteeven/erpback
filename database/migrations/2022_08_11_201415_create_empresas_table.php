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
        Schema::create('empresas', function (Blueprint $table) {
            $table->id();
            $table->string('identificacion')->unique();
            $table->enum('tipo_contribuyente', [Empresa::NATURAL, Empresa::PRIVADA, Empresa::PUBLICA])->required();
            $table->string('razon_social')->required();
            $table->string('nombre_comercial')->nullable();
            $table->string('telefono')->nullable();
            $table->string('correo')->nullable();
            $table->text('direccion')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('empresas');
    }
};
