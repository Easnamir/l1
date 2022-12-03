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
	<title>Submission Reprint</title>
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
					<h3>Submission Reprint</h3>
					</div>
						<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom " style="margin-bottom: 3px!important;">
					<!-- <div class="w3-col l2 w3-padding-small"> </div> -->
                       
						
                    <div class="w3-col l2 w3-padding-small">
							<label>Department<span class="w3-text-red">*</span></label>
              <select name="Department" class="w3-select" id="Department">
								<option value="">Select Department</option>
								
                        <?php $sqlc = "select DEPARTMENT_NAME,DEPARTMENT from POPS_DEP_DETAILS";
                  $stmtc = sqlsrv_query($conn,$sqlc);
                  while($rowc = sqlsrv_fetch_array($stmtc,SQLSRV_FETCH_ASSOC)){

                   ?>
                   <option value="<?php echo $rowc['DEPARTMENT'] ?>"><?php echo $rowc['DEPARTMENT_NAME'] ?></option>
                   <?php
                  }
                  ?>
							</select>
						</div>
                   <!-- <div class="w3-col l2 w3-padding-small">
						<label> Start Date </label> -->
						<input class="w3-input w3-border" type="hidden" name="startdate" id="startdate">
					<!-- </div>
					<div class="w3-col l2 w3-padding-small">
						<label> End Date </label> -->
						<input class="w3-input w3-border" type="hidden" name="enddate" id="enddate">
					<!-- </div> -->
					<div class="w3-col l2 w3-padding-small">
						<label> From </label>
						<input class="w3-input w3-border" type="number" name="from_sno" id="from_sno" placeholder="Enter Invoice No " value="0">
					</div>
					<div class="w3-col l2 w3-padding-small">
						<label> To </label>
						<input class="w3-input w3-border" type="number" name="to_sno " id="to_sno" placeholder="Enter Invoice No" value="0">
					</div>
						<div class="w3-container w3-center w3-col l2 w3-padding-small w3-margin-top">
						<button class="w3-button w3-round w3-red tohide" name="submit" type="Submit" id="submit" onclick="reprintinvoice()">Submit</button>
						
					</div>

					</div>
					<div class="w3-col l12" style="max-height: 200px;  overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table w3-bordered w3-striped w3-card-4" border="1">
					<thead>
						<tr><th>SNo</th><th>Inv_No</th><th>Inv_Date</th><th>Department</th><th>Case</th><th>wsp</th><th>custom duty</th><th>excise</th><th>Vat</th><th>Tcs</th><th>Total</th><th style="text-align: left !important; padding-left: 10px !important; ">   <input type="checkbox" name="check_all" onclick="checkAll(this,'tp_invoice[]')" id="check_all"> All  </th></tr>	
					</thead>
						<tbody id="show_item_inv"  >
								</tbody>
				</table>
				</div>
				</div>
				
			</div>
			<!-- <br> -->
				<form method="post" action="print_tp_invoice_gov.php" id="invoice_form">
					<input type="hidden" name="invoice_list" id="invoice_list">
				</form>
				<button class="w3-button w3-red w3-right" onclick="printAllInvoice()" style="margin-top: 20px;">Print</button>
		</div>
	</div>
</div>
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
function checkAll(obj,elementname){
			// console.log(obj.checked);
			var input = document.getElementsByName(elementname);
			if(obj.checked == true){
				input.forEach(item=>{
					
					item.checked = true;
					
				})
			}
			else{
				input.forEach(item=>{
					item.checked = false;
				})
			}
		}
		function checkAllChecked (element){
			let all_checked_box = document.getElementById('check_all');
			let input = document.getElementsByName(element);
			let all_checked=true;
			input.forEach(item=>{
				if(item.checked==false){
					all_checked = false;
				}
			})
			if(all_checked){
				all_checked_box.checked=true;
			}
			else{
				all_checked_box.checked=false;
			}
		}
 var enddate = document.getElementById('enddate');
		enddate.value= getTodaysDate();

		var startdate =document.getElementById('startdate');
	startdate.value = getFirstDay();
	 
		// const Show_invoice_list = () =>{
		// 	var url = 'invoicedata.php?Show_invoice_list=Show_invoice_list';
		// 	 // console.log(url)
		// 	fetch(url).then(data=>data.text()).then(data=>{
		// 		document.getElementById('show_item_inv').innerHTML=data;

		// 		// console.log(data);
		// 	})
		// }
		// Show_invoice_list();
		
		const printInvoice1 = (invoice)=>{
			// console.log(tp);
			var url1 = 'print_tp_invoice_gov.php?fun=printInvoice1&invoice='+invoice;
			// var url2 = 'table.php?invoice='+invoice;

			// console.log(url);
			window.open(url1);
			
			
		}
		const printInvoice2 = (invoice)=>{
			// console.log(tp);
			// var url1 = 'print_tp_invoice_gov.php?fun=printInvoice1&invoice='+invoice;
			var url2 = 'table.php?invoice='+invoice;

			// console.log(url);
			window.open(url2);
			
			
		}
		function printAllInvoice(){
	let input = document.getElementsByName('tp_invoice[]');
	let count =0;
	let invoices = [];
	input.forEach(item=>{
		if(item.checked==true){
			count++;
			invoices.push(item.id)
		}
	})
	let new_inv = invoices.join('#');
	// console.log(new_inv);
	document.getElementById('invoice_list').value= new_inv;
	document.getElementById('invoice_form').submit();
}
const reprintinvoice = () =>{
 var Department=document.getElementById('Department');
 var to_sno=document.getElementById('to_sno');
 var from_sno=document.getElementById('from_sno');
	if(Department.value==''){
		alert("Please select Department ");
		return false;
	}
	if(to_sno.value=='' || from_sno.value==''){
		alert("Please select invoice no ");
		return false;
	}
	 if(to_sno.value==0 || from_sno.value==0){
		alert("Please select  valid invoice no ");
		return false;
	}
	
	 if(from_sno.value > to_sno.value){
		alert("Please select  valid invoice no ");
		to_sno.value=0;
		return false;
	}
	
			var url = 'invoicedata.php?invoice=invoice&&startdate='+startdate.value+'&enddate='+enddate.value+'&Department='+Department.value+'&to_sno='+to_sno.value+'&from_sno='+from_sno.value;
			 // console.log(url);
			fetch(url).then(data=>data.text()).then(data=>{

				// console.log(data);
				document.getElementById('show_item_inv').innerHTML=data;
				
				
			});

}
		

// const cancelInvoice = (po_num)=>{
// 			// console.log(tp);
// 			var url = 'update-brand.php?fun=cancelInvoice&po_num='+po_num;
// 			// console.log(url);
// 			if(!confirm('Are you sure you want to cancel Invoice?')){
// 				return false;
// 			}
// 			fetch(url).then(data=>data.text()).then(data=>{
// 				// document.getElementById('item_body').innerHTML=data;
// 				alert(data);
// 				window.location.reload();
// 			})
// 		}

		

	</script>
</body>
</html>

