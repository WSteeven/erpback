@echo off
setlocal enabledelayedexpansion
echo Instalador de  ventas de claro
set seeders=EscenarioVentasJPSeeder BonosPorcentualesSeeder BonosSeeder ComisionesSeeder EsquemaComisionSeeder TipoChargebackSeeder ModalidadSeeder PlanesSeeder ProductosClaroSeeder
echo Ejecutando comandos porfavor espere ...
for %%s in (%seeders%) do (
    php artisan db:seed --class=%%s
)

