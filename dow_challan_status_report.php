<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();


	include 'includes/autoload.inc.php';
include 'includes/connect.php';

	if (isset($_GET['challanstatusdatareport']) )
	
	{
 $Department=$_GET['Department'];
  $startdate=$_GET['startdate'];
  $enddate=$_GET['enddate'];
$fromdate = $_GET['startdate'];
$todate = $_GET['enddate'];;

$company_name=$_SESSION['COMPANY_NAME'];
$fromdate1 = date("d-m-Y", strtotime($fromdate));
$todate1 = date("d-m-Y", strtotime($todate));

   $sql = "select * from 
(select distinct  isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME ,VEND_NAME,A.VEND_CODE,a.TP_NO,a.CHALLAN_NO,SUPPLY_DATE
from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE
left join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT

	where  b.DEPARTMENT in $Department and cast(a.CREATED_DATE as date) between '$startdate' and '$enddate' 
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
$html = '<table border=1 cellspacing=0 cellpadding=5 width=700 align=center> <tr align=center  > <td bgcolor="pink" colspan=8><center>Challan Status Report</center></td></tr>
			<tr > <td colspan=8 bgcolor="pink" align="center" >'.$company_name.'</td></tr>
			<tr > <td colspan=8 bgcolor="pink">Report Date : '.$fromdate1.' to '.$todate1.'</td></tr>
				<tr><th>SNo</th><th>Department</th><th>Vend Name</th><th>TP No</th><th>Challan No </th><th>Challan Date</th><th>Status</th><th>Remark </th></tr>	';
	while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
		$data[] = $row;
		
		 $supp_date = $row['SUPPLY_DATE']?$row['SUPPLY_DATE']->format('d-m-Y'):'NA';
		 $supp_date1 = $row['SUPPLY_DATE']?$row['SUPPLY_DATE']->format('Y-m-d'):'NA';

    $challan_num= $row['CHALLAN_NO']?$row['CHALLAN_NO']:'NA';
		$html.= "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['TP_NO']."</td><td class='mid-text' >".$challan_num."</td><td class='mid-text' >".$supp_date."</td><td class='mid-text' >".$row['STATUS_CHALLAN']."</td><td class='mid-text' >".$row['REMARK_CHALLAN']."</td></tr>";
			}
			if($i<1){
			$html.= "<tr><td colspan='8' style='text-align: center !important; '><b>No Data Found!!</b></td></tr>";
		}

		// print_r($data);
	

	
}
		 // $html=$data;
		
			header('Content-Type: application/xls');
			$file="Challan Status Report.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;

 // session_destroy();
?>