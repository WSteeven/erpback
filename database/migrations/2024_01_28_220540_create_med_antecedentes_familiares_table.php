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
        Schema::create('med_antecedentes_familiares', function (Blueprint $table) {
            $table->id();
            $table->text('descripcion');
            $table->unsignedBigInteger('tipo_antecedente_familiar_id');
            $table->string('parentesco');
            $table->unsignedBigInteger('antecedentable_id');
            $table->string('antecedentable_type');
            $table->timestamps();

            $table->foreign('tipo_antecedente_familiar_id')->references('id')->on('med_tipos_antecedentes_familiares')->cascadeOnDelete()->cascadeOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('med_antecedente_familiars');
    }
};
