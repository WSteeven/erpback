<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('claro_tar_tareas', function (Blueprint $table) {
            $table->id();
            $table->string('aid')->unique(); // ID de la actividad (clave principal en OFS)
            $table->string('source'); // Ej: JPC-G009GPON
            $table->string('time_slot')->nullable(); // Ej: 07-10
            $table->string('eta')->nullable();
            $table->string('end_time')->nullable();
            $table->string('aworktype'); // Tipo de trabajo
            $table->string('appt_number')->nullable(); // Número de cita
            $table->string('cname')->nullable(); // Nombre del cliente
            $table->string('activity_workskills')->nullable();
            $table->string('aworkzone')->nullable();
            $table->text('direccion')->nullable(); // -62332
            $table->string('cemail')->nullable();
            $table->string('astatus'); // pendiente, finalizada, etc.
            $table->string('atime_of_booking'); // Fecha de creación
            $table->string('atime_of_assignment')->nullable(); // Fecha de asignación
            $table->decimal('lat', 10, 8)->nullable(); // y
            $table->decimal('lng', 11, 8)->nullable(); // x
            $table->integer('duration')->nullable(); // d: duración en minutos
            $table->integer('travel_time')->nullable(); // G: tiempo de viaje
            $table->integer('sla')->nullable(); // S: SLA
            $table->json('raw_data')->nullable(); // Todo el JSON original
            $table->timestamp('received_at')->useCurrent();
            $table->timestamps();

            $table->index(['source', 'received_at']);
            $table->index('astatus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('claro_tar_tareas');
    }
};
