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
            $table->string('ciclos'); //30 dias, 28 dias, 31 dias, etc.
            $table->date('fecha_ultima_menstruacion');
            $table->integer('gestas');
            $table->integer('partos');
            $table->integer('cesareas');
            $table->integer('abortos');
            $table->unsignedBigInteger('antecedente_personal_id');

            // Foreign keys
            $table->foreign('antecedente_personal_id', 'fk_antecedente_personal')->references('id')->on('med_antecedentes_personales')->cascadeOnDelete()->cascadeOnUpdate();

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
