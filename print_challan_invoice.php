<?php
require_once __DIR__ . '/vendor/autoload.php';

include 'includes/fun.php';
require 'vendor/autoload.php';

// This will output the barcode as HTML output to display in the browser
// $redColor = [255, 0, 0];
$redColor = [0, 0, 0];


$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
$tp_array = [];
session_start();
// $shop_id = $_SESSION['SHOP_KEY'];
// $shop_name = $_SESSION['SHOP_NAME'];
// $shop_address = $_SESSION['SHOP_ADDRESS'] ;
// $pin = $_SESSION['PIN_CODE'];
// $vendid= $_SESSION['VEND_ID'];
// $gst= $_SESSION['GST'];
// $PHONE= $_SESSION['PHONE'];
$user = $_SESSION["username"];
$current_date = date("Y-m-d H:i:s");
$mobile= $_SESSION['PHONE'];
$email= $_SESSION['EMAIL_ID'];
$COMPANY_NAME = $_SESSION['COMPANY_NAME'];
$COMPANY_ADDRESS = $_SESSION['ADDRESS'];
$GST_NUMBER = $_SESSION['GST_NUMBER'];
$WARD_NAME = $_SESSION['WARD_NAME'];
$WARD_NO = $_SESSION['WARD_NUM'];
$C_Pan = $_SESSION['PAN'];
$C_CIN = $_SESSION['CIN'];
$C_EMAIL = $_SESSION['EMAIL'];
$C_pin = $_SESSION['PIN_CODE'];
$C_TIN = $_SESSION['TIN'];
$C_PHONE = $_SESSION['PHONE'];
$C_Zone= $_SESSION['ZONE'];
$inv = $_SESSION['CINV'];
$logo = "images/$inv.jpg";

  $td1 = "<td colspan=2 align=center style='background-image: url($logo); background-image-resize: 1;
	background-repeat: no-repeat; background-position: top left;' > ";
// $email='';
include 'includes/autoload.inc.php';
include 'includes/connect.php';
if(date('m')<4){
			 $finyear = (date('y')-1).'-'.date('y');
		}
		else{
			$finyear = date('y').'-'.(date('y')+1);
		}
		if(isset($_GET['fun']) && $_GET['fun']=='printChallan'){

			extract($_POST);
			
	$item_arr = json_decode($print_arr);
	// var_dump($item_arr);
	// 		exit;	
	$html_all='';
	$len = count($item_arr);
	$xyz=0;
foreach($item_arr as $tp_num){
$xyz++;
// echo($tp_num);
			  $sql2="select * from POPS_DISPATCH_ITEMS a
              join POPS_VEND_DETAILS b on b.VEND_CODE=a.VEND_CODE
              join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
               where tp_no='$tp_num'";         
				$data=[];
				$stmt2 = sqlsrv_query($conn,$sql2);
				$sql = "UPDATE POPS_DISPATCH_ITEMS SET STATUS=2 where TP_NO='$tp_num' and STATUS=1";	
	      $stmt = sqlsrv_query($conn,$sql);
				$html="<table width=100%  border=1>                 
				<tr>$td1<h3 class='center_text under_line'>Challan</h3>
				<h2>$COMPANY_NAME</h2>
				$COMPANY_ADDRESS -$C_pin<br>
				FSSAI: $C_CIN, PAN: $C_Pan<br>
				TIN: $C_TIN, GST: $GST_NUMBER<br>
				
				Email: $C_EMAIL, Contact: $C_PHONE</td></tr>
				
				<tr><td colspan=2 >";
					while($row2 = sqlsrv_fetch_array($stmt2,SQLSRV_FETCH_ASSOC)){
			
					$data[]=$row2;
					// $html .= $row2['LICENCE_CODE'];
					$businessName = $row2['VEND_NAME'];
					$vend_code = $row2['VEND_CODE'];
					$ZONE_NAME = $row2['ZONE_NAME'];


					$OUTLET = $row2['DEPARTMENT_NAME'];

					$businessAddress = $row2['VEND_ADDRESS'];
					$pan = $row2['PAN'];
					$LICENCE_CODE = $row2['ExciseNO'];
					$dispatch_date = $row2['SUPPLY_DATE']->format('d M Y');
					$tp_date = $row2['TP_DATE']->format('d M Y');

					$CREATED_DATE = $row2['CREATED_DATE']->format('d M Y');
					$PO_DATE = $row2['PO_DATE']->format('d M Y');
					$pin_cust = $row2['PIN_CODE'];
					$inv_status = $row2['STATUS_CD'];
					$tp_num = strtoupper($row2['TP_NO']);
					$po_num = strtoupper($row2['PO_NO']);
					$inv_no =strtoupper($row2['CHALLAN_NO']);

					$GST_NO = $row2['GST_NO'];
					$TIN_NO = $row2['TIN'];
				}
				file_put_contents($tp_num.'.png', $generator->getBarcode($tp_num, $generator::TYPE_CODE_128, 3, 50, $redColor));
				$tp_array[]=$tp_num;
				$sum_quantity_case=0;
				$sum_quantity=0;
				$sum_total_amount=0;
				$sum_custom=0;
				$sum_excise=0;
				$sum_discount=0;
				$sum_vat = 0;
				$sum_mrp_amount=0;
					$html .="<table border=1 width=100% cellspacing=0 cellpadding=6 style='font-size: 12px'>
					<tr><td align=left width=50% style='border-top: none'>
					<b> Corporation: $OUTLET</b> <br> Shop Name :$businessName 
					<br>Address : $businessAddress ,$pin_cust
					<br>TIN :  $TIN_NO &nbsp;&nbsp;&nbsp;&nbsp; PAN : $pan
					
					</td><td align=left style='border-top: none'>
						Challan No : $inv_no &nbsp;&nbsp;&nbsp;&nbsp;
						Challan Date   : $dispatch_date
						 <br>TP No.    : $tp_num &nbsp;&nbsp;&nbsp;&nbsp;
						 TP Date       : $tp_date
						 <br>PO No.    : $po_num &nbsp;&nbsp;&nbsp;&nbsp;
						PO Date         : $PO_DATE
					
					</td></tr>
					</table>
				</td></tr>
				<tr><td colspan=2>
				<table border=1 cellspacing=0 cellpadding=1 width=100%>
				<tr><th width=6%>S/N</th><th colspan=4 >Particulars</th><th >Size(ml)</th><th >MRP</th><th > Qty</th><th width=10%>Rate/Case</th><th width=12%>Amount</th></tr>";
				

				// print_r($data);
				
				$tcs=0;
				$row_count=count($data);
				for($i=0;$i<$row_count;$i++){
					$qty = $data[$i]['CASE_QUANTITY']*$data[$i]['PACK_SIZE'];
					// print_r($data[$i]);
					// exit;
					$mrp = $data[$i]['MRP'];
					 $wsp = $data[$i]['WSP'];
					 // "<br>";
					 
					 $sum_excise += $data[$i]['EXCISE_DUTY']*$qty;
					 // "<br>";
					 $custom = $data[$i]['CUSTOM_DUTY'];
					 // "<br>";
					 $cost = $wsp+$custom;
					 
					$rate = number_format($cost*$data[$i]['PACK_SIZE'],2,'.','');
					
					$amount = number_format($rate*$data[$i]['CASE_QUANTITY'],2,'.','');
					
					 $html .=("<tr><td class='no-top'>".($i+1)."</td><td colspan=4 class='no-top-left'>".$data[$i]['BRAND_NAME']."</td><td class='no-top-left'>".$data[$i]['SIZE_VALUE']."</td><td class='no-top-left'>".$data[$i]['MRP']."</td><td class='no-top-left'>".$data[$i]['CASE_QUANTITY']."</td><td class='no-top-left'>".$rate."</td><td align=right class='no-top-left'>".$amount."</td><tr>");
					 $sum_quantity += $data[$i]['CASE_QUANTITY'];
					 $sum_vat += $data[$i]['SALE_TAX']*$qty;

					 $sum_total_amount += $amount;
					 // $sum_quantity += $data[$i]['CASE_QUANTITY'];
					 
				}
			   
				 $sum_excise_total = number_format(($sum_excise +  $sum_total_amount),2,'.','');
	

				// exit;
				$html .="<tr><td class='no-top  no-right ' colspan=6>&nbsp;</td><td class='no-top no-left no-right '></td><td class='no-top no-left no-right' align=right colspan=2><h3>Total:</h3><p>Excise Duty :</p></td><td align=right class='no-top'><p>".number_format((float)$sum_total_amount, 2, '.', '')."</p><p >".number_format((float)$sum_excise, 2, '.', '')."</p></td></tr>";
			
				
				
				
	while($i<11){
					 $html .=("<tr><td class='no-top-bottom' colspan=10>&nbsp;</td><tr>");
					 $i++;
				}	
				
					$html .="<tr><td colspan=7 class='no-bottom '><p><b>Amount(in words):</b> ".digit_to_word($sum_excise_total)."  Only</b></p></td><td class='no-bottom ' colspan=1>".$sum_quantity."</td><td class='no-bottom 'align=right colspan=1><p><b>Total:</b></p></td><td align=right class='no-bottom '><p>".number_format($sum_excise_total,2,'.','')."</p></td></tr>";		
				
				$html .="<tr><td colspan=7 align=left style='border-right: none'>
				Received_____________________Cases In Good Order & Condition<br><br>Receiver's Signature___________________
				</td><td colspan=3  class='text-left' style='border-left: none'>For <b>$COMPANY_NAME</b><br><br>Authorised Signatory<br><br><br><img src='$tp_num.png' width=150 /><br>$tp_num</td></tr>";
				$html.="</table></td></tr>";
				

				
				$html.="</table>";
				if($_SESSION['CINV']=='SVL'){
					$payment = 'Payment to be made in advance.';
				}
				else{
					$payment = 'Payment to be made on or before 60 days from date of invoice/receipt of goods (due date).';
				}
				


				$html .="<table border=1 cellspacing=0 cellpadding=1 width=100%><tr><td class=' no-top terms' align=left colspan=9>
				
				<p><b>Terms & Conditions:</b></p>
				<ol>
				<li>$payment</li>
        <li> No return and No exchange after delivery.</li>
        <li>Any damages, shortages, etc should be repoted at the time of delivery. No claims will be accepted after
        delivery.</li>
        <li>Our responsibility shall cases on delivery of goods and acknowledgement of the invoice.</li>
        <li>Interest @ 18% p.a. applicable on payments made after due date.</li>
        <li>Each bounce cheque shall be charged Rs 250 or 0.2% of the amount, whichever is higher.</li>
        <li>All disputes are subject to Delhi Jurisdiction.</li>
        <li>All payments are subject to applicable taxes.</li>
				

				<li>This is a computer-generated challan. </li>
				</ol>

				</td></tr></table>";

				// echo $html;
				// exit;
				if($xyz<$len)
				$html = $html."<pagebreak>";

				$html_all .=$html;
			}
			// echo $html_all;
			// print_r($data);
			// exit;
				$file="Challan:-".$dispatch_date.".pdf";
				$stylesheet = file_get_contents('css/dispatch-inv1.css');
				$mpdf = new \Mpdf\Mpdf();
				$mpdf->setTitle($file);
				// if(!$inv_status){
				// $mpdf->SetWatermarkText('CANCELLED');
				// $mpdf->showWatermarkText = true;
				// }
				$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
				$mpdf->WriteHTML($html_all,\Mpdf\HTMLParserMode::HTML_BODY);
				// $mpdf->Output();
				$mpdf->Output($file,'D');
				
				foreach($tp_array as $tp){
					unlink($tp.'.png');
				}				
		}
		

?>