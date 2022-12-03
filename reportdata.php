<?php 
session_start();

$user = $_SESSION["username"];
$COMPANY_id = $_SESSION['COMPANY_id'];
$CINV = $_SESSION['CINV'];
include 'includes/autoload.inc.php';
include 'includes/connect.php';
if(date('m')<4){
	$finyear = (date('y')-1).''.date('y');
}
else{
   $finyear = date('y').''.(date('y')+1);
}

 if (isset($_GET['register']) )
	
	{
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];

if($Department=='All'){
$sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME ,BRAND_CODE,BRAND_NAME,SIZE_VALUE,SUM(CASE_QUANTITY) as CASE_QUANTITY, sum((wsp+CUSTOM_DUTY) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise,PO_NO,TP_NO from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE 

left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	where  cast(a.CREATED_DATE as date) between '$startdate' and '$enddate'  and status <=4 
  group by BRAND_CODE,BRAND_NAME,SIZE_VALUE,DEPARTMENT_NAME,b.DEPARTMENT,PO_NO,TP_NO ";
}
else{
  $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME ,BRAND_CODE,BRAND_NAME,SIZE_VALUE,SUM(CASE_QUANTITY) as CASE_QUANTITY, sum((wsp+CUSTOM_DUTY) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise,PO_NO,TP_NO from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	where  b.DEPARTMENT in $Department and cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' and status <=4 
  group by BRAND_CODE,BRAND_NAME,SIZE_VALUE,DEPARTMENT_NAME,b.DEPARTMENT,PO_NO,TP_NO ";
}
	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		
		  $cost= ($row['excise']+$row['wsp']);
		  $vat=$cost*.25;
		  $tcs=0;
		  $total=$cost+$vat+$tcs;
    
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$row['BRAND_CODE']."</td><td class='mid-text' >".$row['BRAND_NAME']."</td><td class='mid-text' >".$row['SIZE_VALUE']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".$row['excise']."</td></tr>";

     
			}
			if($i<1){
			echo "<tr><td colspan='13' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}

		// print_r($data);
	$_SESSION['registersummary']=serialize($data);
		$_SESSION['Department'] = $Department;
		$_SESSION['startdate'] = $startdate;
		$_SESSION['enddate'] = $enddate;


	
}

 if (isset($_GET['monthwisereport']) )
	
	{
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];

if($Department=='All'){

   $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME ,a.VEND_CODE,VEND_NAME,BRAND_NAME,SUM(CASE_QUANTITY) as CASE_QUANTITY, 
sum((wsp+CUSTOM_DUTY) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise 
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE  left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	where  cast(SUPPLY_DATE as date) between '$startdate' and '$enddate' and status  between 1 and 4
 group by DEPARTMENT_NAME,a.VEND_CODE,VEND_NAME,BRAND_NAME,b.DEPARTMENT ";

}
else{
$sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME ,a.VEND_CODE,VEND_NAME,BRAND_NAME,SUM(CASE_QUANTITY) as CASE_QUANTITY, 
sum((wsp+CUSTOM_DUTY) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise 
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT 
	where  b.DEPARTMENT in $Department and cast(SUPPLY_DATE as date) between '$startdate' and '$enddate' and status  between 1 and 4
 group by DEPARTMENT_NAME,a.VEND_CODE,VEND_NAME,BRAND_NAME ,b.DEPARTMENT";
}

	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		
		  $cost= ($row['excise']+$row['wsp']);
		  $vat=$cost*.25;
		  $tcs=0;
		  $total=$cost+$vat+$tcs;
    
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['VEND_CODE']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['BRAND_NAME']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".$row['excise']."</td></tr>";

     
			}
			if($i<1){
			echo "<tr><td colspan='11' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}

		// print_r($data);
	$_SESSION['monthwisereport']=serialize($data);
		$_SESSION['Department'] = $Department;
		$_SESSION['startdate'] = $startdate;
		$_SESSION['enddate'] = $enddate;


	
}
if (isset($_GET['saleregister']) )
	
	{
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];

if($Department=='All'){

  $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME ,SUM(CASE_QUANTITY) as CASE_QUANTITY, 
sum((a.wsp) *BOTTLE_QUANTITY) as wsp, sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise, d.LIQUOR_TYPE_CD
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT JOIN POPS_PRICE_MASTER d on a.BRAND_CODE=d.BRAND_CODE
	where status <=4 and cast(INVOICE_DATE as date) between '$startdate' and '$enddate' 
 group by DEPARTMENT_NAME,b.DEPARTMENT,d.LIQUOR_TYPE_CD ";

}
else{
$sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME ,SUM(CASE_QUANTITY) as CASE_QUANTITY, 
sum((a.wsp) *BOTTLE_QUANTITY) as wsp,sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,d.LIQUOR_TYPE_CD
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left  join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT JOIN POPS_PRICE_MASTER d on a.BRAND_CODE=d.BRAND_CODE
	where status =4 and b. DEPARTMENT in $Department and cast(INVOICE_DATE as date) between '$startdate' and '$enddate' and status <=4 
 group by DEPARTMENT_NAME,b.DEPARTMENT,d.LIQUOR_TYPE_CD";
}
// exit;
	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;
	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		// $data[] = $row;restaurant
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
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp']+$row['CUSTOM_DUTY'])."</td><td class='mid-text' >".$row['excise']."</td><td class='mid-text' >".round($vat,2)."</td><td class='mid-text' >".round($tcs,2)."</td><td class='mid-text' >".round($total,2)."</td></tr>";

     
			}
			// exit;
			if($i<1){
			echo "<tr><td colspan='11' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}

		// print_r($data);
	// $_SESSION['saleregister']=serialize($data);
		$_SESSION['Department'] = $Department;
		$_SESSION['startdate'] = $startdate;
		$_SESSION['enddate'] = $enddate;


	
}


?>