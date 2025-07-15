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
      $contenido_pdf = '';
     $contenido_pdf = '
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
      margin-bottom: 20px;
    }

    .info p {
      margin: 5px 0;
    }

    .info span.label {
      font-weight: bold;
      display: inline-block;
      width: 100px;
    }

    table {
      width: 100%;
      border-collapse: collapse;
      margin-top: 20px;
    }

    table, th, td {
      border: 1px solid black;
    }

    th, td {
      text-align: center;
      padding: 5px;
    }

    .firma-container {
      margin-top: 80px;
      display: flex;
      justify-content: space-between;
      padding: 0 40px;
    }

    .firma {
      text-align: center;
    }

    .fecha {
      text-align: right;
      margin-top: 30px;
    }
       table {
    border-collapse: collapse;
    width: 100%;
    font-size: 9pt;
  }
  th, td {
    border: 1px solid #000;
    padding: 4px;
    text-align: center;
  }
  thead th {
    background-color: #f2f2f2;
    font-weight: bold;

  </style>
</head>
<body>

  <h2>PAPELETA DE ROTACIÓN DE BIENES</h2>

  <div class="info">
    <p><span class="label">ENTIDAD:</span> DIRECCIÓN REGIONAL DE EDUCACIÓN - AYACUCHO</p>
    <p><span class="label">ÁREA:</span> OFICINA DE ADMINISTRACIÓN</p>
    <p><span class="label">ORIGEN:</span><'.$respuesta->amb_origen->codigo .' - '.$respuesta->amb_origen->detalle .'></p>
    <p><span class="label">DESTINO:</span><'.$respuesta->amb_destino->codigo .' - '. $respuesta->amb_destino->detalle.'?></p>
    <p><span class="label">MOTIVO (*):</span>'.$respuesta->movimiento->descripcion.'</p>
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
      ';
 
    $contador = 1;
    foreach ($respuesta->detalle as $bien) {
       $contenido_pdf .= "<tr>";
        $contenido_pdf .=  "<td>" . $contador . "</td>";
        $contenido_pdf .=  "<td>" . $bien->cod_patrimonial . "</td>";
        $contenido_pdf .=  "<td>" . $bien->denominacion . "</td>";
        $contenido_pdf .=  "<td>" . $bien->marca . "</td>";
        $contenido_pdf .=  "<td>" . $bien->color . "</td>";
        $contenido_pdf .=  "<td>" . $bien->modelo . "</td>";
        $contenido_pdf .=  "<td>" . $bien->estado_conservacion . "</td>";
        $contenido_pdf .=  "</tr>";
        $contador +=1;
    }
      
      if (isset($respuesta->movimiento->fecha_registro) && $respuesta->movimiento->fecha_registro != '') {
                setlocale(LC_TIME, 'es_ES.UTF-8', 'spanish');
                $fecha = strtotime($respuesta->movimiento->fecha_registro);
                // Si no funciona setlocale en el servidor, usar un array de meses en español
                $meses = [
                    1 => 'enero',
                    2 => 'febrero',
                    3 => 'marzo',
                    4 => 'abril',
                    5 => 'mayo',
                    6 => 'junio',
                    7 => 'julio',
                    8 => 'agosto',
                    9 => 'septiembre',
                    10 => 'octubre',
                    11 => 'noviembre',
                    12 => 'diciembre'
                ];
                $dia = date('d', $fecha);
                $mes = $meses[(int)date('m', $fecha)];
                $anio = date('Y', $fecha);
                $contenido_pdf.= "Ayacucho, $dia de $mes del $anio";
            }

$contenido_pdf .= '
    </tbody>
  </table>



  <div class="firma-container">
    <div class="firma">
      <p>------------------------------</p>
      <p>ENTREGUÉ CONFORME</p>
    </div>
    <div class="firma">
      <p>------------------------------</p>
      <p>RECIBÍ CONFORME</p>
    </div>
  </div>

</body>
</html>
';

  
   
require_once('./vendor/tecnickcom/tcpdf/tcpdf.php');

class MYPDF extends TCPDF {
    public function Header() {
       
        $logo_left  = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcSABjBsifx4kJK7C6ewR1dqJ8DGpEoKk6McLQ&s';
        $logo_right = 'https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcT_BSnUuKJh7yQ05Oav2g2R4W3L0o99TfFS-A&s';

       $this->Image($logo_left, 15, 10, 38, 30);  // ancho y alto de 25mm
      $this->Image($logo_right, 170, 10, 25, 25);


        $this->SetXY(55, 12); // desplazado al centro visual real
        $this->SetFont('helvetica', 'B', 11);
        $this->SetTextColor(0, 70, 140); // Azul fuerte
        $this->Cell(100, 6, 'GOBIERNO REGIONAL DE AYACUCHO', 0, 1, 'C');

       
        $this->SetX(55);
        $this->SetFont('helvetica', 'I', 10);
        $this->SetTextColor(85, 85, 85); // Gris oscuro
        $this->Cell(100, 6, 'DIRECCIÓN REGIONAL DE EDUCACIÓN DE AYACUCHO', 0, 1, 'C');


        $this->SetX(55);
        $this->SetFont('helvetica', 'I', 10);
        $this->SetTextColor(85, 85, 85); // Gris oscuro
        $this->Cell(100, 6, 'DIRECCION DE ADMINISTRACION', 0, 1, 'C');


        $this->SetDrawColor(52, 152, 219);
        $this->SetLineWidth(0.8);
        $this->Line(15, 44, 195, 44);

        $this->Ln(5); // Espacio adicional
    }

  public function Footer() {
    // Posicionar a 15 mm del final de la página
    $this->SetY(-15);

    // Línea superior del footer
    $this->SetDrawColor(189, 195, 199);
    $this->SetLineWidth(0.5);
    $this->Line(15, $this->GetY() - 5, 195, $this->GetY() - 5);

    // Estilo del texto
    $this->SetFont('helvetica', 'I', 8);
    $this->SetTextColor(100, 100, 100);

    // Texto de número de página centrado
    $this->Cell(0, 10, 'Página ' . $this->getAliasNumPage() . ' de ' . $this->getAliasNbPages(), 0, 0, 'C');

    // Reset de estilo
    $this->SetTextColor(0, 0, 0);
    $this->SetLineWidth(0.2);

    }
}


$pdf = new MYPDF();
// set document information
$pdf->SetCreator(PDF_CREATOR);
$pdf->SetAuthor('Marycielo');
$pdf->SetTitle('Reporte de movimiento');

$pdf->SetMargins(PDF_MARGIN_LEFT, 45, PDF_MARGIN_RIGHT); 
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);                
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);  


$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

$pdf->SetFont('helvetica', '', 8);


$pdf->AddPage();


$pdf->writeHTML($contenido_pdf, true, false, true, false, '');

$pdf->Output('reporte_movimiento_' . date('Ymd_His') . '.pdf', 'I');
    }