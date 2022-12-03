<?php
require_once __DIR__ . '/vendor/autoload.php';

include 'includes/fun.php';
session_start();
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

  $td1 = "<td colspan=2 align=center style='background-image: url($logo); background-image-resize: 2;
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

if(isset($_GET['fun']) && $_GET['fun']=='printInvoice1'){
	$invoice = $_GET['invoice'];
	 $sql2=" select DEPARTMENT_NAME,CURRENT_ADDRESS,c.PAN,c.TIN,b.DEPARTMENT,
a.BRAND_NAME,a.SIZE_VALUE,
sum(CASE_QUANTITY) as CASE_QUANTITY,
sum(BOTTLE_QUANTITY)BOTTLE_QUANTITY,(a.WSP) as WSP,a.CUSTOM_DUTY,EXCISE_DUTY as EXCISE_DUTY ,
a.MRP,d.LIQUOR_TYPE_CD,d.PACK_SIZE from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE 
join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT 
join POPS_PRICE_MASTER d on a.BRAND_CODE=d.BRAND_CODE
where INVOICE_NO='$invoice' 
group by a.BRAND_NAME,a.SIZE_VALUE,b.DEPARTMENT,
EXCISE_DUTY,a.WSP,a.CUSTOM_DUTY,a.MRP,DEPARTMENT_NAME,
CURRENT_ADDRESS,c.PAN,c.TIN,d.LIQUOR_TYPE_CD,d.PACK_SIZE"; 
// exit;
$data=[];
				$stmt2 = sqlsrv_query($conn,$sql2);

				 $sql12=" select distinct INVOICE_DATE,PO_NO,PO_DATE,TP_DATE,SUPPLY_DATE,INVOICE_NO,DEPARTMENT_NAME,CURRENT_ADDRESS,LIQUOR_TYPE_CD,c.PAN,c.TIN from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE 
join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT 
join POPS_PRICE_MASTER d on a.BRAND_CODE=d.BRAND_CODE
where INVOICE_NO='$invoice' "; 
// exit;
$data=[];
				$stmt12 = sqlsrv_query($conn,$sql12);
				$html="<table width=100% border=1>
				<tr>$td1<h3>Tax Invoice</h3>
        <h2>$COMPANY_NAME</h2> $COMPANY_ADDRESS -$C_pin<br>
        FSSAI: $C_CIN, PAN: $C_Pan<br>
        TIN: $C_TIN, GST: $GST_NUMBER<br>
        Email: $C_EMAIL, Contact: $C_PHONE</td></tr>
				
				<tr><td colspan=2>";
					while($row2 = sqlsrv_fetch_array($stmt12,SQLSRV_FETCH_ASSOC)){
					
					// $data[]=$row2;
					// print_r($row2);
					// exit;
					 $businessName = $row2['DEPARTMENT_NAME'];
					 // $vend_code = $row2['VEND_CODE'];
			
					$OUTLET = $row2['DEPARTMENT_NAME'];
					$businessAddress = $row2['CURRENT_ADDRESS'];
					$pan = $row2['PAN'];
					$dispatch_date = $row2['INVOICE_DATE']->format('d M Y');
					$tp_date = $row2['TP_DATE']->format('d M Y');				
					$PO_DATE = $row2['PO_DATE']->format('d M Y');
					$liqType = $row2['LIQUOR_TYPE_CD'];
					$tp_num = strtoupper($row2['TP_NO']);
					$po_num = strtoupper($row2['PO_NO']);
					$inv_no = strtoupper($row2['INVOICE_NO']);
					$TIN_NO = $row2['TIN'];
				}

	while($row3 = sqlsrv_fetch_array($stmt2,SQLSRV_FETCH_ASSOC)){

		$data[]=$row3;
	}
				// print_r($liqType);
				// exit;
				if($liqType==171){
					$html .="<table border=1 width=100% cellspacing=0 cellpadding=6 style='font-size: 12px; overflow: wrap;'>
					<tr><td align=left width=50%>
					<b>Dept:</b>$OUTLET <br><b>M/S  $businessName </b>
					<br><b>Address :</b> $businessAddress
					<br><b>TIN :</b>  $TIN_NO &nbsp;&nbsp;&nbsp;&nbsp; <b>PAN </b>: $pan
					
					</td><td align=left >
						Invoice No : $inv_no &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
						Invoice Date   : $dispatch_date<br>
						 <br>PO No. &nbsp;   : $po_num &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
						PO Date   &nbsp;&nbsp;      : $PO_DATE						
					</td></tr>
					</table>
				</td></tr>
				<tr><td colspan=2 class='no-top-bottom'>
				<table id='item_table' border=1 cellspacing=0 cellpadding=1 width=100%>
				<tr><th width=6%>Sno.</th><th colspan=4>Particulars</th><th width=12%>Size(ml)</th><th width=12% >Case Qty</th><th width=12%>MRP</th><th width=12%>WSP per/C</th><th width=16%>Amount</th></tr>";

				$row_count=count($data);
        $bottles = '';
				for($i=0;$i<$row_count;$i++){
					$qty = $data[$i]['CASE_QUANTITY'];
					// $pack_size=($data[$i]['BOTTLE_QUANTITY']/$data[$i]['CASE_QUANTITY']);
					$mrp = $data[$i]['MRP'];
					 $wsp = $data[$i]['WSP'];
					 $pack_size = $data[$i]['PACK_SIZE'];

				      
					 $sum_excise += ($data[$i]['EXCISE_DUTY']*$data[$i]['CASE_QUANTITY']*$pack_size);
					
					 $custom = $data[$i]['CUSTOM_DUTY'];
            
					 $cost = $wsp+$custom;
		
					$rate = number_format(($cost*$pack_size),2,'.','');
					
					$amount = number_format($rate*$qty,2,'.','');
					
					 $html .=("<tr><td class='no-top'>".($i+1)."</td><td colspan=4 align=left class='no-top-left'>".$data[$i]['BRAND_NAME']."</td><td class='no-top-left'>".$data[$i]['SIZE_VALUE']."</td><td class='no-top-left'>".$data[$i]['CASE_QUANTITY']." $bottles</td><td class='no-top-left'>".$data[$i]['MRP']."</td><td class='no-top-left'>".$rate."</td><td align=right class='no-top-left'>".$amount."</td><tr>");
					 $sum_quantity += $data[$i]['CASE_QUANTITY'];
					 $sum_total_amount += $amount;
					 
					 $excise += $sum_excise;
					 $tcs += ($data[$i]['TCS']*$qty);										
				}		
									 
				// exit;
				$total_amount = $sum_total_amount+$sum_excise;
				$vat = $total_amount*0.25;
				$final_amount = $total_amount+$vat;
				$tcs = 0;
				$net_payable = $final_amount-$sum_excise;
				$html .="<tr><td class='only-left' colspan=6>&nbsp;</td><td class='no-border'></td><td class='no-border' align=right colspan=2><h3>Goods Value:</h3></td><td align=right class='no-top'><p>".number_format((float)$sum_total_amount, 2, '.', '')."</p></td></tr>";
				$html .="<tr><td class='center only-left' colspan=5><b>Add</td><td class='no-border' align=right colspan=4><p><b>Excise Duty:</b></p><td align=right class='no-top'><p>".number_format($sum_excise,2,'.','')."</p></td></tr>";
				$html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=4><p><b>VAT:</b></p><td align=right class='no-top'><p>".number_format($vat,2,'.','')."</p></td></tr>";

				$html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=4><h3>Total:</h3><b><p>TCS :</b></p></td><td align=right class='no-top'><p>".number_format($final_amount, 2, '.', '')."</p><p>".number_format($tcs, 2, '.', '')."</p></td></tr>";

				
          $html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=4><h3>Total Amount:</h3><td align=right class='no-top'><p>".number_format($final_amount, 2, '.', '')."</p></td></tr>";

				$html .="<tr><td class='only-left' colspan=5><b> Less </b>&nbsp;</td><td class='no-border' align=right colspan=4><h3>Excise Revenue Received in Advance from Customer:</h3><td align=right class='no-top'><p>".number_format((float) $sum_excise, 2, '.', '')."</p></td></tr>";

				

				$html .="<tr><td class='no-top no-right' align=left colspan=8><p>Amount in words : ".digit_to_word($net_payable)."</b></p></td><td class='no-top no-right no-left' align=right><h3>Net Payable:</h3><td align=right class='no-top'><p>".number_format($net_payable, 2, '.', '')."</p></td></tr>";

				
while($i<11){
					 $html .=("<tr><td class='no-top-bottom' colspan=10>&nbsp;</td><tr>");
					 $i++;
				}	
				
							
				
				$html .="<tr><td colspan=7 align=left style='border-right: none'>
        Received_____________________Cases In Good Order & Condition<br><br>Receiver's Signature___________________
        </td><td colspan=3 class='text-right' style='border-left: none'>For <b>$COMPANY_NAME</b><br><br><br>Authorised Signatory</td></tr>";
				$html.="</table></td></tr>";
				
				$html.="</table>";
      }
				else if($liqType==172){
            $html .="<table border=1 width=100% cellspacing=0 cellpadding=6 style='font-size: 12px'>
					<tr><td align=left width=50% class='no-top-bottom no-left'>
					<b>Dept:</b>$OUTLET <br><b>M/S  $businessName </b>
					<br><b>Address :</b> $businessAddress
					<br><b>TIN :</b>  $TIN_NO &nbsp;&nbsp;&nbsp;&nbsp; <b>PAN </b>: $pan
					
					</td><td align=left class='no-top-bottom no-right' >
						<b>Invoice No :</b> $inv_no &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
						<b>Invoice Date   :</b> $dispatch_date
						 <br><b>PO No. &nbsp;   : </b>$po_num &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<br>
						<b>PO Date   &nbsp;&nbsp;      :</b> $PO_DATE						
					</td></tr>
					</table>
				</td></tr>
				<tr><td colspan=2 class='no-top-bottom'>
				<table id='item_table' border=1 cellspacing=0 cellpadding=1 width=100%>
				<tr><th width=6%>Sno.</th><th colspan=4>Particulars</th><th width=6%>Size(ml)</th><th width=6%>Quantity</th><th width=6%>MRP</th><th width=8%>WSP</th><th width=8%>Duty Paid</th><th width=10%>Amount</th></tr>";

				$row_count=count($data);
				for($i=0;$i<$row_count;$i++){
					$qty = $data[$i]['CASE_QUANTITY'];
					// $pack_size=($data[$i]['BOTTLE_QUANTITY']/$data[$i]['CASE_QUANTITY']);
					$mrp = $data[$i]['MRP'];
					 $wsp = $data[$i]['WSP'];
				      
					 $sum_excise += ($data[$i]['EXCISE_DUTY']*$data[$i]['CASE_QUANTITY']*$pack_size);
					
					 $custom = $data[$i]['CUSTOM_DUTY'];
           $pack_size = $data[$i]['PACK_SIZE'];
					 $cost = $wsp+$custom;
           $bottles = $pack_size==1?'Btl.':'case';
					$rate = number_format(($cost*$pack_size),2,'.','');
					
					$amount = number_format($rate*$qty,2,'.','');
					
					 $html .=("<tr><td class='no-top'>".($i+1)."</td><td colspan=4 class='no-top-left'>".$data[$i]['BRAND_NAME']."</td><td class='no-top-left'>".$data[$i]['SIZE_VALUE']."</td><td class='no-top-left'>".$data[$i]['CASE_QUANTITY']." $bottles</td><td class='no-top-left'>".$data[$i]['MRP']."</td><td class='no-top-left'>".$wsp."</td><td class='no-top-left'>".$custom."</td><td align=right class='no-top-left'>".$amount."</td><tr>");
					 $sum_quantity += $data[$i]['CASE_QUANTITY'];
					 $sum_total_amount += $amount;
					 
					 $excise += $sum_excise;
					 $tcs += ($data[$i]['TCS']*$qty);										
				}								 
				// exit;
				$total_amount = $sum_total_amount+$sum_excise;
				$vat = $total_amount*0.25;
				$final_amount = $total_amount+$vat;
				$tcs = 0;
				$net_payable = $final_amount-$sum_excise;
				$html .="<tr><td class='only-left' colspan=6>&nbsp;</td><td class='no-border'></td><td class='no-border' align=right colspan=3><h3>Goods Value:</h3></td><td align=right class='no-top'><p>".number_format((float)$sum_total_amount, 2, '.', '')."</p></td></tr>";
				$html .="<tr><td class='center only-left' colspan=5><b>Add</td><td class='no-border' align=right colspan=5><p><b>Excise Duty:</b></p><td align=right class='no-top'><p>".number_format($sum_excise,2,'.','')."</p></td></tr>";
				$html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=5><p><b>Output VAT @25%:</b></p><td align=right class='no-top'><p>".number_format($vat,2,'.','')."</p></td></tr>";

				$html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=5><h3>Total:</h3><b><p>TCS :</b></p></td><td align=right class='no-top'><p>".number_format($final_amount, 2, '.', '')."</p><p>".number_format($tcs, 2, '.', '')."</p></td></tr>";

				
          $html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=5><h3>Total Amount:</h3><td align=right class='no-top'><p>".number_format($final_amount, 2, '.', '')."</p></td></tr>";

				$html .="<tr><td class='only-left' colspan=5><b> Less </b>&nbsp;</td><td class='no-border' align=right colspan=5><h3>Excise Revenue Received in Advance from Customer:</h3><td align=right class='no-top'><p>".number_format((float) $sum_excise, 2, '.', '')."</p></td></tr>";

				// $html .="<tr><td class='only-left' align=left colspan=9><p>Amount (in words) : ".digit_to_word($net_payable)." Only</b></p></td><td class='no-border' align=right><h3>Total:</h3><td align=right class='no-top'><p><b>".number_format($net_payable, 2, '.', '')."</b></p></td></tr>";


				$html .="<tr><td class='no-top no-right' align=left colspan=8><p>Amount in words : ".digit_to_word($net_payable)."</b></p></td><td class='no-top no-right no-left' colspan=2 align=right><h3>Net Payable:</h3><td align=right class='no-top'><p>".number_format($net_payable, 2, '.', '')."</p></td></tr>";

				
while($i<11){
					 $html .=("<tr><td class='no-top-bottom' colspan=11>&nbsp;</td><tr>");
					 $i++;
				}	
				
				$html .="<tr><td colspan=8 align=left style='border-right: none'>
        Received_____________________Cases In Good Order & Condition<br><br>Receiver's Signature___________________
        </td><td colspan=3 class='text-right' style='border-left: none'>For <b>$COMPANY_NAME</b><br><br><br>Authorised Signatory</td></tr>";
				$html.="</table></td></tr>";
				
				$html.="</table>";


        }
				if($_SESSION['CINV']=='SVL'){
					$payment = 'Payment to be made in advance.';
				}
				else{
					$payment = 'Payment to be made on or before 60 days from date of invoice/receipt of goods (due date).';
				}

				$html .="<table border=1 cellspacing=0 cellpadding=1 width=100%><tr><td class=' no-top terms' align=left>
				
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
        <tr><td class=' no-border' align=center>This is a computer-generated invoice.</td></tr>
        </table>";

				// echo $html;
				// exit;
				// $html = $html."<pagebreak>".$html;
				$file="Invoice.pdf";
				$stylesheet = file_get_contents('css/dispatch-inv1.css');
				$mpdf = new \Mpdf\Mpdf();
				$mpdf->setTitle($file);
				$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
				$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
				// $mpdf->Output();
				$mpdf->Output($file,'D');
		}
		

?>