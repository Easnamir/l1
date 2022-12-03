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
	<title>PO Creation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style>
		select{
			width: 80%;
		}
		input,select, button{
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
			<div class="w3-col-12">
				<div class="w3-col l3">
					<h3>Generation Invoice</h3>
					</div>
					<div class="w3-col l3 w3-padding">
					<select id='vend_type' onchange="window.location.href='create_invoice_private.php'">
						<option value="Corporation" selected>Corporation</option>
						<option value="Private">Private</option>
					</select>
		</div>
	</div>
	<div class="w3-col l2 w3-padding">
						<button class="w3-button w3-round w3-red  "  onclick="window.location.href='Po.php'">GoTo Generation PO  </button>
						
					</div>
               <div class="w3-row w3-padding ">
					<div class="w3-col l3">
						<label>From Date</label>
						<input class="w3-input w3-border" style="width:80%" type="date" name="from_date" id="from_date">
					</div>
					<div class="w3-col l3">
						<label>To Date</label>
						<input class="w3-input w3-border" style="width:80%" type="date" name="to_date" id="to_date">
					</div>
					<div class="w3-col l4">
						<label>Vend Name</label>
						<select style="width:80%" class="w3-select w3-border" id="vend_id">
							<option value="" hidden>Select vend name</option>
				
							 <?php 
							 $sql1 = "select  VEND_CODE,VEND_NAME+'('+VEND_CODE+')' as VEND_NAME from POPS_VEND_DETAILS where DEPARTMENT='Corporation'";
	              $stmt1 = sqlsrv_query($conn, $sql1);
							 while($row1 = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
 
							    	?>
								<option value="<?php echo $row1['VEND_CODE']; ?>"><?php echo $row1['VEND_NAME']; ?></option>
							<?php } ?>
						</select>
						
					
			</div>
			<div class="w3-col l2">
						<button class="w3-button w3-round w3-red w3-margin-top " name="submit" type="Submit" id="submit" onclick="createinvoice()">Submit</button>
						
					</div>
					
		</div>



                 <div class="w3-col l12" style="max-height: 200px;  overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table" border="1">
					<thead>
						<tr><th>SNo</th><th>Inv_No</th><th>Inv_Date</th><th>Vend Code</th><th>Vend Name</th><th>wsp</th><th>Excise</th><th>Total</th><th style="text-align: left !important; padding-left: 10px !important; width: 100px;">  Action </th></tr>	
					</thead>
						<tbody id="show_item_gov"  >
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



		
		const list_item_gov = () =>{
			var url = 'update-brand.php?list_item_gov=list_item_gov';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('show_item_gov').innerHTML=data;

				// console.log(data);
			})
		}
		list_item_gov();
		
		

		// const GenerateInvoice = (tp)=>{
		// 	// console.log(tp);
		// 	var url = 'update-brand.php?fun=GenerateInvoice&tp_num='+tp;
		// 	// console.log(url);
			
		// 	fetch(url).then(data=>data.text()).then(data=>{
		// 		// document.getElementById('item_body').innerHTML=data;
		// 		alert(data);
		// 		window.location.reload();
		// 	})
		// }

		const printInvoice1 = (invoice)=>{
			// console.log(invoice);
			var url = 'print_tp_invoice_gov.php?fun=printInvoice1&invoice='+invoice;
			console.log(url);
			window.open(url,'_blank');
			// var url = 'update-brand.php?fun=printInvoice1&invoice='+tp;
			// fetch(url).then(data=>data.text()).then(data=>{
			// 	// document.getElementById('item_body').innerHTML=data;
			// 	alert(data);
			// 	window.location.reload();
			// });
		}




		
		


         
		var today = document.getElementById('to_date');
		today.value= getTodaysDate();

	var firstday =document.getElementById('from_date');
	firstday.value = getFirstDay();


const createinvoice = () =>{
	
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var vend_id = document.getElementById('vend_id');
	if(vend_id.value=="")
	{
		alert("Please Select vend Name");
		return false;
	}

	var url = 'update-brand.php?createinvoice=createinvoice&vend_id='+vend_id.value+'&fromdate='+fromdate.value+'&todate='+todate.value;
 console.log(url);
	fetch(url).then(data=>data.text()).then(data=>alert(data));
}



	</script>
</body>
</html>

