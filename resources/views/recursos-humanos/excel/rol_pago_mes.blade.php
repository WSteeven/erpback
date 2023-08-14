<!DOCTYPE html>
<html>

<head>
    <style>
        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    @php

        $numcol_ingreso = $cantidad_columna_ingresos + 5;
        $numcol_egreso = $cantidad_columna_egresos + 2;
        $tiene_supa = $sumatoria['supa'] > 0;
        $tiene_bonificacion = $sumatoria['bonificacion'] > 0;
        $tiene_bono_recurente = $sumatoria['bono_recurente'] > 0;
        if ($tiene_bono_recurente) {
            $numcol_ingreso = $cantidad_columna_ingresos + 6;
        }
        if ($tiene_bonificacion) {
            $numcol_ingreso = $cantidad_columna_ingresos + 6;
        }
        if ($tiene_bonificacion && $tiene_bono_recurente) {
            $numcol_ingreso = $cantidad_columna_ingresos + 7;
        }

        if ($tiene_supa) {
            $numcol_egreso = $cantidad_columna_egresos + 3;
        }

    @endphp
    <table>
        <colgroup span="{{ $numcol_ingreso }}"></colgroup>
        <colgroup span="{{ $numcol_egreso }}"></colgroup>
        <tr>
            <td rowspan="2">Empleado</td>
            <td rowspan="2">Cargo</td>
            <td rowspan="2">Sueldo</td>
            <td rowspan="2">Días Laborados</td>
            <th colspan="{{ $numcol_ingreso }}" scope="colgroup">INGRESOS</th>
            <td rowspan="2">TOTAL INGRESOS</td>
            <th colspan="{{ $numcol_egreso }}" scope="colgroup">EGRESOS</th>
            <td rowspan="2">TOTAL EGRESOS</td>
            <td rowspan="2">NETO A RECIBIR</td>

        </tr>
        <tr>
            <th scope="col">DECIMO XII</th>
            <th scope="col">DECIMO XIV</th>
            <th scope="col">FONDOS DE RESERVA</th>
            <th scope="col">IESS (9.45%)</th>
            <th scope="col">ANTICIPO</th>
            @if ($tiene_bonificacion)
                <th scope="col">BONIFICACION</th>
            @endif
            @if ($tiene_bono_recurente)
                <th scope="col">BONO RECURENTE</th>
            @endif
            @foreach ($columnas_ingresos as $ingreso)
                <th scope="col">{{ $ingreso }}</th>
            @endforeach
            <th scope="col">PRESTAMO QUIROGRAFARIO</th>
            <th scope="col">PRESTAMO HIPOTECARIO</th>
            @if ($tiene_supa)
                <th scope="col">SUPA</th>
            @endif
            @foreach ($columnas_egresos as $egreso)
                <th scope="col">{{ $egreso }}</th>
            @endforeach
        </tr>
        @foreach ($roles_pago as $rol_pago)
            <tr>
                <td>{{ $rol_pago['empleado_info'] }}</td>
                <td>{{ $rol_pago['cargo'] }}</td>
                <td>{{ $rol_pago['sueldo'] }}</td>
                <td>{{ $rol_pago['dias_laborados'] }}</td>
                <td>{{ $rol_pago['decimo_tercero'] }}</td>
                <td>{{ $rol_pago['decimo_cuarto'] }}</td>
                <td> {{ $rol_pago['fondos_reserva'] }}</td>
                <td> {{ $rol_pago['iess'] }}</td>
                <td>{{ $rol_pago['anticipo'] }}</td>
                @if ($tiene_bonificacion)
                    <td>{{ $rol_pago['bonificacion'] }}</td>
                @endif
                @if ($tiene_bono_recurente)
                    <td>{{ $rol_pago['bono_recurente'] }}</td>
                @endif
                @foreach ($rol_pago['ingresos'] as $ingreso)
                    <td>{{ $ingreso->monto }}</td>
                @endforeach
                @if ($rol_pago['ingresos_cantidad_columna'] == 0)
                    @for ($i = 0; $i < $cantidad_columna_ingresos; $i++)
                        <td>0 </td>
                    @endfor
                @endif
                <td>{{ $rol_pago['total_ingreso'] }}</td>
                <td>{{ $rol_pago['prestamo_quirorafario'] }}</td>
                <td>{{ $rol_pago['prestamo_hipotecario'] }}</td>
                @if ($tiene_supa)
                    <td>{{ $rol_pago['supa'] }}</td>
                @endif
                @foreach ($rol_pago['egresos'] as $descuento)
                    <td> {{ $descuento->monto }} </td>
                @endforeach
                @if ($rol_pago['egresos_cantidad_columna'] == 0)
                    @for ($i = 0; $i < $cantidad_columna_egresos; $i++)
                        <td>0 </td>
                    @endfor
                @endif
                <td>{{ $rol_pago['total_egreso'] }}</td>
                <td>{{ $rol_pago['total'] }}</td>
            </tr>
        @endforeach
        <tr>
            <td colspan="2"><strong>TOTAL:&nbsp;</strong></td>
            <td> {{ number_format($sumatoria['sueldo'], 2, ',', '.') }}</td>
            <td>&nbsp;</td>
            <td>{{ number_format($sumatoria['decimo_tercero'], 2, ',', '.') }}</td>
            <td>{{ number_format($sumatoria['decimo_cuarto'], 2, ',', '.') }}</td>
            <td>{{ number_format($sumatoria['fondos_reserva'], 2, ',', '.') }}</td>
            <td>{{ number_format($sumatoria['iess'], 2, ',', '.') }}</td>
            <td>{{ number_format($sumatoria['anticipo'], 2, ',', '.') }}</td>
            @foreach ($sumatoria_ingresos as $sumatoria_ingreso)
                <td>{{ number_format($sumatoria_ingreso, 2, ',', '.') }}</td>
            @endforeach
            <td>{{ number_format($sumatoria['total_ingreso'], 2, ',', '.') }}</td>
            <td>{{ number_format($sumatoria['prestamo_quirorafario'], 2, ',', '.') }}</td>
            <td>{{ number_format($sumatoria['prestamo_hipotecario'], 2, ',', '.') }}</td>
            <td>{{ number_format($sumatoria['supa'], 2, ',', '.') }}</td>
            @foreach ($sumatoria_egresos as $sumatoria_egreso)
                <td>{{ number_format($sumatoria_egreso, 2, ',', '.') }}</td>
            @endforeach
            <td>{{ number_format($sumatoria['total_egreso'], 2, ',', '.') }}</td>
            <td>{{ number_format($sumatoria['total'], 2, ',', '.') }}</td>
        </tr>
    </table>
</body>

</html>
