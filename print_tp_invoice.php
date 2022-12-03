<?php
require_once __DIR__ . '/vendor/autoload.php';
include 'includes/fun.php';
session_start();

require 'vendor/autoload.php';

// This will output the barcode as HTML output to display in the browser
// $redColor = [255, 0, 0];
$redColor = [0, 0, 0];
$generator = new Picqer\Barcode\BarcodeGeneratorPNG();
$tp_array = [];
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
extract($_POST);
$invoice_arr = explode('#',$invoice_list);
// var_dump($invoice_arr);
// exit;
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
		$html_all='';
		$xyz=0;
		$len = count($invoice_arr);
		foreach($invoice_arr as $invoice){
			$tp_num = $_GET['tp_num'];
			  $sql2="select a.*,b.*,c.LIQUOR_TYPE_CD,b.gst_no from POPS_DISPATCH_ITEMS a
				join POPS_VEND_DETAILS b on b.VEND_CODE=a.VEND_CODE join POPS_PRICE_MASTER c on a.BRAND_CODE=c.BRAND_CODE where INVOICE_NO='$invoice'";
				$data=[];
				$stmt2 = sqlsrv_query($conn,$sql2);

				while($row2 = sqlsrv_fetch_array($stmt2,SQLSRV_FETCH_ASSOC)){
					// print_r($row2);
					$data[]=$row2;
					// $html .= $row2['LICENCE_CODE'];
					$businessName = $row2['VEND_NAME'];
					$vend_code = $row2['VEND_CODE'];
					$ZONE_NAME = $row2['ZONE_NAME'];


					$OUTLET = $row2['DEPARTMENT'];

					$businessAddress = $row2['VEND_ADDRESS'];
					$pan = $row2['PAN_NO'];
					$LICENCE_CODE = $row2['ExciseNO'];
					$dispatch_date = $row2['INVOICE_DATE']->format('d M Y');
					$tp_date = $row2['TP_DATE']->format('d M Y');

					$CREATED_DATE = $row2['CREATED_DATE']->format('d M Y');
					$PO_DATE = $row2['PO_DATE']->format('d M Y');
					$pin_cust = $row2['PIN_CODE'];
					$inv_status = $row2['STATUS_CD'];
					$tp_num = strtoupper($row2['TP_NO']);
					$po_num = strtoupper($row2['PO_NO']);
					$inv_no =strtoupper($row2['INVOICE_NO']);
					$liq_type=$row2['LIQUOR_TYPE_CD'];
					$GST_NO = $row2['GST_NO'];
					$TIN_NO = $row2['TIN'];
          $FSSAI = $row2['FSSAI'];
				}
        file_put_contents($tp_num.'.png', $generator->getBarcode($tp_num, $generator::TYPE_CODE_128, 3, 50, $redColor));
				$tp_array[]=$tp_num;
				$html="<table width=100% border=1>
        
				<tr>
        $td1
        
        <h3 class='center_text under_line'>Tax Invoice</h3>
        <h2 class='center_text'>$COMPANY_NAME</h2>
        <p class='center_text'>
        $COMPANY_ADDRESS - $C_pin<br>
        FSSAI: $C_CIN, PAN: $C_Pan<br>
        TIN: $C_TIN, GST: $GST_NUMBER<br>
        Email: $C_EMAIL, Contact: $C_PHONE
        </p>
        </td></tr>
				
				<tr><td colspan=2>";
					
				// print_r($data);
				// exit;
				$sum_quantity=0;
				$sum_total_amount=0;
				$sum_custom=0;
				$sum_excise=0;
				$sum_discount=0;
				$sum_vat = 0;
				$sum_mrp_amount=0;
				if($liq_type==171){
					$html .="<table border=1 width=100% cellspacing=0 cellpadding=6 style='font-size: 12px'>
					<tr><td align=left width=50%>
					<b> Dept:</b> $OUTLET 
					 <br> <b> M/S:</b> $businessName 
                      
					<br> $businessAddress,$pin_cust
					<br><b>TIN : </b> $TIN_NO &nbsp;&nbsp;&nbsp;&nbsp; <b>PAN :</b> $pan
          <br><b>GST No : </b> $GST_NO <br><b>FSSAI No :</b> $FSSAI
					
					</td><td align=left >
						<b>Invoice No  :</b> $inv_no &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						<b>Date : </b></b> $dispatch_date
					<br><b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  TP No  : </b> $tp_num &nbsp;
					    <b>Date : </b>$tp_date
					<br><b>&nbsp; Order No  :</b> $po_num &nbsp;&nbsp;&nbsp;&nbsp; 
						<b>Date :</b> $PO_DATE
					
					</td></tr>
					</table>
				</td></tr>
				<tr><td colspan=2>
				<table border=1 cellspacing=0 cellpadding=1 width=100%>
				<tr><th>Sno.</th><th colspan=4>Particulars</th><th>Size</th><th>MRP</th><th>Quantity</th><th>WSP per
C/s</th><th>Amount</th></tr>";
				

				// print_r($data);
				$tcs=0;
				$row_count=count($data);
				$wsp_cal=0;
				$revnue_exc=0;
				$rate_rc= $OUTLET=='HOTEL'?0.3:0.2;
				$rate_tcs = $OUTLET=='CLUB'?0:0.01;
				for($i=0;$i<$row_count;$i++){
					$qty = $data[$i]['CASE_QUANTITY']*$data[$i]['PACK_SIZE'];
					
					$mrp = $data[$i]['MRP'];
					 $wsp = $data[$i]['WSP'];
					 $sum_excise += $data[$i]['EXCISE_DUTY']*$qty;
					 // "<br>";
					 $custom = $data[$i]['CUSTOM_DUTY'];
					 // "<br>";
					 $packSize = $data[$i]['PACK_SIZE'];
					 $bottles = $packSize==1?'Btl.':'case';
					 $cost = $wsp+$custom;
					$rate = number_format($cost*$data[$i]['PACK_SIZE'],2,'.','');
					
					$amount = number_format($rate*$data[$i]['CASE_QUANTITY'],2,'.','');
					
					 $html .=("<tr><td class='no-top'>".($i+1)."</td><td colspan=4 class='no-top-left'>".$data[$i]['BRAND_NAME']."</td><td align=center  >".$data[$i]['SIZE_VALUE']." ml </td>
					 <td class='no-top-left' >".$data[$i]['MRP']."</td><td class='no-top-left' >".$data[$i]['CASE_QUANTITY']." $bottles</td>
					 <td class='no-top-left' >".$rate."</td><td align=right class='no-top-left'>".$amount."</td><tr>");
					 
					 $sum_quantity += $data[$i]['CASE_QUANTITY'];
					 $wsp_cal += round($data[$i]['WSP'],2)*$qty;
					 $revnue_exc += round($data[$i]['WSP']*$rate_rc,2)*$qty;
				}
				// echo $wsp_cal = round($wsp_cal*0.2,2);
				// exit;
				//  $revnue_exc = $OUTLET=='HOTEL'?$wsp_cal*0.30:$wsp_cal*0.20;
				//  echo $revnue_exc;
				//  exit;
				 $sum_excise_total = number_format(($sum_excise +  $sum_total_amount),2,'.','');
				 $total_before_vat = $sum_excise_total+$revnue_exc+$wsp_cal;
				 $vat = number_format(($total_before_vat*0.25),2,'.','');
				 $sum_vat_total = $total_before_vat+$vat;
				 $total_after_vat=$sum_vat_total-$revnue_exc;
				 $total_tcs = $total_after_vat*$rate_tcs;
				 $net_payable = number_format(($total_after_vat+$total_tcs),2,'.','');

				$html .="<tr><td class='only-left' colspan=6>&nbsp;</td><td class='no-border'></td><td class='no-border' align=right colspan=2><h3>Total:</h3><p>Excise Duty :</p></td><td align=right class='no-top'><p>".number_format((float)$wsp_cal, 2, '.', '')."</p><p>".number_format((float)$sum_excise, 2, '.', '')."</p></td></tr>";
				$html .="<tr><td class='only-left' align=right colspan=5>Add</td><td class='no-border' align=right colspan=4><p>Revenue Difference of HCR deposited by customer:</p></td><td align=right class='no-top'><p>".number_format($revnue_exc,2,'.','')."</p></td></tr>";
				$html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=4><p><b>Total:</b></p><p>VAT:</p></td><td align=right class='no-top'><p>".number_format($total_before_vat,2,'.','')."</p><p>".number_format((float)($vat), 2, '.', '')."</p></td></tr>";

				$html .="<tr><td class='only-left' align=right colspan=5>&nbsp;<p>Less</p></td><td class='no-border' align=right colspan=4>Total:<p>Revenue Difference of HCR deposited by customer :</p></td><td align=right class='no-top'><p>".number_format((float)$sum_vat_total, 2, '.', '')."</p><p>".number_format($revnue_exc, 2, '.', '')."</p></td></tr>";

				$html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=4><p><b>Total:</b></p><p>TCS:</p></td><td align=right class='no-top'><p>".number_format($total_after_vat,2,'.','')."</p><p>".number_format((float)($total_tcs), 2, '.', '')."</p></td></tr>";
				$html .="<tr><td class='no-top no-right' align=left colspan=8>
				<p><b>Amount (in words) :</b><em> ".ucfirst(digit_to_word($net_payable))." Only</em></p>
				</td><td class=' no-top no-left' align=right colspan=1><b>Total</b>:<td align=right class='no-top'>".number_format((float)($net_payable), 2, '.', '')."</td></tr>";

				// $html .="<tr><td class='no-top no-right' align=left colspan=8>
				// <p><b>Amount (in words) :</b><em> ".ucfirst(digit_to_word($net_payable))." Only</em></p>
				// </td><td class=' no-top no-left ' align=right colspan=1><td align=right class='no-top-bottom'></td></tr>";

				while($i<8){
					 $html .=("<tr><td class='no-top-bottom' colspan=10>&nbsp;</td><tr>");
					 $i++;
				}	
				
							
				
				$html .="<tr><td colspan=6 align=left style='border-right: none'>Received_____________________Cases In Good Order & Condition<br><br>Receiver's Signature___________________</td><td colspan=4 class='text-right' style='border-left: none'>For <b>$COMPANY_NAME</b><br><br><br>Authorised Signatory<br><br><br><img src='$tp_num.png' width=150 /><br>$tp_num</td></tr>";
				$html.="</table></td></tr>";
				
				$html.="</table>";
			}
			else if($liq_type==172){
				$html .="<table border=1 width=100% cellspacing=0 cellpadding=6 style='font-size: 12px'>
					<tr><td align=left width=50%>
					<b> Dept:</b> $OUTLET 
					 <br> <b> M/S:</b> $businessName 
                      
					<br> $businessAddress,$pin_cust
					<br><b>TIN : </b> $TIN_NO &nbsp;&nbsp;&nbsp;&nbsp; <b>PAN :</b> $pan
					<br><b>GST No : </b> $GST_NO <br> <b>FSSAI No :</b> $FSSAI
					</td><td align=left >
						<b>Invoice No  :</b> $inv_no &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
						<b>Date : </b></b> $dispatch_date
					<br><b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;  TP No  : </b> $tp_num &nbsp;
					    <b>Date : </b>$tp_date
					<br><b>&nbsp; Order No  :</b> $po_num &nbsp;&nbsp;&nbsp;&nbsp; 
						<b>Date :</b> $PO_DATE
					
					</td></tr>
					</table>
				</td></tr>
				<tr><td colspan=2>
				<table border=1 cellspacing=0 cellpadding=1 width=100%>
				<tr><th width=6%>Sno.</th><th colspan=4>Particulars</th><th >Size(ml)</th><th >MRP</th><th>Quantity</th><th width=10% >WSP P/B</th><th width=10% >Duty Paid</th><th width=15%>Amount</th></tr>";
				

				// print_r($data);
				$tcs=0;
				$row_count=count($data);
				$wsp_cal=0;
				$revnue_exc=0;
				$rate_rc= $OUTLET=='HOTEL'?0.3:0.2;
				$rate_tcs = $OUTLET=='CLUB'?0:0.01;

				for($i=0;$i<$row_count;$i++){
					$qty = $data[$i]['CASE_QUANTITY']*$data[$i]['PACK_SIZE'];
					
					$mrp = $data[$i]['MRP'];
					 $wsp = $data[$i]['WSP'];
					 $sum_excise += $data[$i]['EXCISE_DUTY']*$qty;
					 // "<br>";
					 $custom = $data[$i]['CUSTOM_DUTY'];
					 // "<br>";
					 $cost = $wsp+$custom;
					 $packSize = $data[$i]['PACK_SIZE'];
					 $bottles = $packSize==1?'Btl.':'case';
					$rate = number_format($cost*$data[$i]['PACK_SIZE'],2,'.','');
					
					$amount = number_format($rate*$data[$i]['CASE_QUANTITY'],2,'.','');
					
					 $html .=("<tr><td class='no-top'>".($i+1)."</td><td colspan=4 class='no-top-left'>".$data[$i]['BRAND_NAME']."</td><td align=center  >".$data[$i]['SIZE_VALUE']."</td>
					 <td class='no-top-left' >".$data[$i]['MRP']."</td><td class='no-top-left' >".$data[$i]['CASE_QUANTITY']." $bottles</td>
					 <td class='no-top-left' >".$wsp."</td><td class='no-top-left' >".$custom."</td><td align=right class='no-top-left'>".$amount."</td><tr>");
					 
					 $sum_quantity += $data[$i]['CASE_QUANTITY'];
					 $wsp_cal += round($data[$i]['WSP'],2)*$qty;
					 $revnue_exc += round($data[$i]['WSP']*$rate_rc,2)*$qty;
					 $sum_total_amount += $amount;
				}
				$revnue_exc=0;
				// echo $wsp_cal = round($wsp_cal*0.2,2);
				// exit;
				//  $revnue_exc = $OUTLET=='HOTEL'?$wsp_cal*0.30:$wsp_cal*0.20;
				//  echo $revnue_exc;
				//  exit;
				 $sum_excise_total = number_format(($sum_excise +  $sum_total_amount),2,'.','');
				 $total_before_vat = $sum_excise_total;
				 $vat = number_format(($total_before_vat*0.25),2,'.','');
				 $sum_vat_total = $total_before_vat+$vat;
				 $total_after_vat=$sum_vat_total-$revnue_exc;
				 $total_tcs = $total_after_vat*$rate_tcs;
				 $net_payable = number_format(($total_after_vat+$total_tcs),2,'.','');

				$html .="<tr><td class='only-left' colspan=7>&nbsp;</td><td class='no-border'></td><td class='no-border' align=right colspan=2><h3>Total:</h3><p>Excise Duty :</p></td><td align=right class='no-top'><p>".number_format((float)$sum_total_amount, 2, '.', '')."</p><p>".number_format((float)$sum_excise, 2, '.', '')."</p></td></tr>";
				// $html .="<tr><td class='only-left' align=right colspan=5>Add</td><td class='no-border' align=right colspan=4><p>Revenue Difference of HCR deposited by customer:</p></td><td align=right class='no-top'><p>".number_format($revnue_exc,2,'.','')."</p></td></tr>";
				$html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=5><p><b>Total:</b></p><p>Output VAT @25%:</p></td><td align=right class='no-top'><p>".number_format($total_before_vat,2,'.','')."</p><p>".number_format((float)($vat), 2, '.', '')."</p></td></tr>";

				// $html .="<tr><td class='only-left' align=right colspan=5>&nbsp;<p>Less</p></td><td class='no-border' align=right colspan=4>Total:<p>Revenue Difference of HCR deposited by customer :</p></td><td align=right class='no-top'><p>".number_format((float)$sum_vat_total, 2, '.', '')."</p><p>".number_format($revnue_exc, 2, '.', '')."</p></td></tr>";

				$html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=5><p><b>Total:</b></p><p>TCS:</p></td><td align=right class='no-top'><p>".number_format($total_after_vat,2,'.','')."</p><p>".number_format((float)($total_tcs), 2, '.', '')."</p></td></tr>";
				
				

				$html .="<tr><td class='no-top no-right' colspan=9><p><b>Amount (in words) :</b><em> ".ucfirst(digit_to_word($net_payable))." Only</em></p></td><td class='no-top no-left' align=right colspan=1><b>Total:</b><td align=right class='no-top'><b>".number_format((float)($net_payable), 2, '.', '')."</b></td></tr>";

				while($i<8){
					 $html .=("<tr><td class='no-top-bottom' colspan=11>&nbsp;</td><tr>");
					 $i++;
				}	
				

				
							
				
				$html .="<tr><td colspan=7 style='border-right: none; text-align: left;'>Received_____________________Cases In Good Order & Condition<br><br>Receiver's Signature___________________</td><td colspan=4 class='text-right' style='border-left: none'>For <b>$COMPANY_NAME</b><br><br><br>Authorised Signatory<br><br><br><img src='$tp_num.png' width=150 /><br>$tp_num</td></tr>";
				$html.="</table></td></tr>";
				
				$html.="</table>";
			}
				// echo $html;
				// exit;

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
				</ol>

				</td></tr>
        <tr><td  class=' no-border' align=center  colspan=9>This is a computer-generated invoice.</td></tr>
        </table>";
				$xyz++;
				if($xyz<$len)
				$html = $html."<pagebreak>";
				
				$html_all .= $html;
			}
				$file="Invoice_All.pdf";
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
		

?>