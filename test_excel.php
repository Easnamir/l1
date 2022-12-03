<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
$filename ='helloworld.xlsx';
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');

$writer = new Xlsx($spreadsheet);

// $content = file_get_contents($filename);

$excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
$sheet = $excel->getActiveSheet();
$sheet->setTitle('This is a test', true);
$sheet = $spreadsheet->getActiveSheet();
$sheet->setCellValue('A1', 'Hello World !');
ob_end_clean();
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="filename_' . time() . '.xlsx"');
header('Cache-Control: max-age=0');

$xlsxWriter = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
$xlsxWriter = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($excel);
exit($xlsxWriter->save('php://output'));