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
        Schema::create('hunt_posiciones_hunter', function (Blueprint $table) {
            $table->id();
            // Fuente del dato (hunter_stream, api, etc.)
            $table->string('source')->index();
            $table->string('imei', 20)->index();
            $table->string('placa', 10)->nullable()->index();

            // Coordenadas (precisión alta)
            $table->decimal('lat', 10, 8);
            $table->decimal('lng', 11, 8);

            // Datos GPS
            $table->float('velocidad')->nullable(); // km/h
            $table->unsignedSmallInteger('rumbo')->nullable(); // 0-359 grados
            $table->string('alt')->nullable(); // altitud como string (Hunter lo manda así)

            // Fechas
            $table->dateTime('fecha'); // Fecha del reporte GPS
            $table->dateTime('received_at')->useCurrent(); // Cuándo llegó al servidor

            // Estado del vehículo
            $table->boolean('encendido')->default(false)->index();

            // Datos de reporte
            $table->string('direccion')->nullable();
            $table->string('tipo_reporte')->nullable();
            $table->string('estado', 10)->nullable(); // "00000" o similar

            // Flags
            $table->json('flags_binarios')->nullable(); // "001000100..."
            $table->json('flags')->nullable(); // { encendido: true, ... }

            // Raw completo
            $table->text('raw_data');

            // Índices compuestos para consultas frecuentes
            $table->index(['imei', 'fecha']);
            $table->index(['encendido', 'fecha']);
            $table->index('received_at');

            // Índice espacial para búsquedas por proximidad
            $table->point('location')->nullable(false);
            $table->spatialIndex('location');
            $table->timestamps();
        });

        // ÍNDICE ESPACIAL (solo después de crear la columna)
        \DB::statement('ALTER TABLE hunt_posiciones_hunter ADD SPATIAL INDEX idx_location (location)');

        // Trigger: actualizar location solo si lat/lng válidos
        \DB::statement("
            CREATE TRIGGER trg_hunt_posiciones_hunter_location
            BEFORE INSERT ON hunt_posiciones_hunter
            FOR EACH ROW
            BEGIN
                IF NEW.lat IS NOT NULL AND NEW.lng IS NOT NULL
                   AND NEW.lat BETWEEN -90 AND 90
                   AND NEW.lng BETWEEN -180 AND 180 THEN
                    SET NEW.location = POINT(NEW.lng, NEW.lat);
                ELSE
                    SET NEW.location = POINT(0, 0); -- Valor seguro (no indexado)
                END IF;
            END
        ");

        \DB::statement("
            CREATE TRIGGER trg_hunt_posiciones_hunter_location_update
            BEFORE UPDATE ON hunt_posiciones_hunter
            FOR EACH ROW
            BEGIN
                IF NEW.lat IS NOT NULL AND NEW.lng IS NOT NULL
                   AND NEW.lat BETWEEN -90 AND 90
                   AND NEW.lng BETWEEN -180 AND 180 THEN
                    SET NEW.location = POINT(NEW.lng, NEW.lat);
                ELSE
                    SET NEW.location = POINT(0, 0);
                END IF;
            END
        ");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        \DB::statement('DROP TRIGGER IF EXISTS trg_hunt_posiciones_hunter_location_update');
        \DB::statement('DROP TRIGGER IF EXISTS trg_hunt_posiciones_hunter_location');
        \DB::statement('ALTER TABLE hunt_posiciones_hunter DROP INDEX IF EXISTS idx_location');
        Schema::dropIfExists('hunt_posiciones_hunter');
    }
};
