@php
    use Src\Shared\Utils;
@endphp
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Préstamos Empresariales</title>
    <style>
        @page {
            margin: 100px 25px;
        }

        .header {
            position: fixed;
            top: -55px;
            left: 0;
            right: 0;
            height: 80px;
            text-align: center;
            line-height: 35px;
        }

        table {
            width: 100%;
            font-family: Verdana, Arial, Helvetica, sans-serif;
            font-size: 10px;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #000;
            padding: 4px;
            text-align: center;
        }

        th {
            background-color: #c5d9f1;
        }
    </style>
</head>
<body>
<div class="header">
    <table>
        <tr>
            <td style="width: 17%">
                <img src="{{ Utils::getImagePath($configuracion->logo_claro) }}" width="90" alt="logo empresa">
            </td>
            <td style="width: 83%; font-size:16px; font-weight:bold">
                <div>{{$configuracion->razon_social}}</div>
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="font-size:12px">
                <strong>REPORTE DE PRÉSTAMOS EMPRESARIALES</strong>
            </td>
        </tr>
    </table>
</div>
<table style="margin-top: 100px;">
    <thead>
    <tr>
        <th style="background-color:#DBDBDB; font-weight: bold">Empleado</th>
        <th style="background-color:#DBDBDB; font-weight: bold">Fecha Préstamo</th>
        <th style="background-color:#DBDBDB; font-weight: bold">Monto</th>
        <th style="background-color:#DBDBDB; font-weight: bold">Estado</th>
        <th style="background-color:#DBDBDB; font-weight: bold">Cantidad Cuotas</th>
        <th style="background-color:#DBDBDB; font-weight: bold">Monto Pagado</th>
        <th style="background-color:#DBDBDB; font-weight: bold">Monto Pendiente</th>
        <th style="background-color:#DBDBDB; font-weight: bold">Valor Cuota</th>
        <th style="background-color:#DBDBDB; font-weight: bold">Fecha Primera Cuota</th>
        <th style="background-color:#DBDBDB; font-weight: bold">Fecha Última Cuota</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($prestamos as $prestamo)
        <tr>
            <td>{{ $prestamo['empleado'] }}</td>
            <td>{{ $prestamo['fecha'] }}</td>
            <td>$ {{ $prestamo['monto'] }}</td>
            <td>{{ $prestamo['estado'] }}</td>
            <td>{{ $prestamo['cantidad_cuotas'] }}</td>
            <td>{{ $prestamo['monto_pagado']}}</td>
            <td>{{ $prestamo['monto_pendiente']}}</td>
            <td>{{ $prestamo['valor_cuota']}}</td>
            <td>{{ $prestamo['fecha_primera_cuota'] }}</td>
            <td>{{ $prestamo['fecha_ultima_cuota'] }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
