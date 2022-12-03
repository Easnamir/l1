<?php 
session_start();

$user = $_SESSION["username"];
$COMPANY_id = $_SESSION['COMPANY_id'];
$CINV = $_SESSION['CINV'];
include 'includes/autoload.inc.php';
include 'includes/connect.php';

if (isset($_GET['tallyreport']) )
{
	$startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];

   $databases=['zeta','beam','gwalior','ALLIED','GRANO69 BEVERAGES PRIVATE LIMITED','SULA','Inbev'];
 $data=[];


   $sql = "select * from ( select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT,INVOICE_NO,INVOICE_DATE,PO_NO,PO_DATE,'**' as TP_NO,'' as TP_DATE ,
isnull(DEPARTMENT_NAME,b.VEND_CODE) VEND_CODE

,isnull(DEPARTMENT_NAME,b.VEND_NAME)VEND_NAME,
isnull(c.Tin,b.tin) TIN,
isnull(c.PAN,b.PAN_NO) PAN,
 'CORP' as Group_name,
 '56A- Excise Bond L1F' as Godown_Name,
a.brand_name as Item_Name,
(select COMPANY_NAME from POPS_COMPANY_DETAILS) as Item_Group ,
(select LOOKUP_VALUE from POPS_LOOKUP where LOOKUP_PK=bc.CATEGORY_CD) as Item_Category,
case when bc.PACK_SIZE= 1 then 'BTL'
else 'CASE'
end
as Item_Unit,
SUM(CASE_QUANTITY) as CASE_QUANTITY, 
a.wsp as Rate,
sum((a.wsp) *BOTTLE_QUANTITY) as Bill_Value,
sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as Sales_Custom_Duty 
,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as Excise_Sale ,
'Sales Local' as Voucher_Type_Name,
isnull(c.CURRENT_ADDRESS,b.VEND_ADDRESS) as Consginee_Address,LIQUOR_TYPE_CD
from POPS_DISPATCH_ITEMS a
join POPS_PRICE_MASTER bc on a.BRAND_CODE=bc.BRAND_CODE
join  POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE 
left join  POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	where status =4 and a.INVOICE_DATE between '$startdate' and '$enddate' and b.DEPARTMENT in('DSIIDC','DCCWS','DTTDC','DSCSC')
 group by [PO_NO],INVOICE_NO,INVOICE_DATE,DEPARTMENT_NAME,b.DEPARTMENT,PO_DATE,
b.VEND_CODE,b.VEND_NAME,c.Tin,b.tin,c.PAN,b.PAN_NO,a.BRAND_NAME,
 bc.CATEGORY_CD,a.wsp,c.CURRENT_ADDRESS,b.VEND_ADDRESS,LIQUOR_TYPE_CD,bc.PACK_SIZE) as aaa

 union all
  select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT,INVOICE_NO,INVOICE_DATE,PO_NO,PO_DATE,isnull(DEPARTMENT_NAME,TP_NO) as TP_NO,isnull(DEPARTMENT_NAME,TP_DATE) as TP_DATE ,
isnull(DEPARTMENT_NAME,b.VEND_CODE) VEND_CODE

,isnull(DEPARTMENT_NAME,b.VEND_NAME)VEND_NAME,
isnull(c.Tin,b.tin) TIN,
isnull(c.PAN,b.PAN_NO) PAN,
isnull(DEPARTMENT_NAME,b.DEPARTMENT)Group_name,
 '56A- Excise Bond L1F' as Godown_Name,
a.brand_name as Item_Name,
(select COMPANY_NAME from POPS_COMPANY_DETAILS) as Item_Group ,
(select LOOKUP_VALUE from POPS_LOOKUP where LOOKUP_PK=bc.CATEGORY_CD) as Item_Category,
case when bc.PACK_SIZE= 1 then 'BTL'
else 'CASE'
end
as Item_Unit,
SUM(CASE_QUANTITY) as CASE_QUANTITY, 
a.wsp as Rate,
sum((a.wsp) *BOTTLE_QUANTITY) as Bill_Value,
sum((a.CUSTOM_DUTY) *BOTTLE_QUANTITY) as Sales_Custom_Duty 
,sum(EXCISE_DUTY*BOTTLE_QUANTITY) as Excise_Sale ,
'Sales Local' as Voucher_Type_Name,
isnull(c.CURRENT_ADDRESS,b.VEND_ADDRESS) as Consginee_Address,LIQUOR_TYPE_CD
from POPS_DISPATCH_ITEMS a
join POPS_PRICE_MASTER bc on a.BRAND_CODE=bc.BRAND_CODE
join  POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE 
left join  POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
	where status =4 and a.INVOICE_DATE between '$startdate' and '$enddate'and b.DEPARTMENT not in('DSIIDC','DCCWS','DTTDC','DSCSC')
 group by [PO_NO],INVOICE_NO,INVOICE_DATE,DEPARTMENT_NAME,b.DEPARTMENT,PO_DATE,
 TP_NO,TP_DATE,b.VEND_CODE,b.VEND_NAME,c.Tin,b.tin,c.PAN,b.PAN_NO,a.BRAND_NAME,
 bc.CATEGORY_CD,a.wsp,c.CURRENT_ADDRESS,b.VEND_ADDRESS,LIQUOR_TYPE_CD,bc.PACK_SIZE
 order by  INVOICE_NO 
 ";


	$stmt1 = sqlsrv_query($conn,$sql);
	$i=0;
	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){


		 $data[] = $row;
}



// print_r($data);
// exit;
foreach ($data as $row ) {

	$excise_rev=0;
	$notcs = ['DSIIDC Limited','DCCWS Limited','DTTDC Limited','DSCSC Limited','CLUB'];
			$hcr = ['CLUB','HOTEL','RESTAURANT'];
			if(in_array($row['DEPARTMENT'],$hcr)){
				if($row['LIQUOR_TYPE_CD'] == '171'){
					if($row['DEPARTMENT'] =='HOTEL'){
						$excise_rev=0.3*$row['Bill_Value'];
					}
					else{
						$excise_rev=0.2*$row['Bill_Value'];
					}
				}
			}
			$cost = $row['Bill_Value']+$row['Sales_Custom_Duty']+$row['Excise_Sale']+$excise_rev;
			$vat = ($cost)*0.25;
			$tcs = ($cost+$vat-$excise_rev)*0.01;
			// $tcs = 
			// echo $row['DEPARTMENT_NAME'];
			if(in_array($row['DEPARTMENT'],$notcs)){
				 $tcs=0;
			}
			// exit;
		  $total=$cost+$vat+$tcs-$excise_rev; 

echo "<tr><td  class='mid-text'>". ++$i. "</td>
<td class='mid-text' >".$row['INVOICE_NO']."</td><td class='mid-text' >".$row['INVOICE_DATE']->format('d-m-Y')."</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text' >".$row['PO_DATE']->format('d-m-Y')."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".(
	$row['TP_DATE'])."</td><td class='mid-text' >".$row['VEND_CODE']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['TIN']."</td><td class='mid-text' >".$row['PAN']."</td><td class='mid-text' >".$row['Group_name']."</td><td class='mid-text' >".$row['Godown_Name']."</td><td class='mid-text' >".$row['Item_Name']."</td><td class='mid-text' >".$row['Item_Group']."</td><td class='mid-text' >".$row['Item_Category']."</td><td class='mid-text' >".$row['Item_Unit']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".$row['Rate']."</td><td class='mid-text' >".$row['Bill_Value']."</td><td class='mid-text' >".$row['Excise_Sale']."</td><td class='mid-text' >".$row['Sales_Custom_Duty']."</td><td class='mid-text' >".round($vat,2)."</td><td class='mid-text' >".round($tcs,2)."</td><td class='mid-text' >".round($total,2)."</td><td class='mid-text' >".$row['INVOICE_NO']."</td><td class='mid-text' >".$row['Voucher_Type_Name']."</td><td class='mid-text' >".$row['Voucher_Type_Name']."</td><td class='mid-text' ></td><td class='mid-text' ></td><td class='mid-text' >".$row['Consginee_Address']."</td></tr>";

     
			}
			// exit;
			if($i<1){
			echo "<tr><td colspan='11' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}




	
	
}


?>