<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
include 'includes/autoload.inc.php';
include 'includes/connect.php';
$USER = $_SESSION['username'];
	
$fromdate = $_SESSION['startdate'];
$todate = $_SESSION['enddate'];
$Department =$_SESSION['Department'];
$company_name=$_SESSION['COMPANY_NAME'];
$fromdate1 = date("d-m-Y", strtotime($fromdate));
$todate1 = date("d-m-Y", strtotime($todate));

if($Department=='All'){

    $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,a.BRAND_NAME,a.SIZE_VALUE,SUM(CASE_QUANTITY) as CASE_QUANTITY, 
sum((a.wsp) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY ,d.LIQUOR_TYPE_CD
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT join POPS_PRICE_MASTER d on a.BRAND_CODE = d.BRAND_CODE
	where status <=4 and cast(INVOICE_DATE as date) between '$fromdate' and '$todate' 
 group by DEPARTMENT_NAME,a.BRAND_NAME,a.SIZE_VALUE,b.DEPARTMENT ,d.LIQUOR_TYPE_CD";

}
else{
  $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,a.BRAND_NAME,a.SIZE_VALUE,SUM(CASE_QUANTITY) as CASE_QUANTITY, 
 sum((a.wsp) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY ,d.LIQUOR_TYPE_CD
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT join POPS_PRICE_MASTER d on a.BRAND_CODE = d.BRAND_CODE
	where status <=4 and  b.DEPARTMENT in $Department and cast(INVOICE_DATE as date) between '$fromdate' and '$todate' 
 group by DEPARTMENT_NAME,a.BRAND_NAME,a.SIZE_VALUE,b.DEPARTMENT,d.LIQUOR_TYPE_CD";
}
// exit;
$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;
	$case =0;
	$wsp =0;
	$custom_sum =0;

	$excise=0;
	$total_vat =0;
	$total_tcs =0;
	$total_amount =0;

$html = '<table border=1 cellspacing=0 cellpadding=5 width=700 align=center> <tr align=center  > <td bgcolor="pink" colspan=11><center>SALE  REGISTER SUMMARY</center></td></tr>
			<tr > <td colspan=11 bgcolor="pink" align="center" >'.$company_name.'</td></tr>
			<tr > <td colspan=11 bgcolor="pink">Report Date : '.$fromdate1.' to '.$todate1.'</td></tr>
			<tr><th bgcolor="pink">SNO</th><th bgcolor="pink">Department</th><th bgcolor="pink">Brand Name</th><th bgcolor="pink">Size</th><th bgcolor="pink">Case Qty</th><th bgcolor="pink">WSP</th><th bgcolor="pink">Custom</th><th bgcolor="pink">Excise</th><th bgcolor="pink">VAT</th><th bgcolor="pink">TCS</th><th bgcolor="pink">Total</th></tr>';


while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
	$notcs = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited','CLUB'];
	$hcr = ['CLUB','HOTEL','RESTAURANT'];
	if(in_array($row['DEPARTMENT'],$hcr)){
		if($row['LIQUOR_TYPE_CD'] == '171'){
			if($row['DEPARTMENT_NAME'] =='HOTEL'){
				$row['wsp']=1.3*$row['wsp'];
			}
			else{
				$row['wsp']=1.2*$row['wsp'];
			}
		}
	}
	$cost = $row['wsp']+$row['CUSTOM_DUTY']+$row['excise'];
	$vat = ($cost)*0.25;
	$tcs = ($cost+$vat)*0.01;
	// $tcs = 
	// echo $row['DEPARTMENT_NAME'];
	if(in_array($row['DEPARTMENT_NAME'],$notcs)){
		 $tcs=0;
	}
	// exit;
	$total=$cost+$vat+$tcs;  

		$html .= "<tr><td  class='mid-text' class='width=10%'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['BRAND_NAME']."</td><td class='mid-text' >".$row['SIZE_VALUE']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".($row['CUSTOM_DUTY'])."</td><td class='mid-text' >".$row['excise']."</td><td class='mid-text' >".round($vat,2)."</td><td class='mid-text' >".round($tcs,2)."</td><td class='mid-text' >".round($total,2)."</td></tr>";

		$case += $row['CASE_QUANTITY'];
		$wsp += $row['wsp'];
		$custom_sum += $row['CUSTOM_DUTY'];

		$excise += $row['excise'];
		$total_vat +=$vat;
		$total_tcs +=$tcs;
		$total_amount +=$total;



	}
	// print_r($data);

	$html .= "<tr ><td colspan='4' bgcolor='pink'><b style='float:right; padding-right:10px;'>Total</b></td><td bgcolor='pink'>".$case."</td><td bgcolor='pink'>".$wsp."</td><td bgcolor='pink'>".$custom_sum."</td><td bgcolor='pink'>".$excise."</td><td bgcolor='pink'>".round($total_vat,2)."</td><td bgcolor='pink'>".round($total_tcs,2)."</td><td bgcolor='pink'>".round($total_amount,2)."</td></tr></table>";
	
		
	// echo $html;
	// exit;	
			header('Content-Type: application/xls');
			$file="SALE REGISTER SUMMARY.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;

?>