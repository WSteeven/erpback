<?php

use App\Models\Empleado;
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
        Schema::create('empleados', function (Blueprint $table) {
            $table->id();
            $table->string('identificacion')->unique()->nullable();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('telefono')->nullable();
            $table->date('fecha_nacimiento')->nullable();
            // $table->enum('estado', [Empleado::ACTIVO, Empleado::INACTIVO])->default(Empleado::ACTIVO);
            $table->boolean('estado')->default(true);
            // $table->boolean('es_responsable_grupo')->default(true);

            // Foreign keys
            $table->unsignedBigInteger('grupo_id')->nullable();
            $table->foreign('grupo_id')->references('id')->on('grupos')->onDelete(null)->onUpdate('cascade');

            $table->unsignedBigInteger('jefe_id')->nullable();
            $table->foreign('jefe_id')->references('id')->on('empleados')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('usuario_id'); //fk usuario que inicia sesion
            $table->foreign('usuario_id')->references('id')->on('users')->onDelete('cascade')->onUpdate('cascade');

            $table->unsignedBigInteger('canton_id')->nullable();
            $table->foreign('canton_id')->references('id')->on('cantones')->onDelete(NULL)->onUpdate('cascade');

            $table->unsignedBigInteger('cargo_id')->nullable();
            $table->foreign('cargo_id')->references('id')->on('cargos')->onDelete(null)->onUpdate('cascade');

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
        Schema::dropIfExists('empleados');
    }
};
