<!DOCTYPE html>
<html lang="es">
<title>cecofal.com Tu solucion cooperativa</title>

<head>
    <style>
        /** Establezca los márgenes de la página en 0, por lo que el pie de página y el encabezado puede ser de altura   y     anchura completas. **/

        @page {
            margin: 0cm 0cm;
        }

        /** Defina ahora los márgenes reales de cada página en el PDF **/

        body {
            margin-top: 3cm;
            margin-left: 2cm;
            margin-right: 2cm;
            margin-bottom: 2cm;
        }

        /** Definir las reglas del encabezado **/

        header {
            position: fixed;
            top: 2cm;
            left: 2cm;
            right: 2cm;
            height: 3cm;
        }

        /** Definir las reglas del pie de página **/

        footer {
            position: fixed;
            bottom: 2cm;
            left: 2cm;
            right: 2cm;
            height: 2cm;
        }

        main {
            margin-top: 5cm;
            margin-left: 0cm;
            margin-right: 0cm;
            margin-bottom: 2cm;
        }
    </style>
</head>

<body>
    <!---------- Cabecera  Cabecera  Cabecera  Cabecera  Cabecera  Cabecera  Cabecera ---------------------->
    <header>
        <div id="photo" style="text-align: center">
            <img src="../imagenes/72.png" alt="" width="150" style="vertical-align:middle" />
            <h4 style="float:right;">
                Comprobante N#:
            </h4>
        </div>
        <br>
        <div style="text-align: center">
            <font size="1"><strong>CONCEPTO:</strong>
            </font>
        </div>
    </header>
    <!---------- pie de pag  pie de pag  pie de pag  pie de pag  pie de pag  pie de pag  pie de pag   --------------------    -->
    <footer>
        <table width="100%">
            <tbody>

                <tr>
                    <td width="50%"></td>
                    <td width="30%" align="right">
                        <font size="1"><strong>TOTAL COMPROBANTE</strong></font>
                    </td>
                    <td width="10%" align="right">
                        <font size="1">
                    </td>
                    <td width="10%" align="right">
                        <font size="1">
                        </font>
                    </td>
                </tr>
            </tbody>
        </table>
        <table width="100%" ">
                <tbody>
                    <tr>
                        <td align="center" style="border: black 1px solid;">
                            <font size="1">BANCO</font>
                        </td>
                        <td align="center" style="border: black 1px solid;">
                            <font size="1">CUENTA</font>
                        </td>
                        <td align="center" style="border: black 1px solid;">
                            <font size="1">TIPO TRANSACCION</font>
                        </td>
                        <td align="center" style="border: black 1px solid;">
                            <font size="1">SERVICIO</font>
                        </td>
                    </tr>
                    <tr align="center">
                        <td style="border: black 1px solid;">
                            <font size="1">
                               
                            </font>
                        </td>
                        <td style="border: black 1px solid;">
                            <font size="1">
                               
                            </font>
                        </td>
                        <td style="border: black 1px solid;">
                            <font size="1">
                            </font>
                        </td>
                        <td style="border: black 1px solid;">
                            <font size="1">
                            </font>
                        </td>
                    </tr>
                    <tr>
                        <td align="center" style="border: black 1px solid;">
                            <font size="1">COMPROBANTE</font>
                        </td>
                        <td align="center" style="border: black 1px solid;">
                            <font size="1">HECHO POR:</font>
                        </td>
                        <td align="center" style="border: black 1px solid;">
                            <font size="1">CONTABILIDAD</font>
                        </td>
                        <td align="center" style="border: black 1px solid;">
                            <font size="1">FECHA</font>
                        </td>
                    </tr>
                    <tr align="center">
                        <td style="border: black 1px solid;">
                            <font size="1">
                            </font>
                        </td>
                        <td style="border: black 1px solid;" width="25%"></td>
                        <td style="border: black 1px solid;" width="25%"></td>
                        <td style="border: black 1px solid;" width="25%"></td>
                    </tr>
                </tbody>
            </table>
            <script type="text/php">
                if ( isset($pdf) ) {
                    $pdf->page_script('
                        $font = $fontMetrics->get_font("Arial, Helvetica, sans-serif", "normal");
                        $pdf->text(270, 820, "Pág $PAGE_NUM de $PAGE_COUNT", $font, 10);
                    ');
                }
                </script>
    </footer>
    <!---------- cuerpo de   cuerpo de   cuerpo de   cuerpo de   cuerpo de   cuerpo de   cuerpo de    --------------------    -->
    <main>
        <table width="100%">
            <thead>
                <tr>
                    <td>
                        <center>
                            <font size="1"><strong>Codigo</strong></font>
                        </center>
                    </td>
                    <td>
                        <center>
                            <font size="1"><strong>Descripcion</strong></font>
                        </center>
                    </td>
                    <td>
                        <center>
                            <font size="1"><strong>Debito</strong></font>
                        </center>
                    </td>
                    <td>
                        <center>
                            <font size="1"><strong>Credito</strong></font>
                        </center>
                    </td>
                </tr>
            </thead>
            <tbody>
                @php
                    $collection = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, 36, 37, 38, 39, 40, 41, 42, 43, 44, 45, 46, 47, 48, 49, 50, 51, 52, 53, 54, 55, 56, 57, 58, 59, 60, 61];
                @endphp
                   @foreach ($collection as $item)
            <tr>
                <td>
                    <font size="1">
                        vfdvfd
                    </font>
                </td>
                <td>
                    <font size="1">
                        qwwqdeweqeq </font>
                </td>
                <td align="right">
                    <font size="1">
                        vfds
                    </font>
                </td>
                <td align="right">
                    <font size="1">
                        vfdvfdvfd
                    </font>
                </td>
            </tr>
            @endforeach
            </tbody>
        </table>
        </center>
        </main>
</body>

</html>
