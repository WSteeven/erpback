<?php

use App\Models\Proveedor;
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
        Schema::table('proveedores', function (Blueprint $table) {
            // $table->string('sucursal');
            // $table->unsignedBigInteger('parroquia_id')->nullable();
            // $table->text('direccion');
            // $table->string('celular')->nullable();
            // $table->string('telefono')->nullable();
            $table->double('calificacion')->nullable();
            $table->enum('estado_calificado', [Proveedor::SIN_CALIFICAR, Proveedor::SIN_CONFIGURAR, Proveedor::PARCIAL, Proveedor::CALIFICADO])->nullable();

            // $table->foreign('parroquia_id')->references('id')->on('parroquias')->cascadeOnUpdate()->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('proveedores', function (Blueprint $table) {
            //
        });
    }
};
