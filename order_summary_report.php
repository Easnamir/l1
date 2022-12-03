<?php
//call the autoload
session_start();
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
//load phpspreadsheet class using namespaces
$alphabet =   array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z','AA','AB','AC','AD','AE','AF','AG','AH','AI','AJ','AK','AL','AM','AN','AO','AP','AQ','AR','AS','AT','AU','AV','AW','AX','AY','AZ','BA','BB','BC','BD','BE','BF','BG','BH','BI','BJ','BK','BL','BM','BN','BO','BP','BQ','BR','BS','BT','BU','BV','BW','BX','BY','BZ','CA', 'CB', 'CC', 'CD', 'CE', 'CF', 'CG', 'CH', 'CI', 'CJ', 'CK', 'CL', 'CM', 'CN', 'CO', 'CP', 'CQ', 'CR', 'CS', 'CT', 'CU', 'CV', 'CW', 'CX', 'CY', 'CZ', 'DA', 'DB', 'DC', 'DD', 'DE', 'DF', 'DG', 'DH', 'DI', 'DJ', 'DK', 'DL', 'DM', 'DN', 'DO', 'DP', 'DQ', 'DR', 'DS', 'DT', 'DU', 'DV', 'DW', 'DX', 'DY', 'DZ');
$company_name= $_SESSION['COMPANY_NAME'];
include 'includes/autoload.inc.php';
include 'includes/connect.php';
$data=[];

$startdate = $_SESSION['startdate'];
 $enddate = $_SESSION['enddate'];
 $Department =$_SESSION['Department'];

if($Department=='All'){
 $sql = "Select distinct Brand_Name,SIZE_VALUE from POPS_DISPATCH_ITEMS WHERE 
	  cast(CREATED_DATE as date) between '$startdate' and '$enddate' and status<5
          order by Brand_Name,size_VALUE desc";
}
else{
     $sql = "Select distinct Brand_Name,SIZE_VALUE from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b 
on a.VEND_CODE=b.VEND_CODE WHERE 
	 b.DEPARTMENT in $Department and cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' and status<5
  order by Brand_Name,size_VALUE desc ";
}
	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		// $data[] = $row;



$brands["{$row['Brand_Name']}"][] = $row['SIZE_VALUE'];
	$size_array[]=$row['SIZE_VALUE'];
	$size_arr++;
}
// print_r($brands);
// exit;
if($Department=='All'){
	
   $sql="select distinct isnull(DEPARTMENT_NAME,b.DEPARTMENT)DEPARTMENT_NAME,B.DEPARTMENT  from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b 
on a.VEND_CODE=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT 
where cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' and status<5 ";
}
else{
  $sql="select distinct isnull(DEPARTMENT_NAME,b.DEPARTMENT) DEPARTMENT_NAME,B.DEPARTMENT from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b 
on a.VEND_CODE=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
where  b.DEPARTMENT in $Department and cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' and status<5 ";

}	
// exit;
$stmt= sqlsrv_query($conn,$sql);
// $PO_NUM=[];
while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
	$data[]=$row;
	// $PO_NUM["{$row['PO_NO']}"]  = $row['PO_NO'];
	// $Department["{$row['DEPARTMENT_NAME']}"]  = $row['DEPARTMENT_NAME'];
}
// var_dump($data);
// exit;
$tableHead = [
	'font'=>[
		'color'=>[
			'rgb'=>'000000'
		],
		'bold'=>true,
		'size'=>10
	],
	'fill'=>[
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => 'FFC0CB'
		]
	],
	'borders' => [
		'outline' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => ['rgb' => '000000'],
		],
],
];

$tableSubHead = [
	'font'=>[
		'color'=>[
			'rgb'=>'000000'
		],
		'bold'=>true,
		'size'=>10
	],
	'fill'=>[
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => 'f5ec71'
		]
	],
	'borders' => [
		'outline' => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => ['rgb' => '000000'],
		],
],
];
//even row
$evenRow = [
	'fill'=>[
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => 'e1e1e1'
		]
		],
		'borders' => [
			'outline' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
			],
	],
];
//odd row
$oddRow = [
	'fill'=>[
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => 'ffffff'
		]
		],
		'borders' => [
			'outline' => [
					'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
					'color' => ['rgb' => '000000'],
			],
	],
];

//styling arrays end

//make a new spreadsheet object
$spreadsheet = new Spreadsheet();
//get current active sheet (first sheet)
$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getActiveSheet()->setTitle("ORDER REGISTER");
//set default font
$spreadsheet->getDefaultStyle()
	->getFont()
	->setName('Arial')
	->setSize(9);

//heading
$spreadsheet->getActiveSheet()
	->setCellValue('A1',"$company_name")
	->setCellValue('A2',"ORDER SUMMARY")
	 ->setCellValue('A3',"Department: $Department")
	->setCellValue('A4'," Report Date : $startdate to $enddate")
	->setCellValue('A6',"");
	
//merge heading
$col1=3;
// var_dump($brands);
// exit;
foreach($brands as $key=> $value){

	// var_dump($alphabet[$col1]);
	// echo $key;
	$spreadsheet->getActiveSheet()
	->setCellValue("{$alphabet[$col1]}".'6',$key);
	$spreadsheet->getActiveSheet()->getStyle("{$alphabet[$col1]}".'6')->getAlignment()->setWrapText(true);
	$spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(126);

	// $spreadsheet->getActiveSheet()->getColumnDimension("{$alphabet[$col1]}".'6')->setAutoSize(TRUE);

	$spreadsheet->getActiveSheet()->mergeCells("{$alphabet[$col1]}".'6'.":"."{$alphabet[$col1+ count($value)-1]}".'6');
	$col1= $col1+ count($value);
}
// $spreadsheet->getActiveSheet()->getStyle('C3:'."{$alphabet[$col1]}".'3')
//     ->getAlignment()->setWrapText(true); 
// var_dump($data);
// 	exit;
// echo $alphabet[$col1+count($value)];
// exit;


$spreadsheet->getActiveSheet()->mergeCells("A3:E3");
$spreadsheet->getActiveSheet()->mergeCells("A4:E4");
$spreadsheet->getActiveSheet()->mergeCells("A6:B6");



// // set font style
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
// // set cell alignment
$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
// //setting column width
// $spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
// $spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
// $spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
// $spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
// $spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);
// $spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);
// $spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
// $spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
// $spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(30);
// $spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(12);
// // $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(12);
// //header text
// $row=7;
$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(1,7,"Sno")
	
	->setCellValueByColumnAndRow(2,7,"Department");	
	$col=3;

	foreach($brands as $size){
		foreach($size as $value){
$spreadsheet->getActiveSheet()
	->setCellValueByColumnAndRow($col++,7,$value);
	// $col++;
		}
	}
$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow($col++,7,"Total Quantity")
->setCellValueByColumnAndRow($col++,7,"Bill Value")
->setCellValueByColumnAndRow($col,7,"Excise Revenue");
$spreadsheet->getActiveSheet()->getStyle('A6:'."{$alphabet[$col-1]}".'6')->applyFromArray($tableHead);
$spreadsheet->getActiveSheet()->getStyle('A7:'."{$alphabet[$col-1]}".'7')->applyFromArray($tableHead);
$row=8;
$sno=1;

// $spreadsheet->getActiveSheet()->getRowDimension('6')->setRowHeight(-1);

$gt_total_qty=0;
$gt_total_wsp=0;
$gt_total_excise=0;
// print_r($data);
// exit;

foreach($data as $key=> $value){
	
	$spreadsheet->getActiveSheet()
		->setCellValue('A'.$row , $sno++)
		
		
		->setCellValue('B'.$row , $value['DEPARTMENT_NAME']);

	$i=3;
	$total_qty=0;
	$total_wsp=0;
	$total_excise=0;
	$total_mrp=0;
	// print_r($brands);
	// exit;
	$dept=$value['DEPARTMENT'];
	foreach($brands as $key=> $variable){
		foreach($variable as $size){
		   $sqltp = "select isnull(DEPARTMENT_NAME,aa.DEPARTMENT) as DEPARTMENT_NAME,BRAND_NAME,sum(case_".$size.") case_".$size.",sum(wsp_".$size.") as wsp_".$size.",sum(MRP_".$size.") as MRP_".$size.",sum(EXCISE_".$size.")EXCISE_".$size." from(
				select '$dept' as DEPARTMENT,'$key' as BRAND_NAME,0 as case_".$size.", 0 as wsp_".$size.",0 as MRP_".$size.", 0 AS EXCISE_".$size." 
				union SELECT b.DEPARTMENT as DEPARTMENT, BRAND_NAME, SUM(CASE WHEN (SIZE_VALUE = '$size') THEN CASE_QUANTITY ELSE 0 END) AS case_".$size.", 
				SUM(CASE WHEN (SIZE_VALUE = '$size' ) THEN (CUSTOM_DUTY+WSP)*BOTTLE_QUANTITY ELSE 0 END) AS wsp_".$size.", SUM(CASE WHEN (SIZE_VALUE = '$size' ) THEN MRP*BOTTLE_QUANTITY ELSE 0 END) AS MRP_".$size.", SUM(CASE WHEN (SIZE_VALUE = '$size' ) 
				THEN EXCISE_DUTY*BOTTLE_QUANTITY ELSE 0 END) AS EXCISE_".$size." FROM POPS_DISPATCH_ITEMS a  join POPS_VEND_DETAILS b on a.VEND_CODE=b.VEND_CODE
				
				WHERE  BRAND_NAME='$key' and SIZE_VALUE='$size' and b.DEPARTMENT = '$dept' and cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' and status<5 group by BRAND_NAME,b.DEPARTMENT) as aa
				left join POPS_DEP_DETAILS c on aa.DEPARTMENT=c.DEPARTMENT 
				group by BRAND_NAME,aa.DEPARTMENT,c.DEPARTMENT_NAME";
				// exit;
	// echo "<br>";
	$stmttp = sqlsrv_query($conn,$sqltp);
		$data_brand_new=[];
	while($rowtp = sqlsrv_fetch_array($stmttp,SQLSRV_FETCH_ASSOC)){
		// print_r($rowtp);
		// echo "<br>";
		$data_brand_new[]=$rowtp;
		  $rowtp["case_$size"] = $rowtp["case_$size"];
		$total_qty = $total_qty+($rowtp["case_$size"]);
		$total_wsp += $rowtp["wsp_$size"];
		$total_excise += $rowtp["EXCISE_$size"];
		$total_mrp += $rowtp["MRP_$size"];
		// echo "<br>";
		
		$spreadsheet->getActiveSheet()
		->setCellValueByColumnAndRow($i++,$row,($rowtp["case_$size"]!=null?$rowtp["case_$size"]:'0'));
	}
}

}
// print_r($data_brand_new);
// exit;
$gt_total_qty += $total_qty;
$gt_total_wsp += $total_wsp;
$gt_total_excise += $total_excise;


// exit;
	$spreadsheet->getActiveSheet()
		->setCellValueByColumnAndRow($i++,$row,$total_qty);
		$spreadsheet->getActiveSheet()
		->setCellValueByColumnAndRow($i++,$row,$total_wsp);
		$spreadsheet->getActiveSheet()
		->setCellValueByColumnAndRow($i++,$row,$total_excise);
$row++;
// exit;
}


$spreadsheet->getActiveSheet()
	->setCellValue("A$row","Total");
$spreadsheet->getActiveSheet()
	->setCellValueByColumnAndRow(3+$size_arr++,$row,$gt_total_qty);
$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(3+$size_arr++,$row,$gt_total_wsp);
$spreadsheet->getActiveSheet()
	->setCellValueByColumnAndRow(3+$size_arr++,$row,$gt_total_excise);
$spreadsheet->getActiveSheet()->mergeCells("A$row:H$row");
$spreadsheet->getActiveSheet()->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
$spreadsheet->getActiveSheet()->getStyle("A$row:"."{$alphabet[$col-1]}"."$row")->applyFromArray($tableHead);
$spreadsheet->getActiveSheet()->mergeCells("A1:"."{$alphabet[$col-1]}1");
$spreadsheet->getActiveSheet()->mergeCells("A2:"."{$alphabet[$col-1]}2");
// $spreadsheet->getActiveSheet()->mergeCells("A3:"."{$alphabet[$col-1]}3");
// $spreadsheet->getActiveSheet()->mergeCells("A4:"."{$alphabet[$col-1]}4");
// $spreadsheet->getActiveSheet()->mergeCells("A5:"."{$alphabet[$col-1]}5");



// $spreadsheet->getActiveSheet()->mergeCells("A2:O2");



// exit;

//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="ORDER REGISTER.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//save into php output
$writer->save('php://output');
