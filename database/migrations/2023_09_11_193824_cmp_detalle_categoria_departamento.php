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
        Schema::create('cmp_detalle_categoria_departamento_proveedor', function(Blueprint $table){
            $table->id();
            $table->unsignedBigInteger('categoria_id')->nullable();
            $table->unsignedBigInteger('departamento_id')->nullable();

            $table->timestamps();

            $table->foreign('categoria_id', 'fk_categoria_id')->references('id')->on('cmp_categorias_ofertas_proveedores')->nullOnDelete()->cascadeOnUpdate();
            $table->foreign('departamento_id', 'fk_departamento_id')->references('id')->on('departamentos')->nullOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cmp_detalle_categoria_departamento_proveedor');
    }
};
