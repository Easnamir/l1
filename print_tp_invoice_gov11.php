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
$sql2="select DEPARTMENT_NAME,CURRENT_ADDRESS,c.PAN,c.TIN,INVOICE_NO,b.DEPARTMENT,INVOICE_DATE,PO_NO,PO_DATE,TP_DATE,SUPPLY_DATE,BRAND_NAME,SIZE_VALUE,sum(CASE_QUANTITY) as CASE_QUANTITY ,sum(BOTTLE_QUANTITY)BOTTLE_QUANTITY,(WSP+CUSTOM_DUTY) as WSP,EXCISE_DUTY as EXCISE_DUTY ,MRP

from POPS_DISPATCH_ITEMS a join POPS_VEND_DETAILS b on a.vend_code=b.VEND_CODE join POPS_DEP_DETAILS c on b.DEPARTMENT=c.DEPARTMENT
where  INVOICE_NO='$invoice' 
group by INVOICE_NO,b.DEPARTMENT,INVOICE_DATE,SUPPLY_DATE,BRAND_NAME,SIZE_VALUE,TP_DATE,PO_NO,PO_DATE,EXCISE_DUTY,WSP,CUSTOM_DUTY,MRP,DEPARTMENT_NAME,CURRENT_ADDRESS,c.PAN,c.TIN";            
				$data=[];
				$stmt2 = sqlsrv_query($conn,$sql2);
				$html="<table width=100% border=0>
				<tr><td colspan=2 align=center><h3>Tax Invoice</h3></td></tr>
				<tr><td colspan=2 ><h2>$COMPANY_NAME</h2></td></tr>
				<tr><td colspan=2>Regd. Address: $COMPANY_ADDRESS -$C_pin</td></tr>
				<tr><td colspan=2>CIN: $C_CIN, PAN: $C_Pan</td></tr>
				<tr><td colspan=2>TIN: $C_TIN, GST: $GST_NUMBER</td></tr>
				
				<tr><td colspan=2>Email: $C_EMAIL, Contact: $C_PHONE</td></tr>
				
				<tr><td colspan=2>";
					while($row2 = sqlsrv_fetch_array($stmt2,SQLSRV_FETCH_ASSOC)){
					
					$data[]=$row2;
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
					// $pin_cust = $row2['PIN_CODE'];
					$tp_num = strtoupper($row2['TP_NO']);
					$po_num = strtoupper($row2['PO_NO']);
					$inv_no =strtoupper($row2['INVOICE_NO']);
					$TIN_NO = $row2['TIN'];
				}
				// print_r($data);
				// exit;
				
					$html .="<table border=1 width=100% cellspacing=0 cellpadding=6 style='font-size: 12px'>
					<tr><td align=left width=50%>
					<b>Dept:</b>$OUTLET <br><b>M/S  $businessName </b>
					<br><b>Address :</b> $businessAddress
					<br><b>TIN :</b>  $TIN_NO &nbsp;&nbsp;&nbsp;&nbsp; <b>PAN </b>: $pan
					
					</td><td align=left >
						Invoice No : $inv_no &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						Invoice Date   : $dispatch_date
						 <br>PO No. &nbsp;   : $po_num &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
						PO Date   &nbsp;&nbsp;      : $PO_DATE						
					</td></tr>
					</table>
				</td></tr>
				<tr><td colspan=2>
				<table border=1 cellspacing=0 cellpadding=1 width=100%>
				<tr><th width=6%>Sno.</th><th colspan=4>Particulars</th><th >Size</th><th >Case Qty</th><th width=10%>MRP</th><th width=10%>WSP per/C</th><th width=15%>Amount</th></tr>";

				$row_count=count($data);
				for($i=0;$i<$row_count;$i++){
					$qty = $data[$i]['CASE_QUANTITY'];
					$pack_size=($data[$i]['BOTTLE_QUANTITY']/$data[$i]['CASE_QUANTITY']);
					$mrp = $data[$i]['MRP'];
					 $wsp = $data[$i]['WSP'];
				      
					 $sum_excise += ($data[$i]['EXCISE_DUTY']*$data[$i]['CASE_QUANTITY']*$pack_size);
					
					 $custom = $data[$i]['CUSTOM_DUTY'];
			
					 $cost = $wsp+$custom;
		
					$rate = number_format(($cost*$pack_size),2,'.','');
					
					$amount = number_format($rate*$qty,2,'.','');
					
					 $html .=("<tr><td class='no-top'>".($i+1)."</td><td colspan=4 class='no-top-left'>".$data[$i]['BRAND_NAME']."</td><td class='no-top-left'>".$data[$i]['SIZE_VALUE']."</td><td class='no-top-left'>".$data[$i]['CASE_QUANTITY']."</td><td class='no-top-left'>".$data[$i]['MRP']."</td><td class='no-top-left'>".$rate."</td><td align=right class='no-top-left'>".$amount."</td><tr>");
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

				$html .="<tr><td class='only-left' colspan=5>&nbsp;</td><td class='no-border' align=right colspan=4><h3>Net Payable:</h3><td align=right class='no-top'><p>".number_format($net_payable, 2, '.', '')."</p></td></tr>";

				$html .="<tr><td class='only-left' align=left colspan=8>
				<p>Amount in words : ".digit_to_word($net_payable)."</b></p>
				</td><td class='no-border' align=right colspan=1><td align=right class='no-top-bottom'></td></tr>";

				
							
				
				$html .="<tr><td colspan=7 style='border-right: none'>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td colspan=3 class='text-right' style='border-left: none'>For <b>$COMPANY_NAME</b><br><br><br>Authorised Signatory</td></tr>";
				$html.="</table></td></tr>";
				
				$html.="</table>";
				// echo $html;
				// exit;

				$html .="<table border=1 cellspacing=0 cellpadding=1 width=100%><tr><td class=' no-top terms' align=left colspan=8>
				<p>*This is supply of alcohol for human consumption which is not subject to GST. As per CBEC, Govt. of India Notification No. 3/2018 dated 23rd January, 2018,E way Bill is not applicable on transportation of alcoholic liquor for human consumption.</p>
				<p><b>Terms & Conditions:</b></p>
				<ol>
				<li>Payments against invoice to be made either by crossed demand draft/ cheque / Bank Transfer in the name of the Company only.</li>
				<li> Interest @18% p.a will be charged if invoice is not paid or is partly paid by due date.</li>
				<li>The credit facility/limit depends upon the security deposit, our association and payment track report of customer.</li>
				<li>Goods once sold will neither be taken back nor be exchanged.</li>
				<li>Seller will not be responsible for any transit damage, loss or leakage.</li>
				<li>After delivery the customer is responsible for any loss or non compliance.</li>
				<li>The updated KYC details of customer is mandatory.</li>
				<li>Dishonored cheque will attract penal interest @ 24% p.a. including bank charges of Rs 200. It may also result in withdrawal of service.</li>
				<li>Our prices and business terms are within Delhi.</li>
				<li>Goods once received by customer will mean goods received intact with barcode and without any breakage, shortage or leakage.</li>
				<li>All disputes are subject to Delhi jurisdiction.</li>
				<li> Any Discrepancy in the invoice has to be brought into our notice within 7 days.</li>
				
				<li>This is a computer-generated invoice. </li>
				</ol>

				</td></tr></table>";

				// echo $html;
				// exit;
				$html = $html."<pagebreak>".$html;
				$file="Invoice-".$tp_num.".pdf";
				$stylesheet = file_get_contents('css/dispatch-inv.css');
				$mpdf = new \Mpdf\Mpdf();
				$mpdf->setTitle($file);
				$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
				$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
				// $mpdf->Output();
				$mpdf->Output($file,'D');
		}
		

?>