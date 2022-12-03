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
	<title>HCR Invoice Generation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style rel="stylesheet" >
		
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
			<div class="w3-col l9">
					<h3>HCR Invoice Generation</h3>
					</div>
<div class="w3-col l1 w3-margin-top">
						<label> Invoice Date </label>
						</div>
					<div class="w3-col l2 w3-margin-top">
						<input class="input" type="date" name="startdate" id="startdate" style="hight: 15px;">
					</div>

						<div class="w3-col l12">
				<div class="w3-border w3-border-grey" style="min-height: 200px; max-height: 200px;  overflow:auto">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
						<thead>
							<tr><th>SNo</th><th>PO NO</th><th>PO_Date</th><th>Department</th><th>TP No</th><th>Case</th><th>Total</th><th>Excise</th><th style="text-align: left !important; padding-left: 10px !important; width: 100px;">  <input type="checkbox" name="checkAllchallan" id="checkAllchallan" value="" onclick="checkAll(this,'tp_HCR[]')"/> All</th></tr>	
						</thead>
						<tbody id="show_item_hcr">
						</tbody>
					</table>
				</div>
				<table class="w3-table w3-margin-bottom" border="1">
					<!-- <tr><td colspan=12 style="height:1px !important; width:100%">&nbsp;</td></tr> -->
					<tr>
					<td width=70%>&nbsp;</td><td class="mid-text" width=15%>Selected Cases: <span id="total_case">0</span> </td>
					<td style="width: 25%;">
						<button type='button' onclick='processHCR()' class='w3-red w3-button w3-round'>Process</button>
					</td>
				</tr>
			</table>
			</div>
				
			</div>


				<div class="w3-col l12" >
				<div class="w3-border w3-border-grey" style="min-height: 200px;max-height: 200px;  overflow:auto">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
						<thead>
							<tr><th>SNo</th><th>Inv_No</th><th>Inv_Date</th><th>PO_N0</th><th>TP_N0</th><th>Department</th><th>Case</th><th>wsp</th><th>excise</th><th>Vat</th><th>Tcs</th><th>Total</th><th> Action</th></tr> 
						</thead>
						<tbody id="show_hcr_invoice">
						</tbody>
					</table>
				</div>
				</div>
				
			
		</div>
	</div>
</div>
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
		var startdate =document.getElementById('startdate');
	startdate.value = getTodaysDate();
	startdate.min = getTodaysDate();
		const Hcrwisepo = () =>{
			var url = 'challandata.php?hcr_wise_invoice=hcr_wise_invoice';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('show_item_hcr').innerHTML=data;

				// console.log(data);
			})
		}
		Hcrwisepo();

const processHCR = () =>{
		
			var lists = document.getElementsByName('tp_HCR[]');
			
			var tp_list = [];
			lists.forEach(function(list){
				if(list.checked==true) {
					tp_list.push(list.value);
				}
			})
			// console.log(tp_list);
			if(tp_list.length==0) {
				alert('Please select items to process');
				return false;
			}
			var item_str = JSON.stringify(tp_list);
			// console.log(item_str);
			var url = 'invoicedata.php?fun=HCR&startdate='+startdate.value+'&item_str='+item_str;
			 
			fetch(url).then(data=>data.text()).then(data=>{
				// document.getElementById('show_item').innerHTML=data;
// console.log(data);
				alert(data);
				window.location.reload();
			})
		}



		const print_hcrinvoice = () =>{
			var url = 'invoicedata.php?print_hcrinvoice=print_hcrinvoice';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('show_hcr_invoice').innerHTML=data;

				
			})
		}
		print_hcrinvoice();
const getSelectedCases = () =>{
			var input = document.getElementsByName('tp_HCR[]');
			var total_cases = 0;
			var case_id = document.getElementById('total_case');

			input.forEach(item=>{
					if(item.checked == true){
						total_cases += parseInt(item.dataset.case);
					}
				})
				// console.log(total_cases);
				case_id.innerHTML = total_cases;

		}
		
			function checkAll(obj,elementname){
			// console.log(obj.checked);
			var input = document.getElementsByName(elementname);
			if(obj.checked == true){
				input.forEach(item=>{
					if(item.disabled == false){
					item.checked = true;
					}
				})
			}
			else{
				input.forEach(item=>{
					item.checked = false;
				})
			}
			getSelectedCases();
		}
 const Check_po_list=()=>{
			var input = document.getElementsByName('tp_HCR[]');
			var all = document.getElementById('checkAllchallan');
			var all_checked = true;
			input.forEach(item=>{
					if(item.checked == false){
						all_checked = false;
					}
				})
			if(all_checked){
				all.checked= true;
			}
			else{
				all.checked= false;
			}
			getSelectedCases();
		}




const printInvoicehcr = (tp)=>{
         // console.log(tp);
         var url = 'print_tp_invoice.php?fun=printInvoicehcr&tp_num='+tp;
         var print = 'invoicedata.php?TP_num='+tp;
         
          window.open(url);
        // console.log(url);
       

          fetch(print).then(data=>data.text()).then(data=>{
           // console.log(data);       
            window.location.reload();
            
         })

           
         
      }



	</script>
</body>
</html>

