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




if (isset($_GET['lotwise_challan']) )
{

  $sql = "select count (DISTINCT TP_NO ) tp_no,PO_NO,PO_DATE,DEPARTMENT_NAME,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount,cast(sum((EXCISE_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as excise 
from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE  join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT

where b.DEPARTMENT in('DSIIDC','DCCWS','DTTDC','DSCSC') and status =0 
group by PO_NO,DEPARTMENT_NAME,PO_DATE";

  // exit;
	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' class='w3-checkbox' data-case='".$row['case_QUANTITY']."' onclick='Check_po_list()' name='tp_challan[]' value='".$row['PO_NO']."' />";
		
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['PO_NO']."</td><td>".$row['PO_DATE']->format('d-m-Y')."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['tp_no']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td  class='mid-text'>".$row['excise']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
	
	
}

if(isset($_GET['fun']) && $_GET['fun']=='processlotChallan'){
	
	extract($_REQUEST);
	// var_dump($po_arr);
	// exit;
	$sno=1;
	$count =0;
	$sql1 = "SELECT max(CHALLAN_S_NO) as SNO FROM POPS_DISPATCH_ITEMS";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$row = sqlsrv_fetch_array($stmt1);
	 $new_sno = $row['SNO']? ($row['SNO']+1):$sno;
	$notProcessed = [];
	 $item_arr = json_decode($po_arr);
	 $tp_arr = [];
foreach($item_arr as $abc=> $value){
 $sql_po ="select DISTINCT TP_NO from POPS_DISPATCH_ITEMS where PO_NO='$value'";
$stmt_po = sqlsrv_query($conn,$sql_po);
while($row = sqlsrv_fetch_array($stmt_po, SQLSRV_FETCH_ASSOC)){
$tp_arr[] = $row['TP_NO'];
}
}
$len=count($tp_arr);
	foreach($tp_arr as $tp_num){
	 $new_inv =  'CH'.$finyear.''.str_pad($new_sno,6,'0',STR_PAD_LEFT);
	
	$sql2 = "UPDATE POPS_DISPATCH_ITEMS 
	SET CHALLAN_S_NO = '$new_sno',
	SUPPLY_DATE='$startdate', STATUS=1, CHALLAN_NO='$new_inv' WHERE TP_NO = '$tp_num' and STATUS=0";
	
	 $stmt2 = sqlsrv_query($conn,$sql2);
	 if($stmt2 !== false){
		$count++;
		$new_sno++;
	 }
	 else
	 {
		$notProcessed[]=$tp_num;
	 }
	//  $new_sno++;
	}
	// exit;
	 if($count==$len){
		echo "Challan generated Successfully";
		
	 }
	 else{
		$notProcessed = implode(",",$notProcessed);
		echo "These TP ($notProcessed) were not processed";
	 }
}
else if (isset($_GET['print_challan']) )
{
	
   $sql = "select b.VEND_CODE,DEPARTMENT_NAME,b.VEND_NAME,[PO_NO],a.po_date,[TP_NO],a.STATUS,CHALLAN_NO,SUPPLY_DATE ,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX+((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX)*.01))*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount
   from POPS_DISPATCH_ITEMS a
   join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT

	  where status =1  and  b.DEPARTMENT in('DSIIDC','DCCWS','DTTDC','DSCSC')
   group by b.VEND_CODE,[PO_NO],[TP_NO],CHALLAN_NO,b.VEND_NAME,SUPPLY_DATE,a.po_date,STATUS,DEPARTMENT_NAME
    order by CHALLAN_NO";
	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' class='w3-checkbox' name='tp_challan_print[]' value='".$row['TP_NO']."' />";
		
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['CHALLAN_NO']."</td><td>".$row['SUPPLY_DATE']->format('d-m-Y')."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td>".$row['po_date']->format('d-m-Y')."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
	// print_r($data);
	echo "<tr></tr>";
	
}

else if (isset($_GET['print_challan_list']) )
{
	
   $sql = "select b.VEND_CODE,DEPARTMENT_NAME,b.VEND_NAME,[PO_NO],a.po_date,[TP_NO],a.STATUS,CHALLAN_NO,SUPPLY_DATE ,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX+((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX)*.01))*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount
   from POPS_DISPATCH_ITEMS a
   join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	  where status =2  and  b.DEPARTMENT in('DSIIDC','DCCWS','DTTDC','DSCSC')
   group by b.VEND_CODE,[PO_NO],[TP_NO],CHALLAN_NO,b.VEND_NAME,SUPPLY_DATE,a.po_date,STATUS,DEPARTMENT_NAME
    order by CHALLAN_NO desc ";
	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' data-case='".$row['case_QUANTITY']."' onclick='Check_po_list()' class='w3-checkbox' name='tp_challan[]' value='".$row['TP_NO']."' />";
		
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['CHALLAN_NO']."</td><td>".$row['SUPPLY_DATE']->format('d-m-Y')."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td>".$row['po_date']->format('d-m-Y')."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
	// print_r($data);
	echo "<tr></tr>";
	
}


else if (isset($_GET['print_challan_list_all']) )
{
	// echo "Here";
	// exit;
   $sql = "select b.VEND_CODE,DEPARTMENT_NAME,b.VEND_NAME,[PO_NO],a.po_date,[TP_NO],a.STATUS,CHALLAN_NO,SUPPLY_DATE ,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX+((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX)*.01))*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount
   from POPS_DISPATCH_ITEMS a
   join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	  where a.STATUS>=2 and a.STATUS<5 and  b.DEPARTMENT in('DSIIDC','DCCWS','DTTDC','DSCSC')
   group by b.VEND_CODE,[PO_NO],[TP_NO],CHALLAN_NO,b.VEND_NAME,SUPPLY_DATE,a.po_date,STATUS,DEPARTMENT_NAME
    order by CHALLAN_NO desc ";
	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' data-case='".$row['case_QUANTITY']."' onclick='Check_po_list()' class='w3-checkbox' name='tp_challan[]' value='".$row['TP_NO']."' />";
		$supp_date = $row['SUPPLY_DATE']?$row['SUPPLY_DATE']->format('d-m-Y'):'';
		
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['CHALLAN_NO']."</td><td>".$supp_date."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td>".$row['po_date']->format('d-m-Y')."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
	// print_r($data);
	echo "<tr></tr>";
	
}
else if (isset($_GET['cancel_challan_list']) )
{
   $sql = "select b.VEND_CODE,DEPARTMENT_NAME,b.VEND_NAME,[PO_NO],a.po_date,[TP_NO],a.STATUS,CHALLAN_NO,SUPPLY_DATE ,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX+((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX)*.01))*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount
   from POPS_DISPATCH_ITEMS a
   join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	  where status in (1,2,3,4)  and  b.DEPARTMENT in('DSIIDC','DCCWS','DTTDC','DSCSC')
   group by b.VEND_CODE,[PO_NO],[TP_NO],CHALLAN_NO,b.VEND_NAME,SUPPLY_DATE,a.po_date,STATUS,DEPARTMENT_NAME
    order by CHALLAN_NO desc ";
	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='button' style='cursor: pointer' class='w3-orange w3-padding-small' name='".$row['STATUS']."' value='cancel' onclick='cancel_challan(this.id,this.name)' id='".$row['TP_NO']."' />
		<input type='button' style='cursor: pointer' id='".$row['CHALLAN_NO']."' name='".$row['SUPPLY_DATE']->format('Y-m-d')."' class='w3-green w3-padding-small' value='Modify' onclick='showModal(this.id,this.name)'>";
		
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['CHALLAN_NO']."</td><td>".$row['SUPPLY_DATE']->format('d-m-Y')."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td>".$row['po_date']->format('d-m-Y')."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
	// print_r($data);
	echo "<tr></tr>";
	
}
else if (isset($_GET['list_item_challan']) )
{

   $sql = "select b.VEND_CODE,DEPARTMENT_NAME,b.VEND_NAME,[PO_NO],a.po_date,[TP_NO],a.STATUS,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX+((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX)*.01))*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount
   from POPS_DISPATCH_ITEMS a
   join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	  where b. DEPARTMENT in('DSIIDC','DCCWS','DTTDC','DSCSC') and status =0 
   group by b.VEND_CODE,[PO_NO],[TP_NO],INVOICE_NO,b.VEND_NAME,INVOICE_DATE,a.po_date,STATUS,DEPARTMENT_NAME";


	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' class='w3-checkbox' data-case='".$row['case_QUANTITY']."' onclick='Check_po_list()' name='tp_challan[]' value='".$row['TP_NO']."' />";
		
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td>".$row['po_date']->format('d-m-Y')."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
	// print_r($data);
	echo "<tr></tr>";
	
}

if(isset($_GET['fun']) && $_GET['fun']=='processChallanshop'){
	
	extract($_REQUEST);
	$item_arr = json_decode($po_arr);
	// var_dump($item_arr);
	// exit;

	// echo $startdate;
	// exit;
	$len = count($item_arr);
	// $item_string = "'".implode("','",$item_arr)."'";
	// print($item_string);
	// $sql="update POPS_DISPATCH_ITEMS set status=1 where"
	$sno=1;
	$count =0;
	$sql1 = "SELECT max(CHALLAN_S_NO) as SNO FROM POPS_DISPATCH_ITEMS";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$row = sqlsrv_fetch_array($stmt1);
	$new_sno = $row['SNO']? ($row['SNO']+1):$sno;
	$notProcessed = [];
//    $finyear = date('y').''.(date('y')+1);
	foreach($item_arr as $tp_num){
		
		$new_inv =  'CH'.$finyear.''.str_pad($new_sno,6,'0',STR_PAD_LEFT);

	$sql2 = "UPDATE POPS_DISPATCH_ITEMS 
	SET CHALLAN_S_NO = '$new_sno',
	SUPPLY_DATE='$startdate', STATUS=1, CHALLAN_NO='$new_inv' WHERE TP_NO = '$tp_num' and STATUS=0";
	 $stmt2 = sqlsrv_query($conn,$sql2);
	 if($stmt2!==false){
		$count++;
		$new_sno++;
	 }
	 else
	 {
		$notProcessed[]=$tp_num;
	 }
	//  $new_sno++;
	}
	// exit;
	 if($count==$len){
		echo "Challan generated Successfully";
		
	 }
	 else{
		$notProcessed = implode(",",$notProcessed);
		echo "These TP ($notProcessed) were not processed";
	 }
}
//---------------------------- hcr invoice start -------------------------------------------

if (isset($_GET['hcr_wise_invoice']) )
{

  $sql = "select  TP_NO,PO_NO,PO_DATE,DEPARTMENT,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount,cast(sum((EXCISE_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as excise 
from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE

where DEPARTMENT  not in('DSIIDC','DCCWS','DTTDC','DSCSC','Private') and status =0 
group by PO_NO,DEPARTMENT,PO_DATE,TP_NO";

  // exit;
	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' data-case='".$row['case_QUANTITY']."' onclick='Check_po_list()' class='w3-checkbox' name='tp_HCR[]' value='".$row['TP_NO']."' />";
		
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['PO_NO']."</td><td>".$row['PO_DATE']->format('d-m-Y')."</td><td class='mid-text' >".$row['DEPARTMENT']."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td  class='mid-text'>".$row['excise']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
	
	
}
if(isset($_GET['fun']) && $_GET['fun']=='cancel_challan'){
	// var_dump($_GET);
	// echo 'Hello';
	extract($_GET);
	// echo $tp_no;
	 $sql = "Select distinct CHALLAN_NO,INVOICE_NO,TP_NO from POPS_DISPATCH_ITEMS where TP_NO='$tp_no' and status in (1,2,3,4)";
	// exit;
	$stmt = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
		$challan = $row['CHALLAN_NO'];
		$invoice = $row['INVOICE_NO'];
	}
// 	echo $challan;
// 	echo $invoice;
// exit;
	if($status<3){
		$time = time();
		$new_tp = $tp_no.'_'.$time;
		   $sqlu = "UPDATE POPS_DISPATCH_ITEMS set STATUS=5,TP_NO='$new_tp',UPDATED_DATE=getdate() where CHALLAN_NO='$challan' and STATUS between 1 and 2";
		// exit;
		$stmtu = sqlsrv_query($conn,$sqlu);
		// var_dump($stmtu);
		// exit;
		if($stmtu != false){
		     $sql1 = "INSERT INTO [dbo].[POPS_DISPATCH_ITEMS]
								([VEND_CODE]
								,[PO_NO]
								,[PO_DATE]
								,[TP_NO]
								,[TP_DATE]
								,[BRAND_CODE]
								,[BRAND_NAME]
								,[SIZE_VALUE]
								,[PACK_SIZE]
								,[CASE_QUANTITY]
								,[BOTTLE_QUANTITY]
								,[WSP]
								,[EXCISE_DUTY]
								,[CUSTOM_DUTY]
								,[RETAIL_PROFIT]
								,[SALE_TAX]
								,[TCS]
								,[MRP]
								,[TOTAL_AMOUNT]
								,[CREATED_BY]
								,[CREATED_DATE]
								,[UPDATED_BY]
								,[UPDATED_DATE]
								,[STATUS]
							 )
					Select [VEND_CODE]
								,[PO_NO]
								,[PO_DATE]
								,'$tp_no'
								,[TP_DATE]
								,[BRAND_CODE]
								,[BRAND_NAME]
								,[SIZE_VALUE]
								,[PACK_SIZE]
								,[CASE_QUANTITY]
								,[BOTTLE_QUANTITY]
								,[WSP]
								,[EXCISE_DUTY]
								,[CUSTOM_DUTY]
								,[RETAIL_PROFIT]
								,[SALE_TAX]
								,[TCS]
								,[MRP]
								,[TOTAL_AMOUNT]
								,[CREATED_BY]
								,[CREATED_DATE]
								,[UPDATED_BY]
								,[UPDATED_DATE]
								,0 as  STATUS from POPS_DISPATCH_ITEMS where CHALLAN_NO='$challan' and STATUS =5 ";
								 // exit;
		 $stmt1 = sqlsrv_query($conn,$sql1);
		 if($stmt1 != false){
			echo "Challan Cancelled and updated!!";
		 }
		}
		 		 
		}
	else{
		 echo $sql2 = "Select * from POPS_DISPATCH_ITEMS where INVOICE_NO='$invoice'";
	}
}


if(isset($_POST['update_challan'])){

	extract($_POST);
   $sql="update  POPS_DISPATCH_ITEMS set SUPPLY_DATE='$challan_date'  where CHALLAN_NO='$challan_num' ";
 $stmt1 = sqlsrv_query($conn,$sql);
  if($stmt1 != false){
			header("Location:challan_cancel.php");
		 }
		 else{
        alart("data not update ");
        header("Location: challan_cancel.php");
		 }
}




?>