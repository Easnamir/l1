<?php
//call the autoload
session_start();
require 'vendor/autoload.php';
//load phpspreadsheet class using namespaces
$alphabet =   array('','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
$company_name= $_SESSION['COMPANY_NAME'];
include 'includes/autoload.inc.php';
include 'includes/connect.php';
$data=[];
$invoice = $_GET['invoice'];

$sqlb= "Select distinct Brand_Name,SIZE_VALUE from POPS_DISPATCH_ITEMS WHERE INVOICE_NO='$invoice' order by Brand_Name,size_VALUE desc";
$stmtb = sqlsrv_query($conn, $sqlb);
$brands = [];
$size_arr=0;
$size_array = [];
while($rowb = sqlsrv_fetch_array($stmtb,SQLSRV_FETCH_ASSOC)){
	$brands["{$rowb['Brand_Name']}"][] = $rowb['SIZE_VALUE'];
	$size_array[]=$rowb['SIZE_VALUE'];
	$size_arr++;
}
// print_r($brands);
// exit;
$sql="select distinct CHALLAN_NO,DEPARTMENT_NAME,SUPPLY_DATE,PO_NO,PO_DATE,TP_DATE,TP_NO,a.VEND_CODE,VEND_NAME from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b 
on a.VEND_CODE=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
where INVOICE_NO='$invoice'";	
$stmt= sqlsrv_query($conn,$sql);
$PO_NUM=[];
while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
	$data[]=$row;
	$PO_NUM["{$row['PO_NO']}"]  = $row['PO_NO'];
	$Department["{$row['DEPARTMENT_NAME']}"]  = $row['DEPARTMENT_NAME'];
}
$po_number = implode(',',$PO_NUM);
$dept= implode(',',$Department);
use PhpOffice\PhpSpreadsheet\Spreadsheet;
//call iofactory instead of xlsx writer
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
// var_dump($data);
// exit;
//styling arrays
//table head style
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
];
//even row
$evenRow = [
	'fill'=>[
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => 'e1e1e1'
		]
	]
];
//odd row
$oddRow = [
	'fill'=>[
		'fillType' => Fill::FILL_SOLID,
		'startColor' => [
			'rgb' => 'ffffff'
		]
	]
];

//styling arrays end

//make a new spreadsheet object
$spreadsheet = new Spreadsheet();
//get current active sheet (first sheet)
$sheet = $spreadsheet->getActiveSheet();
$spreadsheet->getActiveSheet()->setTitle("Submission");
//set default font
$spreadsheet->getDefaultStyle()
	->getFont()
	->setName('Arial')
	->setSize(9);

//heading
$spreadsheet->getActiveSheet()
	->setCellValue('A1',"$company_name")
	->setCellValue('A2',"Weekly Submission")
	->setCellValue('A3',"Department: $dept")
	->setCellValue('A4',"Order No: $po_number")
	->setCellValue('A6',"");
	
//merge heading
$col1=10;
foreach($brands as $key=> $value){

	// var_dump($value);
	// echo $key;
	$spreadsheet->getActiveSheet()
	->setCellValue("{$alphabet[$col1]}".'6',$key);
	$spreadsheet->getActiveSheet()->mergeCells("{$alphabet[$col1]}".'6'.":"."{$alphabet[$col1+ count($value)-1]}".'6');
	$col1= $col1+ count($value);
}
// echo $alphabet[$col1+count($value)];
// exit;
$spreadsheet->getActiveSheet()->mergeCells("A1:O1");
$spreadsheet->getActiveSheet()->mergeCells("A2:O2");
$spreadsheet->getActiveSheet()->mergeCells("A3:E3");
$spreadsheet->getActiveSheet()->mergeCells("A4:E4");
$spreadsheet->getActiveSheet()->mergeCells("A6:I6");



// set font style
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(14);
// set cell alignment
$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
//setting column width
$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(12);
$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('J')->setWidth(12);
$spreadsheet->getActiveSheet()->getColumnDimension('K')->setWidth(10);
$spreadsheet->getActiveSheet()->getColumnDimension('L')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('M')->setWidth(20);
$spreadsheet->getActiveSheet()->getColumnDimension('N')->setWidth(30);
$spreadsheet->getActiveSheet()->getColumnDimension('O')->setWidth(12);
// $spreadsheet->getActiveSheet()->getColumnDimension('P')->setWidth(12);
//header text
$row=7;
$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(1,7,"Sno")
	->setCellValueByColumnAndRow(2,7,"Challan Num")
	->setCellValueByColumnAndRow(3,7,"Challan Date")
	->setCellValueByColumnAndRow(4,7,"Permit Num")
	->setCellValueByColumnAndRow(5,7,"Permit Date")
	->setCellValueByColumnAndRow(6,7,"Order Num")
	->setCellValueByColumnAndRow(7,7,"Order date")
	->setCellValueByColumnAndRow(8,7,"Party Code")
	->setCellValueByColumnAndRow(9,7,"Party Name");	
	$col=10;
	foreach($brands as $size){
		foreach($size as $value){
$spreadsheet->getActiveSheet()
	->setCellValueByColumnAndRow($col,$row,$value);
	$col++;
		}
	}
$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow($col++,7,"Total Quantity")
->setCellValueByColumnAndRow($col++,7,"Bill Value")
->setCellValueByColumnAndRow($col++,7,"Excise Revenue");
$spreadsheet->getActiveSheet()->getStyle('A6:'."{$alphabet[$col-1]}".'6')->applyFromArray($tableHead);
$spreadsheet->getActiveSheet()->getStyle('A7:'."{$alphabet[$col-1]}".'7')->applyFromArray($tableHead);
$row=8;
$i=10;
$sno=1;
$total_size = $i+$size_arr;

$gt_total_qty=0;
$gt_total_wsp=0;
$gt_total_excise=0;
foreach($data as $key=> $value){
	// var_dump($value);
	// echo "<br>";
	$tp = $value['TP_NO'];
	// $value['TP_DATE']->format('d-m-Y');
	$spreadsheet->getActiveSheet()
		->setCellValue('A'.$row , $sno++)
		->setCellValue('B'.$row , $value['CHALLAN_NO'])
		->setCellValue('C'.$row , $value['SUPPLY_DATE']->format('d-m-Y'))
		->setCellValue('D'.$row , $value['TP_NO'])
		->setCellValue('E'.$row , $value['TP_DATE']->format('d-m-Y'))
		->setCellValue('F'.$row , $value['PO_NO'])
		->setCellValue('G'.$row , $value['PO_DATE']->format('d-m-Y'))
		->setCellValue('H'.$row , $value['VEND_CODE'])
		->setCellValue('I'.$row , $value['VEND_NAME']);

	$i=10;
	$total_qty=0;
	$total_wsp=0;
	$total_excise=0;
	$total_mrp=0;
	
	// var_dump($brands);
	// echo "<br>";
	// exit;
	// $size_array[]=$rowb['SIZE_VALUE'];

	foreach($brands as $key=> $variable){
		// var_dump($variable);
		// echo "<br>";
		foreach($variable as $size){
			// var_dump($size);
			// echo "<br>";
		 $sqltp = "
  select BRAND_NAME,sum(case_".$size.") case_".$size.",sum(wsp_".$size.") as wsp_".$size.",sum(MRP_".$size.") as MRP_".$size.",sum(EXCISE_".$size.")EXCISE_".$size." from (
select '$key'  as BRAND_NAME,0 as case_".$size.", 0 as wsp_".$size.",0 as MRP_".$size.", 0 AS EXCISE_".$size."
union 

SELECT
	BRAND_NAME,
	SUM(CASE WHEN (SIZE_VALUE = $size) THEN CASE_QUANTITY ELSE 0 END) AS case_".$size.",
		SUM(CASE WHEN (SIZE_VALUE = $size ) THEN (CUSTOM_DUTY+WSP)*BOTTLE_QUANTITY ELSE 0 END) AS wsp_".$size.",
		SUM(CASE WHEN (SIZE_VALUE = $size ) THEN MRP*BOTTLE_QUANTITY ELSE 0 END) AS MRP_".$size.",
	
  
			SUM(CASE WHEN (SIZE_VALUE = $size ) THEN EXCISE_DUTY*BOTTLE_QUANTITY ELSE 0 END) AS EXCISE_".$size."
	
	FROM
	POPS_DISPATCH_ITEMS WHERE TP_NO='$tp' and BRAND_NAME='$key' and SIZE_VALUE='$size' group by BRAND_NAME) as a
group by BRAND_NAME
	";
	// echo "<br>";
	$stmttp = sqlsrv_query($conn,$sqltp);

	while($rowtp = sqlsrv_fetch_array($stmttp,SQLSRV_FETCH_ASSOC)){
		// print_r($rowtp);
		// echo "<br>";
		 $rowtp["case_$size"] = $rowtp["case_$size"]?$rowtp["case_$size"]:0;
		$total_qty = $total_qty+($rowtp["case_$size"]?$rowtp["case_$size"]:0);
		$total_wsp += $rowtp["wsp_$size"];
		$total_excise += $rowtp["EXCISE_$size"];
		$total_mrp += $rowtp["MRP_$size"];
		// echo "<br>";
		
		$spreadsheet->getActiveSheet()
		->setCellValueByColumnAndRow($i++,$row,($rowtp["case_$size"]!=null?$rowtp["case_$size"]:'0'));
	}
}

		// $spreadsheet->getActiveSheet()
		// ->setCellValueByColumnAndRow($i++,$row,$total_mrp);
	
}
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
// exit;
// $size_arr
$spreadsheet->getActiveSheet()
	->setCellValue("A$row","Total");
$spreadsheet->getActiveSheet()
	->setCellValueByColumnAndRow(10+$size_arr++,$row,$gt_total_qty);
$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(10+$size_arr++,$row,$gt_total_wsp);
$spreadsheet->getActiveSheet()
	->setCellValueByColumnAndRow(10+$size_arr++,$row,$gt_total_excise);
$spreadsheet->getActiveSheet()->mergeCells("A$row:I$row");
$spreadsheet->getActiveSheet()->getStyle("A$row")->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);
$spreadsheet->getActiveSheet()->getStyle("A$row:O$row")->applyFromArray($tableHead);






// print_r($data);
$spreadsheet->createSheet();

// Add some data
$spreadsheet->setActiveSheetIndex(1)
        ->setCellValue('A1', 'world!');

// Rename worksheet
$spreadsheet->getActiveSheet()->setTitle('Revenue');

$spreadsheet->getActiveSheet()
	->setCellValue('A1',"$company_name")
	->setCellValue('A2',"REVENUE SHEET FOR ORDER NUMBER $po_number");
	$spreadsheet->getActiveSheet()->mergeCells("A1:I1");
$spreadsheet->getActiveSheet()->mergeCells("A2:F2");
$spreadsheet->getActiveSheet()->getStyle('A1')->getFont()->setSize(20);
$spreadsheet->getActiveSheet()->getStyle('A2')->getFont()->setSize(10);
// set cell alignment
$spreadsheet->getActiveSheet()->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$spreadsheet->getActiveSheet()->getStyle('A2')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);

$spreadsheet->getActiveSheet()
->setCellValueByColumnAndRow(1,5,"Sno")
	->setCellValueByColumnAndRow(2,5,"Item Name")
	->setCellValueByColumnAndRow(3,5,"Size")
	->setCellValueByColumnAndRow(4,5,"Quantity")
	->setCellValueByColumnAndRow(5,5,"WSP")
	->setCellValueByColumnAndRow(6,5,"Bill Amount")
	->setCellValueByColumnAndRow(7,5,"Revenue")
	->setCellValueByColumnAndRow(8,5,"Total Revenue")
	->setCellValueByColumnAndRow(9,5,"Net Amount");	
	$spreadsheet->getActiveSheet()->getStyle('A5:I5')->applyFromArray($tableHead);
	$spreadsheet->getActiveSheet()->getColumnDimension('A')->setWidth(5);
	$spreadsheet->getActiveSheet()->getColumnDimension('B')->setWidth(60);
	$spreadsheet->getActiveSheet()->getColumnDimension('C')->setWidth(10);
	$spreadsheet->getActiveSheet()->getColumnDimension('D')->setWidth(15);
	$spreadsheet->getActiveSheet()->getColumnDimension('E')->setWidth(15);
	$spreadsheet->getActiveSheet()->getColumnDimension('F')->setWidth(15);
	$spreadsheet->getActiveSheet()->getColumnDimension('G')->setWidth(15);
	$spreadsheet->getActiveSheet()->getColumnDimension('H')->setWidth(25);
	$spreadsheet->getActiveSheet()->getColumnDimension('I')->setWidth(15);

	$sql1="select BRAND_NAME,SIZE_VALUE,sum(CASE_QUANTITY) as CASE_QUANTITY
	,((WSP+CUSTOM_DUTY)*PACK_SIZE) as wsp, sum(((WSP+CUSTOM_DUTY)*PACK_SIZE)*CASE_QUANTITY) as bill_amount
	,(EXCISE_DUTY*PACK_SIZE) EXCISE_DUTY,sum((EXCISE_DUTY*PACK_SIZE)*CASE_QUANTITY) as total_excise ,
	 (sum(((WSP+CUSTOM_DUTY)*PACK_SIZE)*CASE_QUANTITY)+sum((EXCISE_DUTY*PACK_SIZE)*CASE_QUANTITY)) as net_amout 
	from POPS_DISPATCH_ITEMS  where INVOICE_NO='$invoice'
	group by BRAND_NAME,SIZE_VALUE,WSP,CUSTOM_DUTY,EXCISE_DUTY,PACK_SIZE";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$i=1;
	$rowcount=6;
	
	$total_qty=0;
	$total_rev=0;
	$total_bill=0;
	$total_net=0;


	while($row1=sqlsrv_fetch_array($stmt1,SQLSRV_FETCH_ASSOC)){
		$spreadsheet->getActiveSheet()
		->setCellValue('A'.$rowcount , $i++)
		->setCellValue('B'.$rowcount , $row1['BRAND_NAME'])
		->setCellValue('C'.$rowcount , $row1['SIZE_VALUE'])
		->setCellValue('D'.$rowcount , $row1['CASE_QUANTITY'])
		->setCellValue('E'.$rowcount , $row1['wsp'])
		->setCellValue('F'.$rowcount , $row1['bill_amount'])
		->setCellValue('G'.$rowcount , $row1['EXCISE_DUTY'])
		->setCellValue('H'.$rowcount , $row1['total_excise'])
		->setCellValue('I'.$rowcount , $row1['net_amout']);
		$total_qty += $row1['CASE_QUANTITY'];
		$total_rev += $row1['total_excise'];
		$total_bill += $row1['bill_amount'];
		$total_net += $row1['net_amout'];

		$rowcount++;
	}
	$spreadsheet->getActiveSheet()
	->setCellValue('B'.$rowcount , 'Total')
	->setCellValue('D'.$rowcount , $total_qty)
	->setCellValue('F'.$rowcount , $total_bill)
	->setCellValue('H'.$rowcount , $total_rev)
	->setCellValue('I'.$rowcount , $total_net);
	$spreadsheet->getActiveSheet()->getStyle("A$rowcount:I$rowcount")->applyFromArray($tableHead);

	

// exit;
//autofilter
//define first row and last row
$firstRow=2;
$lastRow=$row-1;
//set the autofilter
// $spreadsheet->getActiveSheet()->setAutoFilter("A".$firstRow.":F".$lastRow);

//set the header first, so the result will be treated as an xlsx file.
header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');

//make it an attachment so we can define filename
header('Content-Disposition: attachment;filename="Submission.xlsx"');

//create IOFactory object
$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
//save into php output
$writer->save('php://output');
