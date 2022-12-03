<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();


	include 'includes/autoload.inc.php';
include 'includes/connect.php';
	$USER = $_SESSION['username'];

 $startdate = $_SESSION['startdate'];
 $enddate = $_SESSION['enddate'];
 $Department =$_SESSION['Department'];

$fromdate = date("d-m-Y", strtotime($startdate));
$todate = date("d-m-Y", strtotime($enddate));
if($Department=='All'){

  $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,INVOICE_NO,PO_NO,BRAND_NAME,SIZE_VALUE,a.VEND_CODE,VEND_NAME,CHALLAN_NO,SUM(CASE_QUANTITY) as CASE_QUANTITY,BRAND_CODE,
sum((wsp+CUSTOM_DUTY) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE  left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT where cast(a.SUPPLY_DATE as date) between '$startdate' and '$enddate' and  status  between 1 and 4  group by [PO_NO],INVOICE_NO,DEPARTMENT_NAME,BRAND_NAME,SIZE_VALUE,a.VEND_CODE,VEND_NAME,BRAND_CODE,CHALLAN_NO,b.DEPARTMENT
order by INVOICE_NO ";
}
 else{
 $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,INVOICE_NO,PO_NO,BRAND_NAME,SIZE_VALUE,a.VEND_CODE,VEND_NAME,CHALLAN_NO,SUM(CASE_QUANTITY) as CASE_QUANTITY,BRAND_CODE,
sum((wsp+CUSTOM_DUTY) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT where  b.DEPARTMENT in $Department and cast(a.SUPPLY_DATE as date) between '$startdate' and '$enddate' and status  between 1 and 4   group by [PO_NO],INVOICE_NO,DEPARTMENT_NAME,BRAND_NAME,SIZE_VALUE,a.VEND_CODE,VEND_NAME,BRAND_CODE,CHALLAN_NO,b.DEPARTMENT
order by INVOICE_NO ";
}

$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

$html = '<table border=1 cellspacing=0 cellpadding=5 width=700 align=center> <tr align=center  > <td bgcolor="pink" colspan=13><center>MONTHLY SALE DETAILS </center></td></tr>
			
			<tr > <td colspan=13 bgcolor="pink">Report Date : '.$fromdate.' to '.$todate.'</td></tr>
			<tr><th bgcolor="pink">SNo</th><th bgcolor="pink">Department</th><th bgcolor="pink">Order Number</th><th  bgcolor="pink">Invoice</th><th bgcolor="pink">Challan Number</th><th bgcolor="pink">Party Code</th><th bgcolor="pink">Party Name</th><th bgcolor="pink">Brand Code</th><th bgcolor="pink">Brand Name</th><th  bgcolor="pink">Size</th><th bgcolor="pink">Case</th><th bgcolor="pink">wsp</th><th bgcolor="pink">excise</th></tr>';
	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		
		$html .= "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td class='mid-text' >".$row['CHALLAN_NO']."</td><td class='mid-text' >".$row['VEND_CODE']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['BRAND_CODE']."</td><td class='mid-text' >".$row['BRAND_NAME']."</td><td class='mid-text' >".$row['SIZE_VALUE']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".$row['excise']."</td></tr>";

		$case += $row['CASE_QUANTITY'];
		$wsp += $row['wsp'];
		$excise += $row['excise'];


	}

	$html .= "<tr ><td colspan='10' bgcolor='pink'><b style='float:right; padding-right:10px;'>Total</b></td><td bgcolor='pink'>".$case."</td><td bgcolor='pink'>".$wsp."</td><td bgcolor='pink'>".$excise."</td></tr></table>";
	
		
			header('Content-Type: application/xls');
			$file="MONTHLY SALE DETAILS.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;

 // session_destroy();
?>