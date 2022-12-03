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
// extract($_POST);
// $invoice_arr = explode('#',$invoice_list);
// // var_dump($invoice_arr);
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



		if(isset($_GET['fun']) && $_GET['fun']=='printInvoicecn'){
			 $cn_id = $_GET['id'];
  $sql2="select b.DEPARTMENT_NAME as DEPARTMENT_NAME, DEPARTMENT_NAME as VEND_NAME,b.CURRENT_ADDRESS as VEND_ADDRESS
,b.TIN as TIN ,b.PAN as PAN_NO,a.*
from POPS_CN_DETAILS a 
left join POPS_DEP_DETAILS b  on b.DEPARTMENT_NAME=a.VEND_NAME where CN_DETAILS_PK='$cn_id'

union 


select isnull(DEPARTMENT_NAME,b.DEPARTMENT) as DEPARTMENT_NAME, isnull(DEPARTMENT_NAME,b.VEND_NAME) as VEND_NAME,isnull(c.CURRENT_ADDRESS,b.VEND_ADDRESS) as VEND_ADDRESS
,isnull(c.TIN,b.TIN) as TIN ,isnull(c.PAN,b.PAN_NO) as PAN_NO,a.*
from POPS_CN_DETAILS a join POPS_VEND_DETAILS b on a.VEND_CODE=b.VEND_CODE
left join POPS_DEP_DETAILS c  on c.DEPARTMENT=b.DEPARTMENT where CN_DETAILS_PK='$cn_id'";
				$data=[];
				$stmt2 = sqlsrv_query($conn,$sql2);

				while($row2 = sqlsrv_fetch_array($stmt2,SQLSRV_FETCH_ASSOC)){
					// print_r($row2);
					$data[]=$row2;
					// $html .= $row2['LICENCE_CODE'];
					$businessName = $row2['VEND_NAME'];
			
					$OUTLET = $row2['DEPARTMENT_NAME'];

					$businessAddress = $row2['VEND_ADDRESS'];
					$pan = $row2['PAN_NO'];
					$cn_date = $row2['CN_DATE']->format('d M Y');
					$cn_month = $row2['MONTH']->format('F,Y');
					
					$inv_no =strtoupper($row2['CN_NO']);
					$Reference=$row2['Reference'];
				
					$TIN_NO = $row2['TIN'];
					$Narration=$row2['Narration'];
					$amount=$row2['value'];
				}
 
				$html="<table width=100% border=1 cellspacing=0>
				<tr>
        $td1
        
        <h3 class='center_text under_line'>Credit Note</h3>
        <h2 class='center_text'>$COMPANY_NAME</h2>
        <p class='center_text'>
        $COMPANY_ADDRESS - $C_pin<br>
        FSSAI: $C_CIN, PAN: $C_Pan<br>
        TIN: $C_TIN, GST: $GST_NUMBER<br>
        Email: $C_EMAIL, Contact: $C_PHONE
        </p>
        </td></tr>
				<tr><td colspan=2>";

				$html .="<table border=1 width=100% cellspacing=0 cellpadding=6 style='font-size: 12px'>
					<tr><td align=left width=50%>
					<b> Dept:</b> $OUTLET 
					 <br> <b> M/S:</b> $businessName 
                      
					<br> $businessAddress,$pin_cust
					<br><b>TIN : </b> $TIN_NO &nbsp;&nbsp;&nbsp;&nbsp; <b>PAN :</b> $pan
					
					</td><td align=left >
						<b>Credit Note No :</b> $inv_no &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
					<br>	<b>Credit Note Date : </b></b> $cn_date
					<br><b> Reference : </b> $Reference 
					   
					<br>
						<b>For Month :</b> $cn_month
					
					</td></tr>
					</table>
				</td></tr>
				<tr><td colspan=2>
				<table border=1 cellspacing=0 cellpadding=1 width=100%>
				<tr><th colspan=6>Particulars</th><th>Amount</th></tr>";
				
$html .="<tr><td colspan=6 class='no-top'>".$Narration."</td><td align=right class='no-top-left'>".$amount."</td><tr>";
$html.="</table></td></tr>";
				
				$html.="</table>";
$html .="<div id='footer_tab'><table  border=1 cellspacing=0 width=100% ><tr><td colspan=3 align=left><b>CREDITED TO YOUR ACCOUNT</td><tr>";

$html .="<tr><td colspan=2 align=left class='no-top' ><b>
				<p>Amount in words :<br> ".digit_to_word(($amount))."</b></p>
				</td><td align=right class='no-top' ><b>".$amount."</td></tr>";
				$html .="<tr><td colspan=2 class='no-border' >&nbsp;</td><td  class='text-left no-border' >For <b>$COMPANY_NAME</b><br><br><br>Authorised Signatory</td></tr>";

				$html .="<tr><td class='no-border'  ><b>Prepared By:</td><td class='no-border'><b>Checked By:</td><td class='no-border' ><b>Approved By:</td><tr>";
				$html .="<tr><td colspan=3 class='no-border'>&nbsp;</td><tr>";
				$html .="<tr><td colspan=3 class='no-border'>&nbsp;</td><tr>";

				$html .="<tr><td colspan=3 class='no-border' align=center>This is a Computer Generated Credit Note</td><tr></table></div>";
				
				$html .="<div></div>";	
		// echo $html;
		// 		exit;

			  }
			  $file="Invoice-CN.pdf";
				$stylesheet = file_get_contents('css/dispatch-inv1.css');
				// $mpdf = new \Mpdf\Mpdf();
				$mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4']);
				$mpdf->setTitle($file);
				// if(!$inv_status){
				// $mpdf->SetWatermarkText('CANCELLED');
				// $mpdf->showWatermarkText = true;
				// }
				$mpdf->WriteHTML($stylesheet,\Mpdf\HTMLParserMode::HEADER_CSS);
				$mpdf->WriteHTML($html,\Mpdf\HTMLParserMode::HTML_BODY);
				// $mpdf->Output();
				$mpdf->Output($file,'D');

?>