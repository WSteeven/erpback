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
        Schema::table('tipos_tickets', function (Blueprint $table) {
            $table->foreignId('destinatario_id')->constrained('empleados')->onDelete('cascade')->after('categoria_tipo_ticket_id')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tipos_tickets', function (Blueprint $table) {
            $table->dropColumn('destinatario_id');
            $table->dropForeign(['destinatario_id']);
        });
    }
};
