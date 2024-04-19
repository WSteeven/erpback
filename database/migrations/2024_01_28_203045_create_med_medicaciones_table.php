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
        Schema::create('med_medicaciones', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->text('cantidad');

            // Foreign keys
            $table->unsignedBigInteger('medicable_id');
            $table->string('medicable_type');

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
        Schema::dropIfExists('med_medicaciones');
    }
};
