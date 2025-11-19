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
        Schema::table('tckt_comentarios_tickets', function (Blueprint $table) {
            $table->json('adjuntos')->nullable()->after('comentario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tckt_comentarios_tickets', function (Blueprint $table) {
            $table->dropColumn('adjuntos');
        });
    }
};
