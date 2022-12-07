<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
include 'includes/autoload.inc.php';
include 'includes/connect.php';
if (isset($_GET['dow_Master_Data_Report']) ){
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];
$fromdate = $_GET['startdate'];
$todate = $_GET['enddate'];
$company_name=$_SESSION['COMPANY_NAME'];
$fromdate1 = date("d-m-Y", strtotime($fromdate));
$todate1 = date("d-m-Y", strtotime($todate));
 $notcs = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited','CLUB'];
 $noecise = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited'];
 $hcr = ['CLUB','HOTEL','RESTAURANT'];
if($Department=='All')
{
     $sql = "select DEPARTMENT_NAME,VEND_NAME,VEND_ADDRESS,PO_NO,aa.TP_NO,ISSUE_DATE,BRAND_NAME,SIZE_VALUE,CASE_QUANTITY,TP_STATUS,STATUS_CHALLAN,DISPATCH_DATE  ,RECEIVE_DATE,PO_DATE,MANUAL_STATUS,'',aa.CHALLAN_NO,SUPPLY_DATE,INVOICE_NO,INVOICE_DATE,wsp,excise,CUSTOM_DUTY,LIQUOR_TYPE_CD,po_month
from (select TP_NO,isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,a.PO_DATE,VEND_NAME,b.VEND_CODE,b.VEND_ADDRESS,
b.TIN,b.PAN_NO,INVOICE_DATE,INVOICE_NO,PO_NO,a.BRAND_NAME,a.SIZE_VALUE,SUM(CASE_QUANTITY) as CASE_QUANTITY,CHALLAN_NO,SUPPLY_DATE,
sum((a.wsp) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY ,d.LIQUOR_TYPE_CD,DATENAME(MONTH, PO_DATE)  as po_month
 from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
 join POPS_PRICE_MASTER d on a.BRAND_CODE = d.BRAND_CODE  where cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' and status <=4  
 group by a.TP_NO,[PO_NO],a.CHALLAN_NO,SUPPLY_DATE,a.PO_DATE,INVOICE_NO,DEPARTMENT_NAME,a.BRAND_NAME,a.SIZE_VALUE,b.DEPARTMENT,d.LIQUOR_TYPE_CD,VEND_NAME,INVOICE_DATE,b.VEND_CODE,b.VEND_ADDRESS,b.TIN,b.PAN_NO,DATENAME(MONTH, PO_DATE)
 ) as aa
 left join POPS_TP_STATUS_DETAILS bb
 on aa.TP_NO=bb.TP_NUMBER
 left join POPS_CHALLAN_STATUS cc on aa.TP_NO=cc.TP_NO

 ";
}
else
{
	 $sql = "select DEPARTMENT_NAME,VEND_NAME,VEND_ADDRESS,PO_NO,aa.TP_NO,ISSUE_DATE,BRAND_NAME,SIZE_VALUE,CASE_QUANTITY,TP_STATUS,STATUS_CHALLAN,DISPATCH_DATE  ,RECEIVE_DATE,PO_DATE,MANUAL_STATUS,'',aa.CHALLAN_NO,SUPPLY_DATE,INVOICE_NO,INVOICE_DATE,wsp,excise,CUSTOM_DUTY,LIQUOR_TYPE_CD,po_month
from (select TP_NO,isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,a.PO_DATE,VEND_NAME,b.VEND_CODE,b.VEND_ADDRESS,
b.TIN,b.PAN_NO,INVOICE_DATE,INVOICE_NO,PO_NO,a.BRAND_NAME,a.SIZE_VALUE,SUM(CASE_QUANTITY) as CASE_QUANTITY,CHALLAN_NO,SUPPLY_DATE,
sum((a.wsp) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY ,d.LIQUOR_TYPE_CD,DATENAME(MONTH, PO_DATE) as po_month
 from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
 join POPS_PRICE_MASTER d on a.BRAND_CODE = d.BRAND_CODE  where cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' and status <=4  and  b.DEPARTMENT in $Department
 group by TP_NO,[PO_NO],CHALLAN_NO,SUPPLY_DATE,a.PO_DATE,INVOICE_NO,DEPARTMENT_NAME,a.BRAND_NAME,a.SIZE_VALUE,b.DEPARTMENT,d.LIQUOR_TYPE_CD,VEND_NAME,INVOICE_DATE,b.VEND_CODE,b.VEND_ADDRESS,b.TIN,b.PAN_NO,DATENAME(MONTH, PO_DATE)
 ) as aa
 left join POPS_TP_STATUS_DETAILS bb
 on aa.TP_NO=bb.TP_NUMBER
 left join POPS_CHALLAN_STATUS cc on aa.TP_NO=cc.TP_NO

 ";
}
exit;

	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;
$html .= '<table border=1 cellspacing=0 cellpadding=5 width=700 align=center> <tr align=center  > <td bgcolor="pink" colspan=29><center> MASTER DATA REPORT</center></td></tr>
			<tr > <td colspan=29 bgcolor="pink" align="center" >'.$company_name.'</td></tr>
			<tr > <td colspan=29 bgcolor="pink">Department: '.$Department.'</td></tr>
			<tr > <td colspan=29 bgcolor="pink">Report Date : '.$fromdate1.' to '.$todate1.'</td></tr>
				<tr><th bgcolor="pink">SNo</th><th bgcolor="pink" >Department</th><th bgcolor="pink">Vend Name</th><th bgcolor="pink">Vend Address</th><th bgcolor="pink">PO No.</th><th bgcolor="pink">TP No.</th><th bgcolor="pink">Issue Date</th><th bgcolor="pink">Brand</th><th bgcolor="pink">Size (ml)</th><th bgcolor="pink">Cases in TP</th><th bgcolor="pink">Status</th><th bgcolor="pink">Dispatch Date</th><th bgcolor="pink">Recieve Date</th><th bgcolor="pink">PO Date</th><th bgcolor="pink">Our Status</th><th bgcolor="pink">Challan No </th><th bgcolor="pink">Challan Date</th><th bgcolor="pink"> Challan Status</th><th bgcolor="pink">Invoice No</th><th bgcolor="pink">Invoice Date</th><th bgcolor="pink">WSP</th><th bgcolor="pink">Custom</th><th bgcolor="pink">Excise</th><th  bgcolor="pink">Add Revenue</th><th bgcolor="pink">VAT</th><th  bgcolor="pink">Less Revenue</th><th bgcolor="pink">TCS</th><th bgcolor="pink">Invoice Receiable</th><th bgcolor="pink">Month</th></tr>';
	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		// $data[] = $row;
	$excise_rev=0;
	// var_dump($hcr);
	$supp_date = $row['SUPPLY_DATE']?$row['SUPPLY_DATE']->format('d-m-Y'):'NA';
	if(in_array($row['DEPARTMENT_NAME'],$hcr)){
		$supp_date = 'NA';
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
	
		 // $supp_date = $row['SUPPLY_DATE']?$row['SUPPLY_DATE']->format('d-m-Y'):'NA';
		 $supp_date1 = $row['SUPPLY_DATE']?$row['SUPPLY_DATE']->format('Y-m-d'):'NA';
		 $issue_date = $row['ISSUE_DATE']?$row['ISSUE_DATE']->format('d-m-Y'):'NA';
		 $dispatch_date = $row['DISPATCH_DATE']?$row['DISPATCH_DATE']->format('d-m-Y'):'NA';
		 $receve_date = $row['RECEIVE_DATE']?$row['RECEIVE_DATE']->format('d-m-Y'):'NA';
		 $po_date = $row['PO_DATE']?$row['PO_DATE']->format('d-m-Y'):'NA';
		 $inv_date = $row['INVOICE_DATE']?$row['INVOICE_DATE']->format('d-m-Y'):'NA';
         $challan_num= $row['CHALLAN_NO']?$row['CHALLAN_NO']:'NA';
	
     $html.= "<tr><td  class='mid-text'>".++$i."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['VEND_ADDRESS']."</td><td class='mid-text' >".$row['PO_NO']."</td> <td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$issue_date."</td><td class='mid-text' >".$row['BRAND_NAME']."</td> <td class='mid-text' >".$row['SIZE_VALUE']."</td> <td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".$row['TP_STATUS']."</td> <td class='mid-text' >".$dispatch_date."</td><td class='mid-text' >".$receve_date."</td><td class='mid-text' >".$po_date."</td><td class='mid-text' >".$row['MANUAL_STATUS']."</td><td class='mid-text' >".$challan_num."</td>  <td class='mid-text' >".$supp_date."</td> <td class='mid-text' >".$row['STATUS_CHALLAN']."</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td class='mid-text' >".$inv_date."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".($row['CUSTOM_DUTY'])."</td><td class='mid-text' >".$row['excise']."</td><td class='mid-text' >".$excise_rev."</td><td class='mid-text' >".round($vat,2)."</td><td class='mid-text' >".$excise_rev."</td><td class='mid-text' >".round($tcs,2)."</td><td class='mid-text' >".round($total,2)."</td><td class='mid-text' >".$row['po_month']."</td></tr>";
		
		}

	
	

	
}

header('Content-Type: application/xls');
			$file=" master_data.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;



?>