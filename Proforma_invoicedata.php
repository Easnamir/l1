<?php 
session_start();

$user = $_SESSION["username"];
$COMPANY_id = $_SESSION['COMPANY_id'];
$CINV = $_SESSION['CINV'];

include 'includes/autoload.inc.php';
include 'includes/connect.php';

 if (isset($_GET['Proforma_invoicedata']) )
	
	{
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];

 $sql = "select a.VEND_CODE,VEND_NAME,DEPARTMENT,PO_NO,SUM(a.[CASE]) as CASE_QUANTITY, sum(p.wsp*BOTTLE) as wsp ,sum(p.CUSTOM_DUTY*BOTTLE) as CUSTOM_DUTY 
,sum(p.EXCISE_PRICE*BOTTLE) as excise ,sum((p.wsp) *BOTTLE) as wsp1,LIQUOR_TYPE_CD 
from POPS_PANDING_PO_CREATE a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE 
join POPS_PRICE_MASTER p on a.BRAND_CODE=p.BRAND_CODE 
where cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' and   b.DEPARTMENT not  in('DSIIDC','DCCWS','DTTDC','DSCSC')
group by [PO_NO],DEPARTMENT, a.VEND_CODE,VEND_NAME,LIQUOR_TYPE_CD ";
// exit;
	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<button id='".$row['PO_NO']."' onclick='printInvoicehcrlist(this.id)' class='w3-button w3-red'> Print </button> <button name='".$row['PO_NO']."' onclick='sendInvoicehcrlist(this.name)' class='w3-button w3-red'> Send </button>";
		  $cost= ($row['excise']+$row['wsp']+$row['CUSTOM_DUTY']);

      if($row['LIQUOR_TYPE_CD']=='171'){
        
        if ($row['DEPARTMENT'] == 'HOTEL') {
        	$wsp_retrun=($row['wsp1']*.30);
        }
       else {
       	$wsp_retrun=($row['wsp1']*.20);
       }

        }
   else {

   $wsp_retrun=0;

   	}
		  $vat=($cost+$wsp_retrun)*.25;

		  // $vat=($cost+$wsp_retrun)*.25;
        if($row['DEPARTMENT'] == 'CLUB'){
        	 $tcs=0;
        }
        else{
        	$tcs=($cost+$vat)*0.01;
        }

		  
		  $total=$cost+$vat+$tcs;
    
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['DEPARTMENT']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".($row['CUSTOM_DUTY'])."</td><td class='mid-text' >".$row['excise']."</td><td class='mid-text' >".ROUND($vat,2)."</td><td class='mid-text' >".ROUND($tcs,2)."</td><td class='mid-text' >".ROUND($total,2)."</td><td class='mid-text'>".$button."</td></tr>";
     
			}
			if($i<1){

			echo "<tr><td colspan='14' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}
	

			
	
}




?>
