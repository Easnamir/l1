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
	<title>Challan Creation</title>
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
			<div class="w3-col">
				<div class="w3-col l8">
					<h3>Challan List</h3>
					</div>
			

</div> 

			<div class="w3-col l12" >
				<div class="w3-border w3-border-grey" style="min-height:200px; max-height: 200px;  overflow:auto">
					<table class="w3-table w3-bordered w3-striped w3-card-4" border="1">
						<thead>
							<tr><th>SNo</th><th>Challan No</th><th>Challan Date</th><th>PO NO</th><th>Department</th><th>PO_Date</th><th>TP_NO</th><th>Vend Code</th><th>Vend Name</th><th>Case</th><th>Total</th><th style="text-align: left !important; padding-left: 10px !important; width: 100px;">  <input type="checkbox" name="checkAllchallan" id="checkAllchallan" value="" onclick="checkAll(this,'tp_challan[]')"/> All</th></tr>	
						</thead>
						<tbody id="show_item">
						</tbody>
					</table>
				</div>
				</div>
				<table class="w3-table w3-margin-bottom w3-margin-top" border="1">
					<!-- <tr><td colspan=12 style="height:1px !important; width:100%">&nbsp;</td></tr> -->
					<tr>
					<td style="width: 90%; font-weight: bold !important;">Print Challan</td>
					<td style="width: 10%;">
						<button type='button' onclick='printChallan()' class='w3-red w3-button w3-round'>Print</button>
					</td>
				</tr>
			</table>
			</div>
				
			<form method="POST" name="print_arr_form" id="po_print" action="print_challan_invoice.php?fun=printChallan"  target="_blank" >
				<input type="hidden" name="print_arr" id="hid_print" value="">
			</form>
		</div>
	</div>
</div>
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">

		const print_challanlist = () =>{
			var url = 'challandata.php?print_challan_list=print_challan_list';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('show_item').innerHTML=data;

				// console.log(data);
			})
		}
		print_challanlist();
		
		
		const Check_po_list=()=>{
			var input = document.getElementsByName('tp_no[]');
			var all = document.getElementById('all_check');
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
		}
		

		const printChallan = () =>{
			// var checkedItem = document.getElementById('item_body');
			var lists = document.getElementsByName('tp_challan[]');
			// console.log(typeof(lists));
			var tp_list = [];
			lists.forEach(function(list){
				if(list.checked==true) {
					tp_list.push(list.value);
				}
			})
			// console.log(tp_list);
			if(tp_list.length==0) {
				alert('Please select items to Print');
				return false;
			}
			var item_str = JSON.stringify(tp_list);
			
			var url = 'print_challan_invoice.php?fun=printChallan';
			
			document.getElementById('hid_print').value = item_str;
			document.getElementById('po_print').submit();
		window.location.reload();
				

		}
		

	</script>
</body>
</html>

