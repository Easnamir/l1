<?php
	include 'includes/session_company.php';
	$COMPANY_id = $_SESSION['COMPANY_id'];
	
	include 'includes/autoload.inc.php';
include 'includes/connect.php';
	$USER = $_SESSION['username'];


	
  ?>
<!DOCTYPE html>
<html>
<head>
	<title> Proforma invoice </title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style rel="stylesheet" >
		i{
			cursor: pointer;
		}

		select{
			width: 80%;
		}
		input,select {
			height: 25px;
		}
	
	</style>
</head>
<body>
	<?php
		include 'includes/header_company.php';	
	?>
	<div class="w3-container">
<div class="body-content w3-white w3-small">
	<div class="w3-container w3-margin-bottom">
		<div class="w3-row">
			<div class="w3-col">
				<div class="w3-col l8">
					<h3>Proforma invoice </h3>
					</div>
						<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom " style="margin-bottom: 3px!important;">
					<!-- <div class="w3-col l2 w3-padding-small"> </div> -->
                       
						
                  
                   <div class="w3-col l2 w3-padding-small">
						<label> Start Date </label>
						<input class="w3-input w3-border" type="date" name="startdate" id="startdate">
					</div>
					<div class="w3-col l2 w3-padding-small">
						<label> End Date </label>
						<input class="w3-input w3-border" type="date" name="enddate" id="enddate">
					</div>
						<div class="w3-container w3-center w3-col l2 w3-padding-small w3-margin-top">
						<button class="w3-button w3-round w3-red tohide" name="submit" type="Submit" id="submit" onclick="Proforma_invoicedata()">Submit</button>
						
					</div>

					</div>
					<div class="w3-col l12" style="max-height: 200px;  overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table w3-bordered w3-striped w3-card-4" border="1">
					<thead>
						<tr><th>SNo</th><th>PO_N0</th><th>Department</th><th>Case</th><th>wsp</th><th>Custom</th><th>excise</th><th>Vat</th><th>Tcs</th><th>Total</th><th width=12%>  Action </th></tr> 
					</thead>
						<tbody id="reprint_inv"  >
								</tbody>
				</table>
				</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">


 var enddate = document.getElementById('enddate');
		enddate.value= getTodaysDate();

		var startdate =document.getElementById('startdate');
	startdate.value = getFirstDay();
	 
		
		

const Proforma_invoicedata = () =>{
 
			var url = 'Proforma_invoicedata.php?Proforma_invoicedata=Proforma_invoicedata&&startdate='+startdate.value+'&enddate='+enddate.value;
			 // console.log(url);
			fetch(url).then(data=>data.text()).then(data=>{

				// console.log(data);
				document.getElementById('reprint_inv').innerHTML=data;				
			});

}

const printInvoicehcrlist = (po)=>{
         // console.log(tp);
         var url = 'performa_invoice_po.php?fun=performa_invoice_po&tp_num='+po;
         
          window.open(url);
       
           
         
      }

			const sendInvoicehcrlist = (po)=>{
        //  console.log(po);
         var url = 'performa_invoice_po_send.php?fun=performa_invoice_po&tp_num='+po;
        //  var popout = window.open(url);
					// window.setTimeout(function(){
					// 		//popout.close();
					// }, 1000);   

					fetch(url).then(data=>data.text()).then(data=>{
						if(data.includes('Message Sent Successfully')){
							alert('Message Sent Successfully!!');
						}
						else{
							alert('Message Could not be Sent. Please try again!!');
						}
					})
      }


	</script>
</body>
</html>

