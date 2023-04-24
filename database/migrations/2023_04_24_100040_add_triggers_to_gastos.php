<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
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
        DB::unprepared('DROP TRIGGER IF EXISTS `verificar_comprobante`');
        DB::unprepared('
            CREATE TRIGGER `verificar_comprobante` BEFORE INSERT ON `gastos` FOR EACH ROW BEGIN
                DECLARE factura_existente, factura_pendiente_existente, comprobante_existente, comprobante_pendiente_existente BOOLEAN;

                SELECT COUNT(*) INTO factura_existente
                FROM gastos
                WHERE factura IS NOT NULL
                AND factura != ""
                AND ruc = NEW.ruc
                AND factura = NEW.factura
                AND estado = 1;

                SELECT COUNT(*) INTO factura_pendiente_existente
                FROM gastos
                WHERE factura IS NOT NULL
                AND factura != ""
                AND ruc = NEW.ruc
                AND factura = NEW.factura
                AND estado = 3;

                SELECT COUNT(*) INTO comprobante_existente
                FROM gastos
                WHERE num_comprobante IS NOT NULL
                AND num_comprobante = NEW.num_comprobante
                AND estado = 1;

                SELECT COUNT(*) INTO comprobante_pendiente_existente
                FROM gastos
                WHERE num_comprobante IS NOT NULL
                AND num_comprobante = NEW.num_comprobante
                AND estado = 3;

                IF factura_existente > 0 OR factura_pendiente_existente > 0 THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "El número de factura ya se encuentra registrado";
                END IF;

                IF comprobante_existente > 0 OR comprobante_pendiente_existente > 0 THEN
                    SIGNAL SQLSTATE "45000" SET MESSAGE_TEXT = "El número de comprobante o factura ya se encuentra registrado";
                END IF;
            END
        ');
    }

    /**
     * Reverse the migration.
     *
     * @return void
     */
    public function down()
    {
        DB::unprepared('DROP TRIGGER IF EXISTS `verificar_comprobante`');
    }
};
