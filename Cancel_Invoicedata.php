<?php 
session_start();

$user = $_SESSION["username"];
$COMPANY_id = $_SESSION['COMPANY_id'];
$CINV = $_SESSION['CINV'];
include 'includes/autoload.inc.php';
include 'includes/connect.php';


 if (isset($_GET['Cancel_Invoice']) )
	
	{
 
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];

 $sql = "select isnull(DEPARTMENT_NAME,B.DEPARTMENT) as DEPARTMENT_NAME,INVOICE_NO,INVOICE_DATE,SUM(CASE_QUANTITY) as CASE_QUANTITY, 
sum((wsp+CUSTOM_DUTY) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise,status 
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE  left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	where status in (4,3) and INVOICE_DATE between '$startdate' and '$enddate' 
 group by INVOICE_NO,INVOICE_DATE,DEPARTMENT_NAME,b.DEPARTMENT,status ";

	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<button  name='".$row['status']."'  id='".$row['INVOICE_NO']."' onclick='cancel_invoice(this.id,this.name)' class='w3-button w3-red'>Cancel </button>";
		  
    
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td>".($row['INVOICE_DATE']->format('Y-m-d')=='1900-01-01'?'NA':($row['INVOICE_DATE']->format('d-m-Y')))."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text'>".$button."</td></tr>";

     
			}
			if($i<1){
			echo "<tr><td colspan='7' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}
	// print_r($data);

			
	
}


if(isset($_GET['fun']) && $_GET['fun']=='invoicecancel'){
	// var_dump($_GET);
extract($_GET);
	// echo $tp_no;
	  $sql = " Select distinct INVOICE_NO,TP_NO,DEPARTMENT   from POPS_DISPATCH_ITEMS a
join POPS_VEND_DETAILS b on a.VEND_CODE=b.VEND_CODE where INVOICE_NO='$invoice' and status in (3,4)";
	// exit;
	$stmt = sqlsrv_query($conn,$sql);
	while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
		$tp_no = $row['TP_NO'];
		$invoice = $row['INVOICE_NO'];

		$time = time();
		$new_tp = $row['TP_NO'].'_'.$time;
		  $sqlu = "UPDATE POPS_DISPATCH_ITEMS set STATUS=6,TP_NO='$new_tp',UPDATED_DATE=getdate() where TP_NO='$tp_no'";
		// exit;
		$stmtu = sqlsrv_query($conn,$sqlu);
		    
          $depertment= array('DSIIDC','DCCWS','DTTDC','DSCSC');
					// print_r($depertment);
					// exit;
          if(in_array($row['DEPARTMENT'],$depertment)){
         	$staus=2;

          }
					else{
						$staus=0;
					}
          

		if($stmtu != false){
		   $sql1 = "INSERT INTO [dbo].[POPS_DISPATCH_ITEMS]
								([VEND_CODE]
								,[PO_NO]
								,[SUPPLY_DATE]
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
								,[CHALLAN_NO]
								
								,[CHALLAN_S_NO]
							 )
					Select [VEND_CODE]
								,[PO_NO]
								,[SUPPLY_DATE]
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
								,getdate()
								,'$staus'
								,[CHALLAN_NO]
								
								,[CHALLAN_S_NO]
								 from POPS_DISPATCH_ITEMS where TP_NO='$new_tp'";
								//  exit;
		 $stmt1 = sqlsrv_query($conn,$sql1);

		}
	}	

	echo "Invoice Cancelled and updated";	
}




































?>
