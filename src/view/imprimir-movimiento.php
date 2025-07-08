<?php
$ruta = explode("/",$_GET['views']);
if (!isset($ruta[1]) || $ruta[1]=="") {
    header("location: ". BASE_URL . "movimientos");
}

    $curl = curl_init(); //inicia la sesión cURL
    curl_setopt_array($curl, array(
        CURLOPT_URL => BASE_URL_SERVER."src/control/Movimiento.php?tipo=buscar_movimiento_id&sesion=".$_SESSION['sesion_id']."&token=".$_SESSION['sesion_token']."&data=".$ruta[1], //url a la que se conecta
        CURLOPT_RETURNTRANSFER => true, //devuelve el resultado como una cadena del tipo curl_exec
        CURLOPT_FOLLOWLOCATION => true, //sigue el encabezado que le envíe el servidor
        CURLOPT_ENCODING => "", // permite decodificar la respuesta y puede ser"identity", "deflate", y "gzip", si está vacío recibe todos los disponibles.
        CURLOPT_MAXREDIRS => 10, // Si usamos CURLOPT_FOLLOWLOCATION le dice el máximo de encabezados a seguir
        CURLOPT_TIMEOUT => 30, // Tiempo máximo para ejecutar
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, // usa la versión declarada
        CURLOPT_CUSTOMREQUEST => "GET", // el tipo de petición, puede ser PUT, POST, GET o Delete dependiendo del servicio
        CURLOPT_HTTPHEADER => array(
            "x-rapidapi-host: ".BASE_URL_SERVER,
            "x-rapidapi-key: XXXX"
        ), //configura las cabeceras enviadas al servicio
    )); //curl_setopt_array configura las opciones para una transferencia cURL

    $response = curl_exec($curl); // respuesta generada
    $err = curl_error($curl); // muestra errores en caso de existir

    curl_close($curl); // termina la sesión 

    if ($err) {
        echo "cURL Error #:" . $err; // mostramos el error
    } else {
        $respuesta = json_decode($response);
       // print_r($respuesta);
      
        
        ?>
        <!--

        <!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Papeleta de Rotación de Bienes</title>
  <style>
    body {
      font-family: Arial, sans-serif;
      margin: 40px;
    }
    h2 {
      text-align: center;
      text-transform: uppercase;
    }
    .info {
      margin-top: 30px;
      margin-bottom: 20px;
      line-height: 1.8;
    }
    .info span {
      font-weight: bold;
    }
    table {
      width: 100%;
      border-collapse: collapse;
      margin-bottom: 40px;
    }
    table, th, td {
      border: 1px solid black;
      text-align: center;
      padding: 8px;
    }
    .firma-container {
      display: flex;
      justify-content: space-between;
      margin-top: 80px;
    }
    .firma {
      text-align: center;
      width: 45%;
    }
    .firma hr {
      width: 80%;
    }
    .fecha {
      text-align: right;
      margin-top: 20px;
    }
  </style>
</head>
<body>

  <h2>Papeleta de Rotación de Bienes</h2>

  <div class="info">
    <div><span>ENTIDAD:</span> DIRECCION REGIONAL DE EDUCACION - AYACUCHO</div>
    <div><span>AREA:</span> OFICINA DE ADMINISTRACIÓN</div>
    <div><span>ORIGEN:</span><?php echo $respuesta->amb_origen->codigo.' - '.$respuesta->amb_origen->detalle;?></div>
    <div><span>DESTINO:</span><?php echo $respuesta->amb_destino->codigo.' - '.$respuesta->amb_destino->detalle;?></div>
    <div><span>MOTIVO (*):</span><?php echo $respuesta->movimiento->descripcion?></div>
  </div>

  <table>
    <thead>
      <tr>
        <th>ITEM</th>
        <th>CÓDIGO PATRIMONIAL</th>
        <th>NOMBRE DEL BIEN</th>
        <th>MARCA</th>
        <th>COLOR</th>
        <th>MODELO</th>
        <th>ESTADO</th>
      </tr>
    </thead>
    <tbody>
  <?php
  $contador = 1;
  foreach ($respuesta->detalle as $bien) {
      echo "<tr>";
      echo "<td>" . str_pad($contador, 2, '0', STR_PAD_LEFT) . "</td>";
      echo "<td>" . htmlspecialchars($bien->cod_patrimonial) . "</td>";
      echo "<td>" . htmlspecialchars($bien->denominacion) . "</td>";
      echo "<td>" . htmlspecialchars($bien->marca) . "</td>";
      echo "<td>" . htmlspecialchars($bien->color) . "</td>";
      echo "<td>" . htmlspecialchars($bien->modelo) . "</td>";
      echo "<td>" . htmlspecialchars($bien->estado_conservacion) . "</td>";
      echo "</tr>";
      $contador++;
  }
     ?>
    </tbody>
  </table>

  <div class="fecha">
  <p>
    Ayacucho,
    <?php
      $fecha = $respuesta->movimiento->fecha_registro; // del objeto JSON/base de datos
      $meses = [
        "01" => "enero", "02" => "febrero", "03" => "marzo",
        "04" => "abril", "05" => "mayo", "06" => "junio",
        "07" => "julio", "08" => "agosto", "09" => "septiembre",
        "10" => "octubre", "11" => "noviembre", "12" => "diciembre"
      ];

      $dia = date("d", strtotime($fecha));
      $mes = $meses[date("m", strtotime($fecha))];
      $anio = date("Y", strtotime($fecha));

      echo "$dia de $mes del $anio";
    ?>
  </p>
</div>

  <div class="firma-container">
    <div class="firma">
      <hr>
      ENTREGUÉ CONFORME
    </div>
    <div class="firma">
      <hr>
      RECIBÍ CONFORME
    </div>
  </div>

</body>
</html>
    --<
 
<?php
require_once('./vendor/tecnickom/tcpdf/tcpdf.php');

$pdf = new TCPDF();
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Pamsito');
$pdf->SetTitle('TCPDF Example 006');

$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);

$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    }