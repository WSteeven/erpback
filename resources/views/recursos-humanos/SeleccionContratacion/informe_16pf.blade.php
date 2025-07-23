<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Informe de Personalidad 16PF</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        .titulo {
            font-size: 18px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 20px;
        }
        .seccion {
            margin-bottom: 25px;
        }
        .grafico {
            text-align: center;
            margin-top: 15px;
        }
    </style>
</head>
<body>
<div class="titulo">INFORME DE PERSONALIDAD - 16PF-5</div>

<div class="seccion">
    <strong>Nombre:</strong> {{ $nombre }} <br>
    <strong>Sexo:</strong> {{ $sexo }} <br>
    <strong>Fecha de Evaluación:</strong> {{ $fecha }}
</div>

<div class="seccion">
    <h4>Escalas Primarias</h4>
    <div class="grafico">
        <img src="{{ $graficoPrimario }}" width="500" alt="grafico1">
    </div>
</div>

<div class="seccion">
    <h4>Dimensiones Globales</h4>
    <div class="grafico">
        <img src="{{ $graficoGlobal }}" width="400" alt="grafico2">
    </div>
</div>
</body>
</html>
