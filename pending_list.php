<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
include 'includes/autoload.inc.php';
include 'includes/connect.php';
	$USER = $_SESSION['username'];

  $sql = "SELECT a.PO_NO,A.VEND_CODE,DEPARTMENT,isnull(b.VEND_NAME,'Vend not added') as VEND_NAME,sum(A.[CASE]) [CASE] ,sum(a.bottle) bottle,isnull (cast(sum((EXCISE_PRICE+WSP+CUSTOM_DUTY+sale_tax+((EXCISE_PRICE+WSP+CUSTOM_DUTY+sale_tax)*0.01))* bottle)as decimal(18,2)),0)AS BILL_AMOUNT,isnull(sum(CAST(EXCISE_PRICE*BOTTLE AS decimal(18,2))),0) AS EXCISE_DUTY 
 ,c.BRAND_CODE,c.SIZE_VALUE,c.BRAND_NAME FROM POPS_PANDING_PO_CREATE A
full JOIN POPS_VEND_DETAILS B ON A.VEND_CODE=B.VEND_CODE
full JOIN POPS_PRICE_MASTER C ON C.BRAND_CODE=A.BRAND_CODE where a.STATUS_CD=0 and   b.DEPARTMENT not  in('DSIIDC','DCCWS','DTTDC','DSCSC')   --and tp_no not in (

group by PO_NO,A.VEND_CODE,VEND_NAME,DEPARTMENT,c.BRAND_CODE,c.SIZE_VALUE,c.BRAND_NAME";

	$stmt = sqlsrv_query($conn,$sql);
	//echo '<option value="">';
 
 
$html = '<table border=1 cellspacing=0 cellpadding=5 width=700 align=center> <tr align=center  > <td bgcolor="pink" colspan=12><center><b>Pending Po List </center></td></tr>
			<tr > <td colspan=12 bgcolor="pink" align=center>'.$_SESSION['COMPANY_NAME'].'</td></tr>
			<tr > <td colspan=12 bgcolor="pink" align=center>'.$_SESSION['ADDRESS'].'</td></tr>
			<tr ><th bgcolor="pink" >SNo</th><th bgcolor="pink" >PO Number</th><th bgcolor="pink"  >Department</th><th bgcolor="pink"  >Vend Code</th><th bgcolor="pink"  >Company Name</th><th bgcolor="pink" >Brand Code</th><th bgcolor="pink" >Brand Name</th><th bgcolor="pink" >Size(ml)</th><th bgcolor="pink"  >Case</th><th bgcolor="pink" >Bottles</th><th bgcolor="pink" >Bill Amount</th><th bgcolor="pink" >Revenue</th></tr>	';
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$html .= "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['PO_NO']."</td><td class='mid-text'>".$row['DEPARTMENT']."</td><td class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text'>".$row['VEND_NAME']."</td><td  class='mid-text'>".$row['BRAND_CODE']."</td><td  class='mid-text'>".$row['BRAND_NAME']."</td><td  class='mid-text'>".$row['SIZE_VALUE']."</td><td  class='mid-text'>".$row['CASE']."</td><td  class='mid-text'>".$row['bottle']."</td><td  class='mid-text'>".$row['BILL_AMOUNT']."</td><td  class='mid-text'>".$row['EXCISE_DUTY']."</td></tr>";

		$case += $row['CASE_QUANTITY'];
		$wsp += $row['wsp'];
		$excise += $row['excise'];


	}

	// echo $html;
	// exit;
	
			header('Content-Type: application/xls');
			$file="Pending Po List.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;

?>