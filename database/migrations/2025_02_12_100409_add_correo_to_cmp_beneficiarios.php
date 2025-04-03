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
        Schema::table('cmp_beneficiarios', function (Blueprint $table) {
            $table->string('correo')->nullable()->after('localidad');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('cmp_beneficiarios', function (Blueprint $table) {
            $table->dropColumn('correo');
        });
    }
};
