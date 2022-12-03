<?php
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;

$reader = IOFactory::createReader('Xlsx'); // :: Scope Resolution , is a token that allows access to static, constant, and overridden properties or methods of a class.
     $spreadsheet = $reader->load('ORDER_REGISTER.xlsx');
     $sheet = $spreadsheet->getActiveSheet();
     $highestRow = $spreadsheet->getActiveSheet()->getHighestRow();
     $highestColumn = $spreadsheet->getActiveSheet()->getHighestColumn();

     $title = $sheet->getCell('A1')->getValue();
    //  var_dump($highestRow);
     for($k=10;$k<=$highestRow;$k++){
      var_dump($sheet->getCell('D'.$k)->getValue());
      echo "<br />";
     }
    //  var_dump($spreadsheet->sheet);
// ini_set('auto_detect_line_endings',TRUE);
// $file = fopen('ORDER_REGISTER.xlsx','r');
// var_dump($file);
// while ( ($data = fgetcsv($file) ) !== FALSE ) {
//   //process
//   var_dump($data);
//   // echo '<br>';
//   }


?>