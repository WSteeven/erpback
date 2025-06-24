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
        Schema::table('configuraciones_generales', function (Blueprint $table) {
            $table->string('sitio_web_erp')->nullable()->after('sitio_web');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('configuraciones_generales', function (Blueprint $table) {
            $table->dropColumn('sitio_web_erp');
        });
    }
};
