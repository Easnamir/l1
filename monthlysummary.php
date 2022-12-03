<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
$data = $_SESSION['monthwisereport'];
$data_array = unserialize($data);

if(sizeof($data_array) > 0){

 $fromdate = $_SESSION['startdate'];
 $todate = $_SESSION['enddate'];
 $Department =$_SESSION['Department'];

$fromdate = date("d-m-Y", strtotime($fromdate));
$todate = date("d-m-Y", strtotime($todate));

$html = '<table border=1 cellspacing=0 cellpadding=5 width=700 align=center> <tr align=center  > <td bgcolor="pink" colspan=7><center>MONTHLY SALE SUMMARY</center></td></tr>
			
			<tr > <td colspan=7 bgcolor="pink">Report Date : '.$fromdate.' to '.$todate.'</td></tr>
			<tr><th bgcolor="pink">SNo</th><th bgcolor="pink">Department</th><th bgcolor="pink">Party Code</th><th bgcolor="pink">Brand Name</th><th bgcolor="pink">Case</th><th bgcolor="pink">wsp</th><th bgcolor="pink">excise</th></tr>';
foreach ($data_array as $key => $row) {
		$html .= "<tr><td  class='mid-text'>". ++$i. "</td><td class='mid-text' >".$row['DEPARTMENT_NAME']."</td><td class='mid-text' >".$row['VEND_CODE']."</td><td class='mid-text' >".$row['BRAND_NAME']."</td><td class='mid-text' >".$row['CASE_QUANTITY']."</td><td class='mid-text' >".($row['wsp'])."</td><td class='mid-text' >".$row['excise']."</td></tr>";

		$case += $row['CASE_QUANTITY'];
		$wsp += $row['wsp'];
		$excise += $row['excise'];


	}

	//echo $html;
	$html .= "<tr ><td colspan='4' bgcolor='pink'><b style='float:right; padding-right:10px;'>Total</b></td><td bgcolor='pink'>".$case."</td><td bgcolor='pink'>".$wsp."</td><td bgcolor='pink'>".$excise."</td></tr></table>";
	
			
			header('Content-Type: application/xls');
			$file="MONTHLY SALE SUMMARY.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;

}
?>