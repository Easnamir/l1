<?php 
session_start();
// var_dump($database);
// exit;
include 'includes/autoload.inc.php';
include 'includes/connect.php';
$user = $_SESSION["username"];
$COMPANY_id = $_SESSION['COMPANY_id'];
$CINV = $_SESSION['CINV'];

if(date('m')<4){
	$finyear = (date('y')-1).''.date('y');
}
else{
   $finyear = date('y').''.(date('y')+1);
}

 if(isset($_GET['Allbrand'])){
	$brand = $_GET['brand'];
	$sql = "SELECT DISTINCT BRAND_NAME from POPS_PRICE_MASTER  ORDER BY BRAND_NAME asc";

	$stmt = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){

		echo '<option value="'.$row['BRAND_NAME'].'">';
		// echo $row['BRAND_NAME'].'<br>';
	}

}

else if(isset($_GET['brandname']) && $_GET['fun']=='getBrandSizeAll'){
	$brandname = $_GET['brandname'];
	
	$sql = "SELECT SIZE_VALUE FROM POPS_PRICE_MASTER WHERE BRAND_NAME='".$brandname."' ORDER BY SIZE_VALUE DESC";
	$stmt = sqlsrv_query($conn,$sql);

	echo '<option value="0">Select Size</option>';
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		echo '<option value="'.$row['SIZE_VALUE'].'">'.$row['SIZE_VALUE'].'</option>';
	}
}
// else if(isset($_GET['brandname'])){
// 	$brandname = $_GET['brandname'];
	
// 	$sql = "SELECT SIZE_VALUE FROM POPS_PRICE_MASTER WHERE BRAND_NAME='$brandname' ORDER BY SIZE_VALUE DESC";
// 	$stmt = sqlsrv_query($conn,$sql);

// 	echo '<option value="0">Select Size</option>';
// 	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
// 		echo '<option value="'.$row['SIZE_VALUE'].'">'.$row['SIZE_VALUE'].'</option>';
// 	}
// }

if(isset($_GET['brandname']) && isset($_GET['brandsize'])){
	$brandName = $_GET['brandname'];
	$brandSize = $_GET['brandsize'];

	 $sql = "SELECT * FROM [POPS_PRICE_MASTER] WHERE  BRAND_NAME= '$brandName' AND SIZE_VALUE='$brandSize' 
   ";
  // exit;
  $json_data = array();

  $stmt = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$json_data[] = $row;
	}
	$resultsJSON = json_encode($json_data);
	print_r($resultsJSON);
}

















 if(isset($_GET['customer']) && $_GET['customer']=='all'){
	$customer = $_GET['customer'];
$COMPANY_id = $_SESSION['COMPANY_id'];
	$sql = "SELECT VEND_NAME as BUSINESS_NAME,VEND_CODE from POPS_VEND_DETAILS where DEPARTMENT in ('RESTAURANT','HOTEL','CLUB')";

	$stmt = sqlsrv_query($conn,$sql);
	//echo '<option value="">';
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
      
		echo '<option value="'.$row['BUSINESS_NAME'].'('.$row['VEND_CODE'].')">';
		// echo $row['BRAND_NAME'].'<br>';
	}
	
}

// else if(isset($_GET['customer'])){
// 	$customer = $_GET['customer'];
	
// 	$sql = "SELECT * from POPS_CUSTOMER_DETAILS WHERE BUSINESS_NAME='$customer'";

// 	$customer_details=array();
// 	$stmt = sqlsrv_query($conn,$sql);
// 	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){

// 		$customer_details = $row;
// 	}
// 	print_r(json_encode($customer_details));
// }

else if (isset($_GET['list_customer']) )
	{
   $sql = "select * from POPS_CUSTOMER_DETAILS
    where  COMPANY_DETAILS_FK='$COMPANY_id' and CUSTOMER_CODE!='' order by CUSTOMER_CODE";
	$stmt = sqlsrv_query($conn,$sql);
	//var_dump(sqlsrv_num_rows($stmt));
	$i=0;
	
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
	
		
       echo "<tr><td  class='mid-text'>". ++$i. "</td><td  class='mid-text'>".$row['CUSTOMER_CODE']."</td><td  class='mid-text'>".$row['COMPANY_NAME']."</td><td  class='mid-text' >".$row['BUSINESS_NAME']."</td><td  class='mid-text'>".$row['EMAIL']."</td><td  class='mid-text'>".$row['OWNER']."</td><td  class='mid-text'>".$row['CONTACT_NO']."</td><td  class='mid-text'>".$row['BUSINESS_ADDRESS']."</td><td  class='mid-text'>".$row['PIN_NO']."</td><td  class='mid-text'>".$row['TIN_NO']."</td><td  class='mid-text'>".$row['LICENCE_CODE']."</td><td  class='mid-text'>".$row['PAN_NO']."</td><td  class='mid-text'>".$row['DESCRIPTION']."</td><td>".(($row['CUSTOMER_STATUS']==2)?'Rejected':($row['CUSTOMER_STATUS']?'Approved':'Pending'))."</td><td class='mid-text point'><a href='view_customer.php?cust_id=".base64_encode($row['CUSTOMER_DETAILS_PK'])."'><i class='fa fa-edit'></i></a></td></tr>";

	}
	echo "<tr></tr>";
$_SESSION['customer']=serialize($data);
	}
	
else if(isset($_GET['fun']) && $_GET['fun']=='changeStatus'){
		$customer_pk =  $_GET['customer_pk'];
		$status = $_GET['status'];
		 $sql="UPDATE POPS_CUSTOMER_DETAILS SET CUSTOMER_STATUS='$status' WHERE CUSTOMER_DETAILS_PK='$customer_pk' ";
		$stmt = sqlsrv_query($conn,$sql);

		if($stmt != false){
			echo "Status Updated Successfully!!";
		}
		else{
			echo  "Something Went Wrong!!";
		}
	}
	else if (isset($_GET['list_shop']) )
	{
    $sql = "select * from POPS_VEND_DETAILS
     order by VEND_DETAILS_PK DESC";
	$stmt = sqlsrv_query($conn,$sql);
	//var_dump(sqlsrv_num_rows($stmt));
	$i=0;
	
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
	
		
       echo "<tr><td  class='mid-text'>". ++$i. "</td><td  class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text' >".$row['VEND_NAME']."</td><td  class='mid-text'>".$row['ExciseNO']."</td><td  class='mid-text'>".$row['VEND_ADDRESS']."</td><td>".$row['PIN_CODE']."</td><td  class='mid-text'>".$row['TIN']."</td><td  class='mid-text'>".$row['PAN_NO']."</td><td>".$row['GST_NO']."</td><td>".$row['FSSAI']."</td><td>".$row['DEPARTMENT']."</td><td>".$row['SALE_MANE']."</td><td class='mid-text point'><a href='shop_creation.php?id=".($row['VEND_DETAILS_PK'])."'><i class='fa fa-edit'></i></a></td></tr>";

	}
	echo "<tr></tr>";
      $_SESSION['Shop_List']=serialize($data);
	}
		else if (isset($_GET['list_sale']) )
	{
    $sql = "select * from POPS_SALE_MAN";
	$stmt = sqlsrv_query($conn,$sql);
	//var_dump(sqlsrv_num_rows($stmt));
	$i=0;
	
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
	
		
       echo "<tr><td  class='mid-text'>". ++$i. "</td><td  class='mid-text'>".$row['SALE_MAN_NAME']."</td><td  class='mid-text' >".$row['PHONE_NO']."</td><td  class='mid-text'>".$row['EMAIL']."</td><td  class='mid-text'>".$row['STATUS']."</td><td class='mid-text point'><a href='Sales_man_creation.php?id=".($row['POPS_SALE_MAN_PK'])."'><i class='fa fa-edit'></i></a></td></tr>";

	}
	echo "<tr></tr>";
      // $_SESSION['Shop_List']=serialize($data);
	}
	//-----------------------------list of party end--------------------------------------------------
	else if (isset($_GET['list_po']) )
	{


		$sql = "SELECT a.PO_NO,A.VEND_CODE,DEPARTMENT,isnull(b.VEND_NAME,'Vend not added') as VEND_NAME,b.GST_NO,b.TIN,b.PAN_NO ,a.TP_NO,sum(A.[CASE]) [CASE] ,sum(a.bottle) bottle,sum(((WSP+CUSTOM_DUTY)* bottle))AS BILL_AMOUNT,isnull(sum(CAST(EXCISE_PRICE*BOTTLE AS decimal(18,2))),0) AS EXCISE_DUTY FROM POPS_PO_create A
full JOIN POPS_VEND_DETAILS B ON A.VEND_CODE=B.VEND_CODE
full JOIN POPS_PRICE_MASTER C ON C.BRAND_CODE=A.BRAND_CODE where a.STATUS_CD=0 --and tp_no not in (

group by PO_NO,A.VEND_CODE,VEND_NAME,TP_NO,DEPARTMENT,b.GST_NO,b.TIN,b.PAN_NO ,a.TP_NO
order by PO_NO,DEPARTMENT";





	$stmt = sqlsrv_query($conn,$sql);
	//var_dump(sqlsrv_num_rows($stmt));
	$i=0;
	
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		// $data[] = $row;
		$warning='';
		$disabled='';
		if($row['VEND_NAME']=='Vend not added'){
			$warning= "class='w3-orange'";
			$disabled = 'disabled';
		}
		    elseif(($row['DEPARTMENT'] =='HOTEL') || ( $row['DEPARTMENT'] =='RESTAURANT')  ||( $row['DEPARTMENT'] =='CLUB')){

		    if( !$row['TIN']  || !$row['PAN_NO']){
			
			$disabled = 'disabled';
			if($CINV=='ABII'){
				$disabled='false';
			}
		}
		
	 }
	
       echo "<tr $warning><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text'>".$row['DEPARTMENT']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td  class='mid-text'>".$row['CASE']."</td><td  class='mid-text'>".$row['bottle']."</td><td  class='mid-text'>".$row['TP_NO']."</td><td  class='mid-text'>".$row['BILL_AMOUNT']."</td><td  class='mid-text'>".$row['EXCISE_DUTY']."</td><td  class='mid-text'><input type='checkbox' $disabled onclick='Check_po_list()' data-case='".$row['CASE']."' name='tp_no[]' value='".($row['TP_NO'])."'></td></tr>";

	}
	if($i>0){
	echo "";
	}
	else{
		echo "<tr><td colspan='11' style='text-align: center !important'>No data found</td></tr>";
	}
	}
	if(isset($_GET['fun']) && $_GET['fun'] == 'processPO'){
		// echo "Hello";
		// print_r($_POST);
		// exit;
		extract($_POST);
		$array_tp = explode(',',$tp_arr);
		// var_dump($array_tp);

		// exit;
		 $tp_all = "'".implode("','",($array_tp))."'";
// exit;
		$sqlc = "select BRAND_CODE from POPS_PO_create where TP_NO in ($tp_all) and BRAND_CODE not in (SELECT BRAND_CODE from POPS_PRICE_MASTER)";
		 $stmtc = sqlsrv_query($conn,$sqlc);
		//  exit;
		 $brand_list = [];
		 while ($row = sqlsrv_fetch_array($stmtc, SQLSRV_FETCH_ASSOC)){
			$brand_list[] = $row['BRAND_CODE'];
		 }
		 if(count($brand_list) > 0){
			$brand_all = implode(", ",$brand_list);
			echo "$brand_all brands is/are not added in your brand list. please add them to your brand list";
			exit;
		 }
		 $sql = "INSERT INTO [dbo].[POPS_DISPATCH_ITEMS]
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
				   ,[STATUS])
		
			   SELECT A.VEND_CODE,PO_NO AS PO_NO,CONVERT (DATE,(SUBSTRING(PO_NO,7,2))+(SUBSTRING(PO_NO,5,2)) +(SUBSTRING(PO_NO,3,2)) ) AS PO_DATE
		,TP_NO,CONVERT (DATE,(SUBSTRING(TP_NO,8,2))+(SUBSTRING(TP_NO,6,2)) +(SUBSTRING(TP_NO,4,2)) ) AS TP_DATE ,C.BRAND_CODE,BRAND_NAME,SIZE_VALUE,PACK_SIZE
		,[CASE],BOTTLE,WSP,EXCISE_PRICE AS EXCISE_DUTY,CUSTOM_DUTY,RETAIL_PROFIT,SALE_TAX,(WSP+CUSTOM_DUTY+EXCISE_PRICE+SALE_TAX)*.01 AS TCS ,MRP,(WSP+CUSTOM_DUTY+EXCISE_PRICE+SALE_TAX+((WSP+CUSTOM_DUTY+EXCISE_PRICE+SALE_TAX)*.01)) AS TOTAL_AMOUNT
		,'$user' AS CREATED_BY,GETDATE() AS CREATED_DATE,'$user' AS UPDATED_BY,GETDATE() AS UPDATED_DATE,0
		
		FROM POPS_PO_create A
		JOIN POPS_VEND_DETAILS B ON A.VEND_CODE=B.VEND_CODE
		
		JOIN POPS_PRICE_MASTER C ON C.BRAND_CODE=A.BRAND_CODE where a.STATUS_CD=0  and tp_no in ($tp_all)	
		order by tp_no
		
		update POPS_PO_create set STATUS_CD=1  where tp_no in ($tp_all)";
		$stml = sqlsrv_query($conn,$sql);
		if ($stml === false) {
			echo "This PO has already been processed!!";
		}
		else{
			echo "PO processed successfully";
		}
	}
///po page end----------------------------------------------------------------------




		//-----------------------------po panding page--------------------------------------------------
	else if (isset($_GET['list__panding_po']) )
	{
		$sql = "SELECT a.PO_NO,A.VEND_CODE,DEPARTMENT,isnull(b.VEND_NAME,'Vend not added') as VEND_NAME,sum(A.[CASE]) [CASE] ,sum(a.bottle) bottle,isnull (cast(sum((EXCISE_PRICE+WSP+CUSTOM_DUTY+sale_tax+((EXCISE_PRICE+WSP+CUSTOM_DUTY+sale_tax)*0.01))* bottle)as decimal(18,2)),0)AS BILL_AMOUNT,isnull(sum(CAST(EXCISE_PRICE*BOTTLE AS decimal(18,2))),0) AS EXCISE_DUTY FROM POPS_PANDING_PO_CREATE A
full JOIN POPS_VEND_DETAILS B ON A.VEND_CODE=B.VEND_CODE
full JOIN POPS_PRICE_MASTER C ON C.BRAND_CODE=A.BRAND_CODE where a.STATUS_CD=0 and   b.DEPARTMENT not  in('DSIIDC','DCCWS','DTTDC','DSCSC')   --and tp_no not in (

group by PO_NO,A.VEND_CODE,VEND_NAME,DEPARTMENT";





	$stmt = sqlsrv_query($conn,$sql);
	//var_dump(sqlsrv_num_rows($stmt));
	$i=0;
	
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$warning='';
		$disabled='';
		if($row['VEND_NAME']=='Vend not added'){
			$warning= "class='w3-orange'";
			$disabled = 'disabled';
		}
	
	
       echo "<tr $warning><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text'>".$row['DEPARTMENT']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td  class='mid-text'>".$row['CASE']."</td><td  class='mid-text'>".$row['bottle']."</td><td  class='mid-text'>".$row['BILL_AMOUNT']."</td><td  class='mid-text'>".$row['EXCISE_DUTY']."</td></tr>";

	}
	if($i>0){
	echo "";
	}
	else{
		echo "<tr><td colspan='10' style='text-align: center !important'>No data found</td></tr>";
	}
	// $_SESSION['PO_pending_list']=serialize($data);
	
	}
	if(isset($_GET['fun']) && $_GET['fun'] == 'pandingprocessPO'){
		// var_dump($_GET);

		// exit;
		extract($_REQUEST);
		// exit;
		 $tp_all = "'".implode("','",json_decode($tp_num))."'";
// exit;
		$sqlc = "select BRAND_CODE from  POPS_PANDING_PO_CREATE where PO_NO in ($tp_all) and BRAND_CODE not in (SELECT BRAND_CODE from POPS_PRICE_MASTER)";
		 $stmtc = sqlsrv_query($conn,$sqlc);
		 $brand_list = [];
		 while ($row = sqlsrv_fetch_array($stmtc, SQLSRV_FETCH_ASSOC)){
			$brand_list[] = $row['BRAND_CODE'];
		 }
		 if(count($brand_list) > 0){
			$brand_all = implode(", ",$brand_list);
			echo "$brand_all brands is/are not added in your brand list. please add them to your brand list";
			exit;
		 }
		 $sql = "INSERT INTO [dbo].[POPS_DISPATCH_ITEMS]
				   ([VEND_CODE]
				   ,[SUPPLY_DATE]
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
				   ,[STATUS])
		
			   SELECT A.VEND_CODE,CAST(GETDATE() AS DATE) AS SUPPLY_DATE,PO_NO AS PO_NO,CONVERT (DATE,(SUBSTRING(PO_NO,7,2))+(SUBSTRING(PO_NO,5,2)) +(SUBSTRING(PO_NO,3,2)) ) AS PO_DATE
		,TP_NO,CONVERT (DATE,(SUBSTRING(TP_NO,8,2))+(SUBSTRING(TP_NO,6,2)) +(SUBSTRING(TP_NO,4,2)) ) AS TP_DATE ,C.BRAND_CODE,BRAND_NAME,SIZE_VALUE,PACK_SIZE
		,[CASE],BOTTLE,WSP,EXCISE_PRICE AS EXCISE_DUTY,CUSTOM_DUTY,RETAIL_PROFIT,SALE_TAX,(WSP+CUSTOM_DUTY+EXCISE_PRICE+SALE_TAX)*.01 AS TCS ,MRP,(WSP+CUSTOM_DUTY+EXCISE_PRICE+SALE_TAX+((WSP+CUSTOM_DUTY+EXCISE_PRICE+SALE_TAX)*.01)) AS TOTAL_AMOUNT
		,'$user' AS CREATED_BY,GETDATE() AS CREATED_DATE,'$user' AS UPDATED_BY,GETDATE() AS UPDATED_DATE,0
		
		FROM POPS_PO_create A
		JOIN POPS_VEND_DETAILS B ON A.VEND_CODE=B.VEND_CODE
		
		JOIN POPS_PRICE_MASTER C ON C.BRAND_CODE=A.BRAND_CODE where a.STATUS_CD=0  and tp_no in ($tp_all)	
		order by tp_no
		
		update POPS_PO_create set STATUS_CD=1  where tp_no in ($tp_all)";
		$stml = sqlsrv_query($conn,$sql);
		if ($stml === false) {
			echo "This PO has already been processed!!";
		}
		else{
			echo "PO processed successfully";
		}
	}
///panding po page end----------------------------------------------------------------------

else if (isset($_GET['print_challan']) )
{
	
   $sql = "select b.VEND_CODE,b.DEPARTMENT,b.VEND_NAME,[PO_NO],a.po_date,[TP_NO],a.STATUS,isnull (CHALLAN_NO,'pending') INVOICE_NO,isnull (SUPPLY_DATE,'')INVOICE_DATE ,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX+((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX)*.01))*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount
   from POPS_DISPATCH_ITEMS a
   join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE
	  where status =1 and b.DEPARTMENT='Corporation'
   group by b.VEND_CODE,[PO_NO],[TP_NO],CHALLAN_NO,b.VEND_NAME,SUPPLY_DATE,a.po_date,STATUS,DEPARTMENT";
	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' class='w3-checkbox' name='tp_challan[]' value='".$row['TP_NO']."' />";
		
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td>".($row['INVOICE_DATE']->format('Y-m-d')=='1900-01-01'?'NA':($row['INVOICE_DATE']->format('d-m-Y')))."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['DEPARTMENT']."</td><td>".$row['po_date']->format('d-m-Y')."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
	// print_r($data);
	echo "<tr></tr>";
	
}


else if (isset($_GET['list_item']) )
	{


   $sql = "select b.VEND_CODE,b.VEND_NAME,[PO_NO],a.po_date,[TP_NO],a.STATUS,isnull (INVOICE_NO,'pending') INVOICE_NO,isnull (INVOICE_DATE,'')INVOICE_DATE ,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX+((WSP+CUSTOM_DUTY+EXCISE_DUTY+SALE_TAX)*.01))*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE
	where status <4 and DEPARTMENT='Private'
 group by b.VEND_CODE,[PO_NO],[TP_NO],INVOICE_NO,b.VEND_NAME,INVOICE_DATE,a.po_date,STATUS ";
	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = $row['STATUS']==0?"<button type='button' id='".$row['TP_NO']."' onclick='GenerateInvoice(this.id)' class='w3-button w3-red'>Generate</button>":"<button type='button' id='".$row['TP_NO']."' onclick='printInvoice(this.id)' class='w3-button w3-red'>Print</button>";
		// $button='hello';


		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td>".($row['INVOICE_DATE']->format('Y-m-d')=='1900-01-01'?'NA':($row['INVOICE_DATE']->format('d-m-Y')))."</td><td class='mid-text' >".$row['PO_NO']."</td><td>".$row['po_date']->format('d-m-Y')."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td>".$button."</td></tr>";


		// <input type='checkbox' onclick='Check_po_list()' name='tp_no[]' value='".($row['TP_NO'])."'>


	
			}
	// print_r($data);
	
}
else if (isset($_GET['list_item_gov']) )
	{


   $sql = "select a.VEND_CODE,INVOICE_DATE,b.VEND_NAME,INVOICE_NO,cast(sum((WSP+CUSTOM_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as WSP,cast(sum((EXCISE_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as excise ,cast(sum((WSP+CUSTOM_DUTY+EXCISE_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount
   from POPS_DISPATCH_ITEMS a
   join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE

    and status =3 and b.DEPARTMENT='Corporation' 
    group by INVOICE_NO,a.VEND_CODE,b.VEND_NAME,INVOICE_DATE ";
	$stmt1 = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		


		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td>".($row['INVOICE_DATE']->format('Y-m-d')=='1900-01-01'?'NA':($row['INVOICE_DATE']->format('d-m-Y')))."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td  class='mid-text'>".$row['WSP']."</td><td  class='mid-text'>".$row['excise']."</td><td  class='mid-text'>".$row['bill_amount']."</td><td><button type='button' id='".$row['INVOICE_NO']."' onclick='printInvoice1(this.id)' class='w3-button w3-red'>Print</button></td></tr>";


		
	
			}
	// print_r($data);
	
}

if(isset($_GET['fun']) && $_GET['fun'] =='GenerateInvoice'){
	$tp_num =  $_GET['tp_num'];
	$sno=1;
	$sql1 = "SELECT max(S_NO) as SNO FROM POPS_DISPATCH_ITEMS";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$row = sqlsrv_fetch_array($stmt1);
	$new_sno = $row['SNO']? ($row['SNO']+1):$sno;
//    $finyear = date('y').''.(date('y')+1);
	$new_inv =  $CINV.''.$finyear.''.str_pad($new_sno,6,'0',STR_PAD_LEFT);

	$sql2 = "UPDATE POPS_DISPATCH_ITEMS 
	SET S_NO = '$new_sno',
	 INVOICE_DATE=GETDATE(), STATUS=3, INVOICE_NO='$new_inv' WHERE TP_NO = '$tp_num'";
	 $stmt2 = sqlsrv_query($conn,$sql2);
	 if($stmt2==false){
		echo "Something Went";
	 }
	 else{
		echo "Invoice generated Successfully";
	 }

}

if(isset($_GET['fun']) && $_GET['fun'] =='cancelInvoice'){
	 $po_num =  $_GET['po_num'];
	
$sql2 = "UPDATE POPS_DISPATCH_ITEMS 
	SET STATUS=5 WHERE PO_NO = '$po_num'";
	 // exit;
	 $stmt2 = sqlsrv_query($conn,$sql2);
	 if($stmt2==false){
		echo "Something Went";
	 }
	 else{
		echo "Invoice Cancel Successfully";
	 }

}




if(isset($_GET['fun']) && $_GET['fun']=='printInvoice'){
	 $tp_num =$_GET['tp_num'];
	 $sql = "UPDATE POPS_DISPATCH_ITEMS SET STATUS=4 where TP_NO='$tp_num'";
	 $stmt2 = sqlsrv_query($conn,$sql);
	 if($stmt2==false){
		echo "Something Went";
	 }
	 else{
		echo "Invoice generated Successfully";
	 }
}
if(isset($_GET['fun']) && $_GET['fun']=='checkcase'){
	 $type =$_GET['type'];

	 $sql="select BRAND_CODE,BRAND_NAME,SIZE_VALUE,PACK_SIZE,(wsp+CUSTOM_DUTY) as WSP,EXCISE_PRICE,SALE_TAX ,RETAIL_PROFIT, MRP,SUPP_NAME from POPS_PRICE_MASTER order by brand_name";
 $stmt1 = sqlsrv_query($conn,$sql);
	 if($type=='bottle'){
      echo " <thead><tr class='w3-center w3-red'>
									<th width='5%'>S.No</th><th>Brand Code</th><th>Brand Name</th><th>Size</th><th>Units</th>
									<th>wsp</th><th>Excise Revenue</th><th>Vat</th><th>Margin</th><th>vat OF Margin</th><th>MRP</th><th>Supp Name</th>
								</tr></thead>";
								// $i=0;
									while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;

								echo "<tr><td  class='mid-text'>". ++$i. "</td><td  class='mid-text'>".$row['BRAND_CODE']."</td><td class='mid-text' >".$row['BRAND_NAME']."</td><td class='mid-text' >".$row['SIZE_VALUE']."</td><td class='mid-text' >".$row['PACK_SIZE']."</td><td class='mid-text' >".$row['WSP']."</td><td class='mid-text' >".$row['EXCISE_PRICE']."</td><td class='mid-text' >".$row['SALE_TAX']."</td><td class='mid-text' >".$row['RETAIL_PROFIT']."</td><td class='mid-text' >".($row['RETAIL_PROFIT']*0.25)."</td><td  class='mid-text'>".$row['MRP']."</td><td  class='mid-text'>".$row['SUPP_NAME']."</td></tr>";

}
// print_r($data);

	 }
	 else{

 echo "  <tr class='w3-center w3-red'>
									<th width='5%'>S.No</th><th>Brand Code</th><th>Brand Name</th><th>Size</th><th>Units</th>
									<th>wsp</th><th>Excise Revenue</th><th>Vat</th><th>Margin</th><th>vat OF Margin</th><th>MRP</th> <th>Supp Name</th>
								</tr>";
									$i=0;
									while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
echo "<tr><td  class='mid-text'>". ++$i. "</td><td  class='mid-text'>".$row['BRAND_CODE']."</td><td class='mid-text' >".$row['BRAND_NAME']."</td><td class='mid-text' >".$row['SIZE_VALUE']."</td><td class='mid-text' >".$row['PACK_SIZE']."</td><td class='mid-text' >".$row['WSP']*$row['PACK_SIZE']."</td><td class='mid-text' >".$row['EXCISE_PRICE']*$row['PACK_SIZE']."</td><td class='mid-text' >".$row['SALE_TAX']*$row['PACK_SIZE']."</td><td class='mid-text' >".$row['RETAIL_PROFIT']*$row['PACK_SIZE']."</td><td class='mid-text' >".($row['RETAIL_PROFIT']*0.25)*$row['PACK_SIZE']."</td><td  class='mid-text'>".$row['MRP']."</td><td  class='mid-text'>".$row['SUPP_NAME']."</td></tr>";

	 }
	}

 $_SESSION['type']=serialize($type);
}

elseif (isset($_GET['createinvoice'])){
	$todate=$_GET['todate'];
	$fromdate=$_GET['fromdate'];
	$vend_id=$_GET['vend_id'];

	  $sql2=" select b.VEND_CODE,b.DEPARTMENT,b.VEND_NAME,[PO_NO],[TP_NO],CHALLAN_NO,SUPPLY_DATE,cast(sum((WSP+CUSTOM_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as WSP,cast(sum((EXCISE_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as excise ,cast(sum((WSP+CUSTOM_DUTY+EXCISE_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as bill_amount
   from POPS_DISPATCH_ITEMS a
   join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE
   where SUPPLY_DATE between '$fromdate' and'$todate' and a.VEND_CODE='$vend_id'
    and status =2 and b.DEPARTMENT='Corporation' 
    group by b.VEND_CODE,[PO_NO],[TP_NO],CHALLAN_NO,b.VEND_NAME,SUPPLY_DATE,DEPARTMENT";
	 $stmt2=sqlsrv_query($conn,$sql2);
$i=0;
$sno=1;
	$sql1 = "SELECT max(S_NO) as SNO FROM POPS_DISPATCH_ITEMS";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$row = sqlsrv_fetch_array($stmt1);
	$new_sno = $row['SNO']? ($row['SNO']+1):$sno;
//    $finyear = date('y').''.(date('y')+1);
	$new_inv =  $CINV.''.$finyear.''.str_pad($new_sno,6,'0',STR_PAD_LEFT);
	while($row = sqlsrv_fetch_array($stmt2, SQLSRV_FETCH_ASSOC)){
		$data[] = $row['CHALLAN_NO'];

}
if(count($data)>0){
$challans = "'".implode("','",$data)."'";
  $sql = "UPDATE POPS_DISPATCH_ITEMS 
	SET S_NO = '$new_sno',
	 INVOICE_DATE=GETDATE(), STATUS=3, INVOICE_NO='$new_inv' WHERE CHALLAN_NO in ($challans)";
	 $stmt3 = sqlsrv_query($conn,$sql);
if($stmt3==false){
		echo "Something Went";
	 }
	 else{
		echo "Invoice generated Successfully";
	 }
}
else{
	echo "No Challan available";
}
}
sqlsrv_close( $conn );
 ?>