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
        $numcol_ingreso = $cantidad_columna_ingresos + 3;
        $numcool_egreso = $cantidad_columna_egresos + 5;
    @endphp
    <table>
        <colgroup span="{{ $numcol_ingreso }}"></colgroup>
        <colgroup span="{{ $numcool_egreso }}"></colgroup>
        <tr>
            <td rowspan="2">Empleado</td>
            <td rowspan="2">Cargo</td>
            <td rowspan="2">Sueldo</td>
            <td rowspan="2">Días Laborados</td>
            <th colspan="{{ $numcol_ingreso }}" scope="colgroup">
                INGRESOS({{ $numcol_ingreso }})</th>
            <td rowspan="2">TOTAL INGRESOS</td>
            <th colspan="{{ $numcool_egreso }}" scope="colgroup">
                EGRESOS({{ $numcool_egreso }})</th>
            <td rowspan="2">TOTAL EGRESOS</td>
            <td rowspan="2">NETO A RECIBIR</td>

        </tr>
        <tr>
            <th scope="col">DECIMO XII</th>
            <th scope="col">DECIMO XIV</th>
            <th scope="col">FONDOS DE RESERVA</th>
            <th scope="col">IESS (9.45%)</th>
            <th scope="col">ANTICIPO</th>
            <th scope="col">PRESTAMO QUIROGRAFARIO</th>
            <th scope="col">PRESTAMO HIPOTECARIO</th>
            <th scope="col">SUPA</th>
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
                @foreach ($rol_pago['ingresos'] as $ingreso)
                    <td>{{ $ingreso->monto }}</td>
                @endforeach
                @if ($rol_pago['ingresos_cantidad_columna'] == 0)
                    @for ($i = 0; $i < $cantidad_columna_ingresos; $i++)
                        <td>0 </td>
                    @endfor
                @endif
                <td>{{ $rol_pago['total_ingreso'] }}</td>
                <td> {{ $rol_pago['iess'] }}</td>
                <td>{{ $rol_pago['anticipo'] }}</td>
                <td>{{ $rol_pago['prestamo_quirorafario'] }}</td>
                <td>{{ $rol_pago['prestamo_hipotecario'] }}</td>
                <td>{{ $rol_pago['supa'] }}</td>
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
    </table>
</body>

</html>
