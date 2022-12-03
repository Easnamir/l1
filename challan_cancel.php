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
							<tr><th>SNo</th><th>Challan No</th><th>Challan Date</th><th>PO NO</th><th>Department</th><th>PO_Date</th><th>TP_NO</th><th>Vend Code</th><th>Vend Name</th><th>Case</th><th style="width: 20px;">Total</th><th >Action</th></tr>	
						</thead>
						<tbody id="show_item">
						</tbody>
					</table>
				</div>
				</div>				
			</div>				
			<form method="POST" name="print_arr_form" id="po_print" action="print_challan_invoice.php?fun=printChallan"  target="_blank" >
				<input type="hidden" name="print_arr" id="hid_print" value="">
			</form>
		</div>
	</div>
</div>
	</div>

 <div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:300px">

      <div class="w3-center"><br>
      	<label>Challan No: <span id='challan_id_modal'></span></label>
        <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-red w3-display-topright" title="Close Modal">&times;</span>
       
      </div>

      <form class="w3-container" action="challandata.php" method="POST">
        <div class="w3-section">
          <label><b>Challan Date</b></label>
          <input class="w3-input w3-border w3-margin-bottom" type="date" id="modal_challan_date"  name="challan_date" required>
          <!-- <label><b>Password</b></label> -->
          <input class="w3-input w3-border" type="hidden"  name="challan_num" id="challan_num" >
          <button class="w3-button w3-red w3-margin-top"  name="update_challan" type="submit">Update</button>
          
        </div>
      </form>
      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">      
      </div>

    </div>
  </div>

	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
		const cancel_challan_list = () =>{
			var url = 'challandata.php?cancel_challan_list=cancel_challan_list';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('show_item').innerHTML=data;       
			})
		}
		cancel_challan_list();


		
		function cancel_challan(tp_no,status){
      if(status == 3 || status == 4){
        if(!confirm('Are you sure you want to cancel this challan \nInvoice for this challan has been generated \nInvoice will also be cancelled')){
          return false
        }
      }
      else{
        if(!confirm('Are you sure you want to cancel the challan')){
          return false
        }
      }      
      var url = 'challandata.php?fun=cancel_challan&tp_no='+tp_no+'&status='+status;
      // console.log(url)
      fetch(url).then(data=>data.text()).then(data=>{
        alert(data);
        window.location.reload();
      })
    }
    function showModal(challan,date){
    	document.getElementById('id01').style.display='block';
    	document.getElementById('challan_id_modal').innerHTML=challan;
    	document.getElementById('challan_num').value=challan;
    	document.getElementById('modal_challan_date').value=date;

    	// console.log(date);
    }
	</script>
</body>
</html>

