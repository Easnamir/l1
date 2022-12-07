<header>
		<div class=" w3-container w3-card w3-pale-green">
		<div class="w3-col l3 s2"><a href="./"><img src="images/logoipos.png" height="60"></a></div>
		<div class="w3-col l6 s10 w3-center w3-small w3-padding">
			<h3 style="font-weight: bold; line-height: 22px;"> <?php echo $_SESSION['COMPANY_NAME']; ?> </h3>
			<p><?php echo $_SESSION['ADDRESS']." - ".$PIN ?></p>
		</div>
		<div class="w3-col l3 s12">
			<div class="w3-row">
			<div class="w3-right w3-small w3-padding-small w3-margin-top">
				<div class="w3-col l12 s6 ">Time: <span id="txt"></span>  &nbsp;<a class="w3-margin-right w3-right"  style="font-weight: bold; cursor: pointer; text-decoration: underline; color: blue;"  onclick="location.href='logout.php'"> Logout</a></div>				
				<div class="w3-col l12 s6">			
				</div>
			</div>
			</div>
		</div>
		<!-- <marquee scrollamount="10" onmouseover="this.stop();" onmouseout="this.start();" ><span class="w3-text-red">For support contact on: 0120-4108475</span></marquee> -->
	</div> 
	<div class="w3-small menu_back w3-card-4" id="menu" style=" border-bottom:  2px solid green; border-top: 2px solid green;">
		<a href="./" class="w3-bar-item w3-button"><i class="fa fa-home"></i> Home</a>	
		<?php if($_SESSION['privilege']=='REPORT'){}
		else{?>
		<div class="w3-dropdown-hover">
			<button class="w3-button">Admin</button>
			<div class="w3-dropdown-content w3-bar-block w3-card-4">
		<a href="Sales_man_creation.php" class="w3-bar-item w3-button tohide">Add Sales Man </a>
		<a href="shop_creation.php" class="w3-bar-item w3-button tohide">Vend Configuration </a>
		<a href="item_configuration.php" class="w3-bar-item w3-button tohide">Item Configuration</a>
		<a href="performa_invoice_print.php" class="w3-bar-item w3-button">Proforma Invoice</a>
		<a href="proforma_invoice.php" class="w3-bar-item w3-button">Proforma Manual</a>	
		<a href="proforma_invoice_master.php" class="w3-bar-item w3-button">Proforma Master</a>	
		
		<a href="tally.php" class="w3-bar-item w3-button">Tally Report</a>	
		<a href="po_creation.php" class="w3-bar-item w3-button">IP Creation</a>	

		
	</div>
</div>

<div class="w3-dropdown-hover">
			<button class="w3-button">Operation</button>
			<div class="w3-dropdown-content w3-bar-block w3-card-4">
		
			
		<a href="po.php" class="w3-bar-item w3-button tohide"> PO Loading</a>
		<a href="lotwisechallan.php" class="w3-bar-item w3-button tohide">Lot Wise Challan</a>
		<a href="challan.php" class="w3-bar-item w3-button tohide">Shop Wise Challan</a>
		<a href="lotwiseinvoice.php" class="w3-bar-item w3-button tohide">Lot Wise Submission</a>
		<a href="Tpwiseinvoice.php" class="w3-bar-item w3-button tohide">TP Wise Submission</a>
		<a href="pending_po.php" class="w3-bar-item w3-button tohide">Pending PO Loading </a>
		<a href="challan_cancel.php" class="w3-bar-item w3-button tohide">Challan Cancel </a>
		<a href="cancel_invoice.php" class="w3-bar-item w3-button tohide">Invoice Cancel </a>
		<a href="hcr_invoice_generation.php" class="w3-bar-item w3-button tohide">HCR Invoice Creation</a>
		
		
	</div>
		</div>
		<div class="w3-dropdown-hover">
			<button class="w3-button">Account</button>
			<div class="w3-dropdown-content w3-bar-block w3-card-4">
				 <a href="challan_Status.php" class="w3-bar-item w3-button tohide">Challan status</a>
				 <a href="challan_Status_report.php" class="w3-bar-item w3-button tohide">Challan status report </a>
				 <a href="tp_Status.php" class="w3-bar-item w3-button tohide">Tp Status Portal</a>
				 <a href="invoice_payment_recieve.php" class="w3-bar-item w3-button tohide">Payment Received</a>
				 <a href="cn_creation.php" class="w3-bar-item w3-button tohide">Credit Note </a>
					<a href="list_of_cn.php" class="w3-bar-item w3-button tohide">Credit Note Print</a>
					<a href="dn_creation.php" class="w3-bar-item w3-button tohide">Debit Note </a>
					<a href="list_of_dn.php" class="w3-bar-item w3-button tohide">Debit Note Print</a>
					<a href="master_data_report.php" class="w3-bar-item w3-button tohide">Master Data Report </a>
					
			</div>
		</div>
		<?php } ?>
<div class="w3-dropdown-hover">
			<button class="w3-button">Reports</button>
			<div class="w3-dropdown-content w3-bar-block w3-card-4">
				 <a href="challan_list.php" class="w3-bar-item w3-button tohide">Print Challan</a>
				 <a href="challan_list_reprint.php" class="w3-bar-item w3-button tohide">Reprint Challan</a>
					<a href="old_Invoice_print.php" class="w3-bar-item w3-button tohide">Submission Reprint</a>
					<a href="Reprint_Invoice_print.php" class="w3-bar-item w3-button tohide">Print HCR Invoice</a>
					<a href="orderwisereport.php" class="w3-bar-item w3-button tohide">Order Register Report </a>
 					<a href="monthwisereport.php" class="w3-bar-item w3-button tohide">Month Wise Report </a>
					<a href="salereport.php" class="w3-bar-item w3-button tohide">Sale Register Report </a>
					<a href="challan_report.php" class="w3-bar-item w3-button">Challan Report </a>	
					<a href="invoice_report.php" class="w3-bar-item w3-button">Invoice Report </a>	
			</div>
		</div>
		
		
		<span class="w3-right  w3-margin-right w3-hide-small" style="font-weight: bold; line-height: 22px;">
			<span style="font-weight: normal; font-weight: bold;">Logged in As Roll:</span> <?php echo $_SESSION['privilege']; ?></span>
	</div>
</header>