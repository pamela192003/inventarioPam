<?php

require './vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

$spreadsheet = new Spreadsheet();
$spreadsheet->getProperties()->SetCreator("yo")->setLastModifiedBy("yo")->setTitle("yo")->setDescription("yo");
$activeWorksheet = $spreadsheet->getActiveSheet();
$activeWorksheet->setTitle("hoja 1");
$activeWorksheet->setCellValue('A1', 'Hello World !');
//$activeWorksheet->setCellValue('A2', 'DNI');
//$activeWorksheet->setCellValue('B2', '76452911');
for ($i = 1; $i <= 10; $i++) {
    $activeWorksheet->setCellValue('A' . $i, $i);
}

for ($i = 0; $i < 30; $i++) {
    $columnLetter = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::stringFromColumnIndex($i + 1); // Convierte 1 en 'A', 2 en 'B', etc.
    $activeWorksheet->setCellValue($columnLetter . '1', $i + 1);
}

// Encabezado
$activeWorksheet->setCellValue('A1', 'Tabla del 1');

// Generar tabla del 1 (en columna vertical desde A2)
for ($i = 1; $i <= 10; $i++) {
    $resultado = 1 * $i;
    $activeWorksheet->setCellValue('A' . ($i + 1), "1 x $i = $resultado");
}

$writer = new Xlsx($spreadsheet);
$writer->save('hello world.xlsx');