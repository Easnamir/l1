<?php
// require_once __DIR__ . '/vendor/autoload.php';
session_start();
$data = $_SESSION['customer'];
$data_array = unserialize($data);

// print_r($data_array);
// exit;

if(sizeof($data_array) > 0){
$i = 0;

$html = '<table border=1 cellspacing=0 cellpadding=5 width=700 align=center> <tr align=center > <td colspan=11><center>List Of Customer </center></td></tr>
				<tr>	<th width="5%">S.No</th><th>Customer Code</th><th>Company Name</th><th>Business Name</th><th>Email Id.</th><th>Owner Name</th><th>Phone Number</th>
									<th>Business Address </th><th>Pin Code</th><th>Licence Number</th><th>PAN Number</th></tr>';
foreach ($data_array as $key => $row) {
		$html .= "<tr><td  class='mid-text'>". ++$i. "</td><td  class='mid-text' >".$row['CUSTOMER_CODE']."</td><td  class='mid-text' >".$row['COMPANY_NAME']."</td><td  class='mid-text' >".$row['BUSINESS_NAME']."</td><td  class='mid-text'>".$row['EMAIL']."</td><td  class='mid-text'>".$row['OWNER']."</td><td  class='mid-text'>".$row['CONTACT_NO']."</td><td  class='mid-text'>".$row['BUSINESS_ADDRESS']."</td><td  class='mid-text'>".$row['PIN_NO']."</td><td  class='mid-text'>".$row['LICENCE_CODE']."</td><td  class='mid-text'>".$row['PAN_NO']."</td></tr>";

		
	}
	$html .= "<tr></tr></table>";
	if($i < 1){
		$this->session->error="No Data Found..";
		redirect($_SERVER['HTTP_REFERER']);
	}
	else{

		
			
			header('Content-Type: application/xls');
			$file="list of Customer .xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;
		
		
}
}
else{
	header('Location: ' . $_SERVER['HTTP_REFERER']);
}
?>