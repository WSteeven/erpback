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
        Schema::create('med_antecedentes_gineco_obstetricos', function (Blueprint $table) {
            $table->id();
            $table->text('menarquia');// a los 12 años, a los 14 años, etc...
            $table->integer('ciclos'); //30 dias, 28 dias, 31 dias, etc.
            $table->date('fecha_ultima_menstruacion');
            $table->integer('gestas');
            $table->integer('partos');
            $table->integer('cesareas');
            $table->integer('abortos');
            $table->unsignedBigInteger('ficha_preocupacional_id');

            // Foreign keys
            $table->foreign('ficha_preocupacional_id')->references('id')->on('med_fichas_preocupacionales')->cascadeOnDelete()->cascadeOnUpdate();

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
        Schema::dropIfExists('med_antecedentes_gineco_obstetricos');
    }
};
