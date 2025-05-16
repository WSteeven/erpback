<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ventas_clientes_claro', function (Blueprint $table) {
            $table->enum('tipo_cliente', ['cliente', 'prospecto'])->after('telefono2');
            $table->unsignedBigInteger('canton_id')->nullable()->after('tipo_cliente');
            $table->unsignedBigInteger('parroquia_id')->nullable()->after('canton_id');
            $table->string('correo_electronico')->nullable()->after('parroquia_id');
            $table->string('foto_cedula_frontal')->nullable()->after('correo_electronico');
            $table->string('foto_cedula_posterior')->nullable()->after('foto_cedula_frontal');
            $table->date('fecha_expedicion_cedula')->nullable()->after('foto_cedula_posterior');

            // Llaves foráneas
            $table->foreign('canton_id')->references('id')->on('cantones')->onDelete('set null');
            $table->foreign('parroquia_id')->references('id')->on('parroquias')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('ventas_clientes_claro', function (Blueprint $table) {
            $table->dropForeign(['canton_id']);
            $table->dropForeign(['parroquia_id']);
            $table->dropColumn([
                'tipo_cliente',
                'canton_id',
                'parroquia_id',
                'correo_electronico',
                'foto_cedula_frontal',
                'foto_cedula_posterior',
                'fecha_expedicion_cedula',
            ]);
        });
    }
};
