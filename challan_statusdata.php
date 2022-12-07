<?php 
session_start();

$user = $_SESSION["username"];
$COMPANY_id = $_SESSION['COMPANY_id'];
$CINV = $_SESSION['CINV'];
include 'includes/autoload.inc.php';
include 'includes/connect.php';
if (isset($_GET['challanstatusdata']) )
	
	{
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];


   $sql = "select * from 
(select distinct  isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME ,VEND_NAME,A.VEND_CODE,a.TP_NO,a.CHALLAN_NO,SUPPLY_DATE
from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE
left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT

	where  b.DEPARTMENT in $Department and cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' and status >0 
   group by isnull(DEPARTMENT_NAME,b.DEPARTMENT) ,VEND_NAME,A.VEND_CODE,a.TP_NO,a.CHALLAN_NO,SUPPLY_DATE 
    ) as a

  left join 


 (select challan_no,STRING_AGG(STATUS_CHALLAN,',') as STATUS_CHALLAN, STRING_AGG(REMARK_CHALLAN,',') as REMARK_CHALLAN from POPS_CHALLAN_STATUS
 group by challan_no ) as b

 on a.CHALLAN_NO=b.challan_no
 order by a.CHALLAN_NO desc

 ";

	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		
		 $supp_date = $row['SUPPLY_DATE']?$row['SUPPLY_DATE']->format('d-m-Y'):'NA';
		 $supp_date1 = $row['SUPPLY_DATE']?$row['SUPPLY_DATE']->format('Y-m-d'):'NA';

    $challan_num= $row['CHALLAN_NO']?$row['CHALLAN_NO']:'NA';
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$challan_num."</td><td class='mid-text' >".$supp_date."</td><td class='mid-text' >".$row['STATUS_CHALLAN']."</td><td class='mid-text' >".$row['REMARK_CHALLAN']."</td><td class='mid-text point'><i data-id='".$challan_num."' name='".$row['TP_NO']."' data-vend='".$row['VEND_CODE']."' data-challandate='".$supp_date1."' title='".$row['TP_NO']."' class='fa fa-edit' onclick='updatechallanStatus(this.title)'></i></td></tr>";
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
if(isset($_GET['fun']) && $_GET['fun']=='changeStatus'){
	extract($_POST);

	 $sql ="IF EXISTS (SELECT * FROM POPS_CHALLAN_STATUS WHERE TP_NO='$tp_no')
	 BEGIN
	 UPDATE POPS_CHALLAN_STATUS SET STATUS_CHALLAN='$challan_status',REMARK_CHALLAN='$remark',UPDATED_BY='$user',UPDATED_DATE=GETDATE() WHERE TP_NO='$tp_no'
	 End
	 ELSE
	 BEGIN
	 INSERT INTO [dbo].[POPS_CHALLAN_STATUS]
		 ([VEND_CODE]
		 ,[CHALLAN_DATE]
		 ,[TP_NO]
		 ,[CREATED_BY]
		 ,[CREATED_DATE]
		 ,[CHALLAN_NO]
		 ,[STATUS_CHALLAN]
		 ,[REMARK_CHALLAN])
	 VALUES('$vend_id','$challan_date','$tp_no','$user',getdate(),'$pk_id','$challan_status','$remark')
	 END";
$stmt = sqlsrv_query($conn,$sql);
if($stmt !== false){
	echo "Status Changed Successfully";
}
else{
	echo "Something Failed!";
}

}
if(isset($_GET['fun']) && $_GET['fun']=='changeStatusTp'){
	extract($_POST);
	$sql = "UPDATE [dbo].[POPS_TP_STATUS_DETAILS] set UPDATE_BY='$user',UPDATED_DATE=getdate(),MANUAL_STATUS='$tp_status' where TP_NUMBER='$tp_no'";
	$stmt = sqlsrv_query($conn,$sql);
	if($stmt!=false){
		echo "Status Updated Successfully";
	}
	else{
		echo "Something went wrong. Please try again";
	}
}
if (isset($_GET['tpstatusdata']) )
	
	{
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];

if($Department=='ALL'){
    $sql = "select  distinct c.DEPARTMENT,VEND_NAME,TP_NO ,b.ISSUE_DATE,b.DISPATCH_DATE,b.RECEIVE_DATE,TP_STATUS,b.UPDATE_BY,b.UPDATED_DATE,MANUAL_STATUS from POPS_DISPATCH_ITEMS a
join POPS_TP_STATUS_DETAILS b on a.TP_NO=b.TP_NUMBER
join POPS_VEND_DETAILS c on c.VEND_CODE=a.VEND_CODE 
	where  cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' order by TP_STATUS   ";
}
else{
  $sql = "select  distinct c.DEPARTMENT,VEND_NAME,TP_NO ,b.ISSUE_DATE,b.DISPATCH_DATE,b.RECEIVE_DATE,TP_STATUS,b.UPDATE_BY,b.UPDATED_DATE,MANUAL_STATUS from POPS_DISPATCH_ITEMS a
join POPS_TP_STATUS_DETAILS b on a.TP_NO=b.TP_NUMBER
join POPS_VEND_DETAILS c on c.VEND_CODE=a.VEND_CODE 
	where  c.DEPARTMENT in $Department and cast(a.CREATED_DATE as date) between '$startdate' and '$enddate'
	order by TP_STATUS  ";


}
// exit;
	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		
		 $issue_date = $row['ISSUE_DATE']?$row['ISSUE_DATE']->format('d-m-Y'):'NA';
		 $dispatch_date = $row['DISPATCH_DATE']?$row['DISPATCH_DATE']->format('d-m-Y'):'NA';
		 $receive_date = $row['RECEIVE_DATE']?$row['RECEIVE_DATE']->format('d-m-Y'):'NA';
		 $updated_date = $row['UPDATED_DATE']?$row['UPDATED_DATE']->format('d-m-Y'):'NA';

	
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$issue_date."</td><td class='mid-text' >".$dispatch_date."</td><td class='mid-text' >".$receive_date."</td><td class='mid-text' >".$row['TP_STATUS']."</td><td class='mid-text' >".$row['MANUAL_STATUS']."</td><td class='mid-text' >".$row['UPDATE_BY']."</td><td class='mid-text' >".$updated_date."</td><td><i class='fa fa-edit' id='".$row['TP_NO']."' onclick='updateTpStatus(this.id)'></i></td></tr>";
			}
			if($i<1){
			echo "<tr><td colspan='13' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}

		// print_r($data);
	

	
}

if(isset($_GET['fun']) && $_GET['fun']=='getInvoiceDetails'){
	// var_dump($_GET);
	$notcs = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited','CLUB'];
	$noecise = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited'];
//  $fromdate = date("d-m-Y", strtotime($startdate));
//  $todate = date("d-m-Y", strtotime($enddate));
 $hcr = ['CLUB','HOTEL','RESTAURANT'];
	extract($_GET);
	 $sql ="select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,INVOICE_NO,SUM(CASE_QUANTITY) as CASE_QUANTITY,
	sum((a.wsp) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY ,d.LIQUOR_TYPE_CD
	 from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	 join POPS_PRICE_MASTER d on a.BRAND_CODE = d.BRAND_CODE  
	 where INVOICE_NO='$invoice' and status <=4  
	 group by INVOICE_NO,DEPARTMENT_NAME,b.DEPARTMENT,d.LIQUOR_TYPE_CD";
	 $stmt = sqlsrv_query($conn,$sql);
	 while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){

		// extract($row);
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
	 }
	 echo round($total,2);
}
if(isset($_GET['fun']) && $_GET['fun']=='getInvoiceReceivedAmount'){
	extract($_GET);
	 $sql = "select isnull(sum(received_amount),0) as paid_amount from POPS_PAYMENT_RECEIVED where invoice_no='$invoice'";
	 $stmt = sqlsrv_query($conn,$sql);
	 while ($row =sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
		echo $row['paid_amount'];
	 }
}
// deleteReceivedAmount

if(isset($_GET['fun']) && $_GET['fun']=='deleteReceivedAmount'){
	extract($_GET);
	//  echo $pk;
	$sql = "delete from POPS_PAYMENT_RECEIVED where POPS_PAYMENT_RECEIVED_PK='$pk'";
	$stmt = sqlsrv_query($conn,$sql);
	if(!$stmt){
		echo "Something went wrong. Please try again";
		
	}
	else{
		echo "Record deleted Successfully";
	}
}

?>