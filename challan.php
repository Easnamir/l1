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
			<div class="w3-col l9">
					<h3>Challan Generation</h3>
			</div>
					<div class="w3-col l1 w3-margin-top">
						<label> Challan Date </label>
						</div>
					<div class="w3-col l2 w3-margin-top">
						<input class="input" type="date" name="startdate" id="startdate" style="hight: 15px;">
					</div>
			<div class="w3-col l12 " >
				<div class="w3-border w3-border-grey" style="min-height: 200px; max-height: 200px;  overflow:auto">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
						<thead>
							<tr><th>SNo</th><th>PO NO</th><th>Department</th><th>PO_Date</th><th>TP_NO</th><th>Vend Code</th><th>Vend Name</th><th>Case</th><th>Total</th><th style="text-align: left !important; padding-left: 10px !important; width: 100px;">  <input type="checkbox" name="checkAllchallan" id="checkAllchallan" value="" onclick="checkAll(this,'tp_challan[]')"/> All</th></tr>	
						</thead>
						<tbody id="show_item">
						</tbody>
					</table>
				</div>			
			</div>
			<table class="w3-table w3-margin-bottom">
			<tr> <td width=70%>&nbsp;</td><td class="mid-text" width=15%>Selected Cases: <span id="total_case">0</span> </td>
					<td style="width: 15%;font-weight: bold !important;">Generate Challan</td>
					<td style="width: 15%;">
						<button type='button' onclick='processChallanshop()' class='w3-red w3-button w3-round'>Process</button>
					</td>
			</table>
			<div class="w3-col l12" >
				<div class="w3-border w3-border-grey" style="min-height: 200px; max-height: 200px;  overflow:auto">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
						<thead>
							<tr><th>SNo</th><th>Challan No</th><th>Challan Date</th><th>PO NO</th><th>Department</th><th>PO_Date</th><th>TP_NO</th><th>Vend Code</th><th>Vend Name</th><th>Case</th><th>Total</th><th style="text-align: left !important; padding-left: 10px !important; width: 100px;">  <input type="checkbox" name="checkAllchallan" id="checkAllchallan" value="" onclick="checkAll(this,'tp_challan_print[]')"/> All</th></tr>	
						</thead>
						<tbody id="show_lotwise">
						</tbody>
					</table>
				</div>
				</div>
				<table class="w3-table w3-margin-bottom w3-margin-top">
					<!-- <tr><td colspan=12 style="height:1px !important; width:100%">&nbsp;</td></tr> -->
					<tr>
					<td style="width: 90%; font-weight: bold !important;">Print Challan</td>
					<td style="width: 10%;">
						<button type='button' onclick='printChallan()' class='w3-red w3-button w3-round'>Print</button>
					</td>
				</tr>
			</table>
			<form method="POST" name="po_arr_form" id="po_form" action="#"  enctype="application/x-www-form-urlencoded" >
				<input type="hidden" name="po_arr" id="hid_po" value="">
			</form>



			<form method="POST" name="print_arr_form" id="po_print" action="print_challan_invoice.php?fun=printChallan"  target="_blank" >
				<input type="hidden" name="print_arr" id="hid_print" value="">
			</form>
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
	 
		const Show_item_list_challan = () =>{
			var url = 'challandata.php?list_item_challan=Show_item_list_challan';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('show_item').innerHTML=data;

				// console.log(data);
			})
		}
		Show_item_list_challan();


		const print_challan = () =>{
			var url = 'challandata.php?print_challan=print_challan';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('show_lotwise').innerHTML=data;

				// console.log(data);
			})
		}
		print_challan();
		
		const printChallan = () =>{
			// var checkedItem = document.getElementById('item_body');
			var lists = document.getElementsByName('tp_challan_print[]');
			
			var tp_list = [];
			lists.forEach(function(list){
				if(list.checked==true) {
					tp_list.push(list.value);
				}
			})
		
			if(tp_list.length==0) {
				alert('Please select items to Print');
				return false;
			}
			var item_str = JSON.stringify(tp_list);
			// console.log(item_str);
			// var url = 'print_challan_invoice.php?fun=printChallan';
			// console.log(url);
			// window.open(url,'_blank');

			document.getElementById('hid_print').value = item_str;
			document.getElementById('po_print').submit();
		window.location.reload();
				

		}
		const getSelectedCases = () =>{
         var input = document.getElementsByName('tp_challan[]');
         var total_cases = 0;
         var case_id = document.getElementById('total_case');

         input.forEach(item=>{
               if(item.checked == true){
                  total_cases += parseInt(item.dataset.case);
               }
            })
            console.log(total_cases);
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
         var input = document.getElementsByName('tp_challan[]');
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
      
		
		const processChallanshop = () =>{
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
				alert('Please select items to process');
				return false;
			}
			var item_str = JSON.stringify(tp_list);
			// console.log(item_str);
			var url = 'challandata.php?fun=processChallanshop&startdate='+startdate.value;
			

			document.getElementById('hid_po').value=item_str;
			// var url = 'update-brand.php?fun=processPO';

			var form =document.getElementById('po_form');
			// console.log(form);
			// return false;
			var formData = new FormData(form);
			// console.log(typeof(tp_list));
			
			const xhttp = new XMLHttpRequest();
			xhttp.onload = function() {
				var data = this.responseText;
					if(data.includes("Challan generated Successfully")) {
					alert(data);
					window.location.reload();
				}
				else{
					   
				// console.log(data);
					   alert(data);
				}
				}
			xhttp.open("POST", url, true);
			xhttp.send(formData);

			





		}
		

	</script>
</body>
</html>

