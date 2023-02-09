<?php include_once "../conf/Configuracion.php"; ob_start(); ?>
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
        <?php 
               $peticion = $db->query("SELECT * FROM diario WHERE comprobante='".$_GET['comprobamte']."'"); 
               $columna = $peticion->fetch_assoc(); 
         $item = array('comprobante' => $columna['comprobante'], 'fechafac'=>$columna['fechafac'], 'concepto'=>$columna['      concepto'], 'desccripcion'=>$columna['descripcion'] ); 
               echo $item['comprobante'].' DE FECHA: '.date('d-m-Y',strtotime($item['fechafac']));?> </h4>
    </div>
    <br>
    <div style="text-align: center">
      <font size="1"><strong>CONCEPTO:</strong>
        <?php echo $item['concepto']?>
      </font>
    </div>
  </header>
  <!---------- pie de pag  pie de pag  pie de pag  pie de pag  pie de pag  pie de pag  pie de pag   --------------------    -->
  <footer>
    <center>
      <table width="100%">
        <tbody>
          <?php $sentencia = $base_de_datos->query("SELECT * FROM diario WHERE comprobante='".$_GET['comprobamte']."'  "); 
                $productos = $sentencia->fetchAll(PDO::FETCH_OBJ); ?>
          <?php $saldo = 0; $sumhaber= 0; $sumdebe = 0; 
                foreach($productos as $producto){
                  $_SESSION['departamento'] = $producto->departamento;
                  $_SESSION['comprobante']= $producto->comprobante;
                  $sumhaber = $producto->haber + $sumhaber;
                  $sumdebe = $producto->debe + $sumdebe;
                  $_SESSION['sumhaber']=$sumhaber;
                  $_SESSION['sumadebe']= $sumdebe; }
                  ?>
          <tr>
            <td width="50%"></td>
            <td width="30%" align="right">
              <font size="1"><strong>TOTAL COMPROBANTE</strong></font>
            </td>
            <td width="10%" align="right">
              <font size="1">
                <?php echo   number_format($_SESSION['sumadebe'],2,",",".")?> </font>
            </td>
            <td width="10%" align="right">
              <font size="1">
                <?php echo  number_format($_SESSION['sumhaber'],2,",",".") ?> </font>
            </td>
          </tr>
        </tbody>
      </table>
      <table width="100%" style="@media print{ border: 1px solid #000;  border-radius: 10px;  }  ">
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
                <?php  $peticion2 = $db->query("SELECT * FROM   diario     WHERE comprobante='".$_GET['comprobamte']."' AND definicion='111' and mayor='201'");
                    $columna2 = $peticion2->fetch_assoc(); 
              $item2 = array('banco' => $columna2['banco'], 'n_cuenta_ban'=>$columna2['n_cuenta_ban'], 'tipo_trasn_p'=>  $    columna2['tipo_trasn_p'] ); 
      
                    echo $item2['banco']?>
              </font>
            </td>
            <td style="border: black 1px solid;">
              <font size="1">
                <?php echo $item2['n_cuenta_ban'] ?>
              </font>
            </td>
            <td style="border: black 1px solid;">
              <font size="1">
                <?php echo $item2['tipo_trasn_p'] ?>
              </font>
            </td>
            <td style="border: black 1px solid;">
              <font size="1">
                <?php if($_SESSION['departamento']=='6'){echo'      Servicio de Gas';} ?>
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
                <?php echo $_SESSION['comprobante']?>
              </font>
            </td>
            <td style="border: black 1px solid;" width="25%"></td>
            <td style="border: black 1px solid;" width="25%"></td>
            <td style="border: black 1px solid;" width="25%"></td>
          </tr>
        </tbody>
      </table>
    </center>
  </footer>
  <!---------- cuerpo de   cuerpo de   cuerpo de   cuerpo de   cuerpo de   cuerpo de   cuerpo de    --------------------    -->
  <main>
    <table width="100%">
      <?php $sentencia_diario = $base_de_datos->query("SELECT * FROM diario WHERE comprobante='".$_GET['comprobamte  ']."    '"); 
              $producto_diarios = $sentencia_diario->fetchAll(PDO::FETCH_OBJ); ?>
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
        <?php foreach($producto_diarios as $producto_diario){ ?>
        <tr>
          <td>
            <font size="1">
              <?php echo $producto_diario->codigo.' - '. $producto_diario->cuenta ;?>
            </font>
          </td>
          <td>
            <font size="1">
              <?php echo $producto_diario->descripcion ?>
            </font>
          </td>
          <td align="right">
            <font size="1">
              <?php echo number_format($producto_diario->debe,2,",",".") ?>
            </font>
          </td>
          <td align="right">
            <font size="1">
              <?php echo number_format($producto_diario->haber,2,",",".") ?>
            </font>
          </td>
        </tr>
        <?php } ?>
      </tbody>
    </table>
    </center>
  </main>
</body>

</html>
<?php 
      $html = ob_get_clean();
      //echo $html;
      
      require_once "../dompdf/autoload.inc.php";
      // reference the Dompdf namespace
      use Dompdf\Dompdf;
      
      // instantiate and use the dompdf class
      $dompdf = new Dompdf();
      
      
      $options = $dompdf->getOptions();
      $options->set(array('isRemoteEnable'=> true));
      $dompdf->setOptions($options);
      
      $dompdf->loadHtml($html);
      
      $dompdf->setPaper('letter');
      
      $dompdf->render();
      
      $dompdf->stream("reporte.pdf", array("Attachment" => false));?>
?>