<?php

use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE `transferencias` MODIFY `estado` ENUM('PENDIENTE', 'TRANSITO', 'COMPLETADO', 'ANULADO') NOT NULL");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE `transferencias` MODIFY `estado` ENUM('PENDIENTE', 'TRANSITO', 'COMPLETADO') NOT NULL");
    }
};
