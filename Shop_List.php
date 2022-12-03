<?php
require_once __DIR__ . '/vendor/autoload.php';
session_start();
$data = $_SESSION['Shop_List'];
$data_array = unserialize($data);

if(sizeof($data_array) > 0){

 
$html = '<table border=1 cellspacing=0 cellpadding=5 width=700 align=center> <tr align=center  > <td bgcolor="pink" colspan=11><center>SHOP LIST </center></td></tr>
					<tr><th width="5%">S.No</th><th>Vend ID</th><th>Name</th><th>License No</th>
									<th> Address </th><th>Pin</th><th>TIN No</th><th>PAN Number</th><th>Gst No</th><th>Fssai no </th><th>Department</th></tr>';
foreach ($data_array as $key => $row) {
		$html .= "<tr><td  class='mid-text'>". ++$i. "</td><td  class='mid-text'>".$row['VEND_CODE']."</td><td  class='mid-text' >".$row['VEND_NAME']."</td><td  class='mid-text'>".$row['ExciseNO']."</td><td  class='mid-text'>".$row['VEND_ADDRESS']."</td><td>".$row['PIN_CODE']."</td><td  class='mid-text'>".$row['TIN']."</td><td  class='mid-text'>".$row['PAN_NO']."</td><td>".$row['GST_NO']."</td><td>".$row['FSSAI']."</td><td>".$row['DEPARTMENT']."</td></tr>";



	}

			
			header('Content-Type: application/xls');
			$file="Shop List.xls";
			header("Content-Disposition: attachment; filename=$file");
			echo $html;

}
?>