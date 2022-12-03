<?php 
session_start();

$user = $_SESSION["username"];
$COMPANY_id = $_SESSION['COMPANY_id'];
$CINV = $_SESSION['CINV'];
 $notcs = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited','CLUB'];
 $noecise = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited'];
 $hcr = ['CLUB','HOTEL','RESTAURANT'];
include 'includes/autoload.inc.php';
include 'includes/connect.php';
if(date('m')<4){
	$finyear = (date('y')-1).''.date('y');
}
else{
   $finyear = date('y').''.(date('y')+1);
}


if(isset($_GET['tpinvoice'])){
	 $Department=$_GET['Department'];
$Submission=$_GET['Submission'];
$startdate=$_GET['startdate'];
$enddate=$_GET['enddate'];

$sql_tp="select PO_NO,DEPARTMENT_NAME,a.CHALLAN_NO,TP_NO,VEND_NAME,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as wsp,cast(sum((EXCISE_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as excise  from POPS_DISPATCH_ITEMS a
join POPS_VEND_DETAILS  b on a.VEND_CODE=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
where  b.DEPARTMENT ='$Department' and TP_date between '$startdate' and '$enddate' and status =2 
group by PO_NO,DEPARTMENT_NAME,a.CHALLAN_NO,TP_NO,VEND_NAME";

$stmt1 = sqlsrv_query($conn,$sql_tp);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' class='w3-checkbox' data-case='".$row['case_QUANTITY']."' onclick='Check_po_list()'  name='tp_inv[]' value='".$row['TP_NO']."' />";	
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['CHALLAN_NO']."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['wsp']."</td><td  class='mid-text'>".$row['excise']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
}
if(isset($_GET['fun']) && $_GET['fun']=='processinvoicetp'){
	extract($_REQUEST);
	$Submission=$_GET['Submission'];
	$sno=1;
	$count =0;
	$sql1 = "SELECT max(S_NO ) as SNO FROM POPS_DISPATCH_ITEMS";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$row = sqlsrv_fetch_array($stmt1);
	 $new_sno = $row['SNO']? ($row['SNO']+1):$sno;
	$notProcessed = [];
	// var_dump($item_str);

	 $item_arr = json_decode($item_str);
	 $tp_arr = [];

	foreach($item_arr as $tp_num){
		
$new_inv =  $CINV.''.$finyear.''.str_pad($new_sno,6,'0',STR_PAD_LEFT);
	$sql2 = "UPDATE POPS_DISPATCH_ITEMS 
	SET S_NO = '$new_sno',
	 INVOICE_DATE='$Submission', STATUS=3, INVOICE_NO='$new_inv' WHERE TP_NO = '$tp_num' and STATUS=2";

	 $stmt2 = sqlsrv_query($conn,$sql2);
	 }
	 if($stmt2==false){
		echo "Something Went";
	 }
	 else{
		echo "Invoice generated Successfully";
	 }
	
	
	
}
	if(isset($_GET['Dept'])){

		 $Department=$_GET['Department'];


$sql1 = "select DISTINCT PO_NO from POPS_DISPATCH_ITEMS a
join POPS_VEND_DETAILS  b on a.VEND_CODE=b.VEND_CODE
		where DEPARTMENT ='$Department'and status =2";
	     $stmt1 = sqlsrv_query($conn, $sql1);
	     echo "<option value=''>Select PO</option>";
	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		//$data[] = $row;
		$po = $row['PO_NO'];
		echo "<option value='$po'>$po</option>";
	}
         

	}

	if(isset($_GET['lotinvoice'])){
	 $Department=$_GET['Department'];
$Submission=$_GET['Submission'];
$po=$_GET['po'];

$sql_tp="select PO_NO,DEPARTMENT_NAME,a.CHALLAN_NO,TP_NO,VEND_NAME,sum(case_QUANTITY)case_QUANTITY,cast(sum((WSP+CUSTOM_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as wsp,cast(sum((EXCISE_DUTY)*BOTTLE_QUANTITY) as decimal(10,2)) as excise  from POPS_DISPATCH_ITEMS a
join POPS_VEND_DETAILS  b on a.VEND_CODE=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
where b.DEPARTMENT ='$Department' and PO_NO='$po' and status =2 
group by PO_NO,DEPARTMENT_NAME,a.CHALLAN_NO,TP_NO,VEND_NAME";

$stmt1 = sqlsrv_query($conn,$sql_tp);
	// var_dump(sqlsrv_num_rows($stmt1));
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' class='w3-checkbox' data-case='".$row['case_QUANTITY']."' onclick='Check_po_list()'  name='tp_inv[]'  value='".$row['TP_NO']."' />";
		
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['CHALLAN_NO']."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['case_QUANTITY']."</td><td  class='mid-text'>".$row['wsp']."</td><td  class='mid-text'>".$row['excise']."</td><td  class='mid-text'>".$button."</td></tr>";
			}
}

else if (isset($_GET['invoice']) )
	
	{
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];
	$to_sno=$_GET['to_sno'];
  $from_sno=$_GET['from_sno'];

 $sql = "select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME,INVOICE_DATE,INVOICE_NO,SUM(CASE_QUANTITY) as CASE_QUANTITY,
sum((a.wsp) *BOTTLE_QUANTITY) as wsp ,sum(a.EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as CUSTOM_DUTY ,d.LIQUOR_TYPE_CD
 from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
 join POPS_PRICE_MASTER d on a.BRAND_CODE = d.BRAND_CODE 
	where status =4 and  b.DEPARTMENT ='$Department' and S_NO between  '$from_sno' and '$to_sno'
  group by INVOICE_NO,DEPARTMENT_NAME,b.DEPARTMENT,d.LIQUOR_TYPE_CD,INVOICE_DATE
order by INVOICE_NO ";

	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

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
	// echo $row['excise'];
	// exit;
	$total=$cost+$vat+$tcs-$excise_rev;  
	if(in_array($row['DEPARTMENT_NAME'],$noecise) && $row['LIQUOR_TYPE_CD'] == '171'){
		$total=$cost+$vat+$tcs-$excise_rev-$row['excise']; 
 }
		$data[] = $row;
		$button = "<input type='checkbox' onclick='checkAllChecked(this.name)' name='tp_invoice[]' id='".$row['INVOICE_NO']."'>&nbsp;&nbsp; <i class='fa fa-file-excel-o' title='".$row['INVOICE_NO']."' onclick='printInvoice2(this.title)' style='font-size:14px'></i>";
		  // $cost= ($row['excise']+$row['wsp']);
		  // $vat=$cost*.25;
		  // $tcs=0;
		  // $total=$cost+$vat+$tcs;
    
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td>".($row['INVOICE_DATE']->format('Y-m-d')=='1900-01-01'?'NA':($row['INVOICE_DATE']->format('d-m-Y')))."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".($row['CUSTOM_DUTY'])."</td><td class='mid-text' >".$row['excise']."</td><td class='mid-text' >".round($vat,2)."</td><td class='mid-text' >".round($tcs,2)."</td><td class='mid-text' >".round($total,2)."</td><td class='mid-text'>".$button."</td></tr>";

     
			}
			if($i<1){
			echo "<tr><td colspan='11' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}
	// print_r($data);

			
	
}

else if (isset($_GET['Show_invoice_list']) )
	
	{


$sql = "select DEPARTMENT_NAME,INVOICE_NO,INVOICE_DATE,PO_NO,SUM(CASE_QUANTITY) as CASE_QUANTITY, 
sum((wsp+CUSTOM_DUTY) *BOTTLE_QUANTITY) as wsp ,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as excise 
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	where status =3 and  b.DEPARTMENT in('DSIIDC','DCCWS','DTTDC','DSCSC') 
 group by [PO_NO],INVOICE_NO,INVOICE_DATE,DEPARTMENT_NAME ";

	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<button id='".$row['INVOICE_NO']."' onclick='printInvoice(this.id)' class='w3-button w3-red'> Print </button>";
		  $cost= ($row['excise']+$row['wsp']);
		  $vat=$cost*.25;
		  $tcs=0;
		  $total=$cost+$vat+$tcs;
    
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td>".($row['INVOICE_DATE']->format('Y-m-d')=='1900-01-01'?'NA':($row['INVOICE_DATE']->format('d-m-Y')))."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".$row['excise']."</td><td class='mid-text' >".$vat."</td><td class='mid-text' >".$total."</td><td class='mid-text'>".$button."</td></tr>";

     
			}
			if($i<1){
			echo "<tr><td colspan='11' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}
	// print_r($data);

			
	
}

else if (isset($_GET['invoice121']) )	{

	 $invoice=$_GET['invoice121'];

	  $sql2 = "UPDATE POPS_DISPATCH_ITEMS 
	SET STATUS=4 WHERE INVOICE_NO = '$invoice' and STATUS=3";

	 $stmt2 = sqlsrv_query($conn,$sql2);

}

if(isset($_GET['fun']) && $_GET['fun']=='HCR'){
	extract($_GET);
	// var_dump($_GET);
	// exit;
	$sno=1;
	$count =0;
	$sql1 = "SELECT max(S_NO ) as SNO FROM POPS_DISPATCH_ITEMS";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$row = sqlsrv_fetch_array($stmt1);
	 $new_sno = $row['SNO']? ($row['SNO']+1):$sno;
	$notProcessed = [];
// 	var_dump($item_str);
// exit;
	 $item_arr = json_decode($item_str);
	 $tp_arr = [];

	foreach($item_arr as $tp_num){
$new_inv =  $CINV.''.$finyear.''.str_pad($new_sno,6,'0',STR_PAD_LEFT);
	$sql2 = "UPDATE POPS_DISPATCH_ITEMS 
	SET S_NO = '$new_sno',
	 INVOICE_DATE='$startdate',SUPPLY_DATE='$startdate', STATUS=3, INVOICE_NO='$new_inv' WHERE TP_NO = '$tp_num' and STATUS=0";

	 $stmt2 = sqlsrv_query($conn,$sql2);
	 $new_sno++;
	 }
	 if($stmt2==false){
		echo "Something Went";
	 }
	 else{
		echo "Invoice generated Successfully";
	 }
	
	
	
}

else if (isset($_GET['print_hcrinvoice']) )
	
	{


$sql = "select TP_NO,a.VEND_CODE,VEND_NAME,DEPARTMENT,INVOICE_NO,INVOICE_DATE,PO_NO,SUM(CASE_QUANTITY) as CASE_QUANTITY, LIQUOR_TYPE_CD,
sum((a.wsp+a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as wsp ,sum(a.EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.wsp) *BOTTLE_QUANTITY) as wsp1
 from POPS_DISPATCH_ITEMS a
 join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE  join POPS_PRICE_MASTER p on  a.BRAND_CODE=p.BRAND_CODE
	where status =3 and  b.DEPARTMENT in('RESTAURANT','HOTEL','CLUB') 
 group by [PO_NO],INVOICE_NO,INVOICE_DATE,DEPARTMENT, a.VEND_CODE,VEND_NAME,TP_NO,LIQUOR_TYPE_CD ";

	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<button id='".$row['TP_NO']."' onclick='printInvoicehcr(this.id)' class='w3-button w3-red'> Print </button>";
		  $cost= ($row['excise']+$row['wsp']);

       if($row['LIQUOR_TYPE_CD']=='171'){
        
        if ($row['DEPARTMENT'] == 'HOTEL') {
        	$wsp_retrun=($row['wsp1']*.30);
        }
       else {
       	$wsp_retrun=($row['wsp1']*.20);
       }

}
else{
	$wsp_retrun=0;
}
		  $vat=($cost+$wsp_retrun)*.25;

		  $vat=($cost+$wsp_retrun)*.25;
        if($row['DEPARTMENT'] == 'CLUB'){
        	 $tcs=0;
        }
        else{
        	$tcs=($cost+$vat)*0.01;
        }

		  
		  $total=$cost+$vat+$tcs;
    
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td>".($row['INVOICE_DATE']->format('Y-m-d')=='1900-01-01'?'NA':($row['INVOICE_DATE']->format('d-m-Y')))."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$row['DEPARTMENT']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".$row['excise']."</td><td class='mid-text' >".ROUND($vat,2)."</td><td class='mid-text' >".ROUND($tcs,2)."</td><td class='mid-text' >".ROUND($total,2)."</td><td class='mid-text'>".$button."</td></tr>";

     
			}
			if($i<1){
			echo "<tr><td colspan='13' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}
	// print_r($data);

			
	
}
else if (isset($_GET['TP_num']) )	{

	 $TP_num=$_GET['TP_num'];

	   $sql2 = "UPDATE POPS_DISPATCH_ITEMS 
	SET STATUS=4 WHERE TP_NO = '$TP_num' and STATUS=3";

	 $stmt2 = sqlsrv_query($conn,$sql2);

}


else if (isset($_GET['reinvoice']) )
	
	{
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];
  $to_sno=$_GET['to_sno'];
  $from_sno=$_GET['from_sno'];
 if($Department=='ALL'){
 $sql = "select TP_NO,a.VEND_CODE,VEND_NAME,DEPARTMENT,INVOICE_NO,INVOICE_DATE,PO_NO,SUM(CASE_QUANTITY) as CASE_QUANTITY, sum(a.wsp*BOTTLE_QUANTITY) as wsp  ,sum(a.CUSTOM_DUTY*BOTTLE_QUANTITY) as CUSTOM_DUTY  ,sum(a.EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.wsp) *BOTTLE_QUANTITY) as wsp1,LIQUOR_TYPE_CD from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE 
      join POPS_PRICE_MASTER p on  a.BRAND_CODE=p.BRAND_CODE
where status =4 and b.DEPARTMENT in ('CLUB','HOTEL','RESTAURANT')  and S_NO between  '$from_sno' and '$to_sno'
group by [PO_NO],INVOICE_NO,INVOICE_DATE,DEPARTMENT, a.VEND_CODE,VEND_NAME,TP_NO,LIQUOR_TYPE_CD
order by INVOICE_NO ";

}

else {

 $sql = "select TP_NO,a.VEND_CODE,VEND_NAME,DEPARTMENT,INVOICE_NO,INVOICE_DATE,PO_NO,SUM(CASE_QUANTITY) as CASE_QUANTITY, sum(a.wsp*BOTTLE_QUANTITY) as wsp  ,sum(a.CUSTOM_DUTY*BOTTLE_QUANTITY) as CUSTOM_DUTY  ,sum(a.EXCISE_DUTY*BOTTLE_QUANTITY) as excise ,sum((a.wsp) *BOTTLE_QUANTITY) as wsp1,LIQUOR_TYPE_CD from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE 
      join POPS_PRICE_MASTER p on  a.BRAND_CODE=p.BRAND_CODE
where status =4 and b.DEPARTMENT='$Department' and S_NO between '$from_sno' and '$to_sno'
group by [PO_NO],INVOICE_NO,INVOICE_DATE,DEPARTMENT, a.VEND_CODE,VEND_NAME,TP_NO,LIQUOR_TYPE_CD order by INVOICE_NO ";



}
// exit;
	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;

	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		$button = "<input type='checkbox' onclick='checkAllChecked(this.name)' name='tp_invoice[]' id='".$row['INVOICE_NO']."'>";
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
    
		echo "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td>".$row['INVOICE_DATE']->format('Y-m-d')."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$row['DEPARTMENT']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".($row['CUSTOM_DUTY'])."</td><td class='mid-text' >".$row['excise']."</td><td class='mid-text' >".ROUND($vat,2)."</td><td class='mid-text' >".ROUND($tcs,2)."</td><td class='mid-text' >".ROUND($total,2)."</td><td class='mid-text'>".$button."</td></tr>";
     
			}
			if($i<1){

			echo "<tr><td colspan='14' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}
	

			
	
}

?>