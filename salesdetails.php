<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
include 'includes/autoload.inc.php';
include 'includes/connect.php';
	$USER = $_SESSION['username'];
 $company_name=$_SESSION['COMPANY_NAME'];
 $startdate = $_SESSION['startdate'];
 $enddate = $_SESSION['enddate'];
 $Department =$_SESSION['Department'];
 $notcs = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited','CLUB'];
 $noecise = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited'];
$fromdate = date("d-m-Y", strtotime($startdate));
$todate = date("d-m-Y", strtotime($enddate));
$hcr = ['CLUB','HOTEL','RESTAURANT'];
if($Department=='All'){
   $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,VEND_NAME,b.VEND_CODE,b.VEND_ADDRESS,b.TIN,b.PAN_NO,INVOICE_DATE,INVOICE_NO,PO_NO,a.BRAND_NAME,a.SIZE_VALUE,SUM(CASE_QUANTITY) as CASE_QUANTITY,
sum((a.wsp) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY ,d.LIQUOR_TYPE_CD
 from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
 join POPS_PRICE_MASTER d on a.BRAND_CODE = d.BRAND_CODE  where cast(a.INVOICE_DATE as date) between '$startdate' and '$enddate' and status <=4   group by [PO_NO],INVOICE_NO,DEPARTMENT_NAME,a.BRAND_NAME,a.SIZE_VALUE,b.DEPARTMENT,d.LIQUOR_TYPE_CD,VEND_NAME,INVOICE_DATE,b.VEND_CODE,b.VEND_ADDRESS,b.TIN,b.PAN_NO
order by INVOICE_NO ";
}
 else{
 $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,VEND_NAME,b.VEND_CODE,b.VEND_ADDRESS,b.TIN,b.PAN_NO,INVOICE_DATE,INVOICE_NO,PO_NO,a.BRAND_NAME,a.SIZE_VALUE,SUM(CASE_QUANTITY) as CASE_QUANTITY,
sum((a.wsp) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY ,d.LIQUOR_TYPE_CD from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE  left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT where  b.DEPARTMENT in $Department and cast(a.INVOICE_DATE as date) between '$startdate' and '$enddate' and status <=4  group by [PO_NO],INVOICE_NO,DEPARTMENT_NAME,a.BRAND_NAME,a.SIZE_VALUE,b.DEPARTMENT,d.LIQUOR_TYPE_CD,VEND_NAME,INVOICE_DATE,b.VEND_CODE,b.VEND_ADDRESS,b.TIN,b.PAN_NO
order by INVOICE_NO ";
}
$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;
	$case =0;
	$wsp =0;
	$custom_sum =0;
	$excise=0;
	$total_vat =0;
	$total_tcs =0;
	$total_amount =0;
$html = '<table border=1 cellspacing=0 cellpadding=5 width=700 align=center> <tr align=center  > <td bgcolor="pink" colspan=21><center> SALE DETAILS REPORT </center></td></tr>
			
			<tr > <td colspan=21 bgcolor="pink" align="center" >'.$company_name.'</td></tr> 
			<td colspan=21 bgcolor="pink">Report Date : '.$fromdate.' to '.$todate.'</td></tr>
			<tr><th bgcolor="pink">SNo</th><th bgcolor="pink">Department</th><th bgcolor="pink">Order Number</th><th  bgcolor="pink">Invoice</th><th  bgcolor="pink">Invoice Date</th><th  bgcolor="pink">Excise Code</th><th  bgcolor="pink">Party Name</th><th  bgcolor="pink">Party Address</th><th  bgcolor="pink">Party TIN</th><th  bgcolor="pink">Party PAN</th><th bgcolor="pink">Brand Name</th><th  bgcolor="pink">Size</th><th bgcolor="pink">Case</th><th bgcolor="pink">WSP</th><th bgcolor="pink">Custom</th><th bgcolor="pink">Excise</th><th  bgcolor="pink">Add Revenue</th><th bgcolor="pink">VAT</th><th  bgcolor="pink">Less Revenue</th><th bgcolor="pink">TCS</th><th bgcolor="pink">Total</th></tr>';
	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$excise_rev=0;
	// var_dump($hcr);
	if(in_array($row['DEPARTMENT_NAME'],$hcr)){
		// echo "Here";
		if($row['LIQUOR_TYPE_CD'] == '171'){
			if($row['DEPARTMENT_NAME'] =='HOTEL'){
				// $row['wsp']=1.3*$row['wsp'];
				$excise_rev = $row['wsp']*0.3;
			}
			else{
				// $row['wsp']=1.2*$row['wsp'];
				$excise_rev = $row['wsp']*0.2;
			}
		}
	}
	$cost = $row['wsp']+$row['CUSTOM_DUTY']+$row['excise']+$excise_rev;
	$vat = ($cost)*0.25;
	$tcs = ($cost+$vat-$excise_rev)*0.01;
	
	if(in_array($row['DEPARTMENT_NAME'],$notcs)){
		 $tcs=0;
	}
	// exit;
	// echo $excise_rev;
	// exit;
	$total=$cost+$vat+$tcs-$excise_rev;  
	if(in_array($row['DEPARTMENT_NAME'],$noecise) && $row['LIQUOR_TYPE_CD'] == '171'){
		$total=$cost+$vat+$tcs-$excise_rev-$row['excise']; 
 }
		$dateInvoice = $row['INVOICE_DATE']?$row['INVOICE_DATE']->format('d-m-Y'):'';
		$html .= "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td class='mid-text' >".$dateInvoice."</td><td class='mid-text' >".$row['VEND_CODE']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['VEND_ADDRESS']."</td><td class='mid-text' >".$row['TIN']."</td><td class='mid-text' >".$row['PAN_NO']."</td><td class='mid-text' >".$row['BRAND_NAME']."</td><td class='mid-text' >".$row['SIZE_VALUE']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".($row['CUSTOM_DUTY'])."</td><td class='mid-text' >".$row['excise']."</td><td class='mid-text' >".$excise_rev."</td><td class='mid-text' >".round($vat,2)."</td><td class='mid-text' >".$excise_rev."</td><td class='mid-text' >".round($tcs,2)."</td><td class='mid-text' >".round($total,2)."</td></tr>";

		$case += $row['CASE_QUANTITY'];
		$wsp += $row['wsp'];
		$excise += $row['excise'];
		$total_vat +=$vat;
		$total_tcs +=$tcs;
		$total_amount +=$total;
		$custom_sum += $row['CUSTOM_DUTY'];


	}
// exit;
	$html .= "<tr ><td colspan='12' bgcolor='pink'><b style='float:right; padding-right:10px;'>Total</b></td><td bgcolor='pink'>".$case."</td><td bgcolor='pink'>".$wsp."</td><td bgcolor='pink'>".$custom_sum."</td><td bgcolor='pink'>".$excise."</td><td bgcolor='pink'></td><td bgcolor='pink'>".round($total_vat,2)."</td><td bgcolor='pink'></td><td bgcolor='pink'>".round($total_tcs,2)."</td><td bgcolor='pink'>".ROUND($total_amount,2)."</td></tr></table>";
	// 	<tr > <td colspan=10 bgcolor="pink" align="center" >'.$company_name.'</td></tr>
				
	// echo $html;
	// exit;	
			header('Content-Type: application/xls');
			$file=" SALE DETAILS.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;

 // session_destroy();
?>