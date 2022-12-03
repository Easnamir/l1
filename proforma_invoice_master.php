<?php 
include 'includes/session_company.php';
include 'includes/autoload.inc.php';
include 'includes/connect.php';

// $obj = new Dbh();

// $conn = $obj->connect();
$cid = $_SESSION['CINV'];
// $COMPANY_id = $_SESSION['COMPANY_id'];
 $sql="Select distinct cast(INVOICE_NUMBER as int) INVOICE_NUMBER,PROFORMA_DATE,VEND_NAME,IS_DUTY_FREE from POPS_PROFORMA_INVOICE order by PROFORMA_DATE desc,INVOICE_NUMBER desc";
$stmt = sqlsrv_query($conn,$sql);

?>
<!DOCTYPE html>
<html>
<head>
	<title>Proforma Invoice Master</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
	body{background-color: #e0d6d2;}	
    
   .w3-input,  {
      height: 15px;
      font-size: 8px;
     
    }
    
    h3 span{
    	font-size: 12px;
    }
    
    .button {
  display: inline-block;
  padding: 15px 25px;
  font-size: 24px;
  cursor: pointer;
  text-align: center;
  text-decoration: none;
  outline: none;
  color: #fff;
  background-color: #4CAF50;
  border: none;
  border-radius: 15px;
  box-shadow: 0 9px #999;
}

.button:hover {background-color: #3e8e41}

.button:active {
  background-color: #3e8e41;
  box-shadow: 0 5px #666;
  transform: translateY(4px);
}
	</style>
</head>
<body>
	<?php
	include 'includes/header_company.php';
	?>
	<div class="body-content w3-white w3-small">
		<div class="w3-container " id="form_div">
	
			<div class="w3-row">
				<div class="w3-col l1">&nbsp;</div>
				<div class="w3-col l10"  >
					<!-- //main content -->
					<h3>Proforma Invoice Master</h3>
							<div class='w3-col l12 w3-padding-small' style=" overflow: auto; " id="product_table" >
							<table border='1' class='w3-table' style="width: 100%;" >
								<thead>
								<tr class="w3-center w3-text-white"  >
									<th width="5%">S.No</th><th width=10%>Invoice Number</th><th width=10%>Invoice Date</th><th>Customer Name</th><th>Duty Free</th><th width="70px">Action</th>
								</tr>
							</thead>
								<tbody id="item_body"  >
									<?php 
									$i=1;
										while($row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
											?>
												<tr id="<?php echo $row['PID'];  ?>"><td><?php echo $i++; ?></td><td><?php echo $cid.str_pad($row['INVOICE_NUMBER'],6,'0',STR_PAD_LEFT) ?></td><td><?php echo $row['PROFORMA_DATE']->format('d M Y'); ?></td><td class="mid-text"><?php echo $row['VEND_NAME']; ?></td><td class="mid-text"><?php echo $row['IS_DUTY_FREE']?'Yes':'No'; ?></td><td class='mid-text'><a class="w3-red" href="print_proforma_invoice.php?pid=<?php echo $row['INVOICE_NUMBER']; ?>" target="_blank" >Print</a></td></tr>
											<?php
										}
									 ?>
								</tbody>
							</table>
						</div>
						

					<!-- 	<div class="w3-center"><form action="customer-list.php" method="post">
							<button type="Submit" name="xls" class="w3-button w3-button-report w3-red w3-small">Download Excel</button> <button type="Submit" name="pdf" class="w3-button w3-button-report w3-red w3-small">Download PDF</button>
						</form>
			
				</div> -->
		
				
		
		</div>
		<div class="w3-col l1">&nbsp;</div>
	</div>
</div>
<!-- Modal Start -->
<div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:450px">

      <div class="w3-center"><br>
        <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-green w3-display-topright" title="Close Modal">&times;</span>
      </div>

      <form class="w3-container" action="/action_page.php">
        <div class="w3-section">
          <label><b>Enter Invoice</b></label>
          <select id="tpId" name="customer_status" class="w3-select w3-border w3-margin-bottom" style="width:80% ">
          	<option value="">Change Status</option>
          	<option value="1">Approve</option>
          	<option value="2">Reject</option>
          </select>
       <input  id='pk_id' class="w3-input w3-border w3-margin-bottom" type="hidden" placeholder="Enter TP" name="pk_id">
          <button class="w3-button w3-red" type="button" onclick="changeStatus(document.getElementById('tpId').value)">Update</button>
        </div>
      </form>

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
        <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-button w3-green">Cancel</button>
      </div>

    </div>
  </div>
</div>
<!-- Modal Popup End -->
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript" src="js/zone_script.js"></script>
	<script type="text/javascript">
		

		const Viewcustomer = () =>{
			var url = 'Zone_viewUser.php?customer=Viewcustomer';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('item_body').innerHTML=data;
			})
		}
		// document.getElementById('change_zone').addEventListener('change',function(){
		// 	setTimeout(Viewcustomer,0);
		// })

		// Viewcustomer();

		const updateCustomerStatus = (id) =>{
			// console.log(id);
			document.getElementById('id01').style.display='block';
			document.getElementById('pk_id').value=id;
		}
		const changeStatus = (value)=>{
			if(value==''){
				alert("Please change status first");
				return false;
			}
			var customer_pk=document.getElementById('pk_id').value;
			// console.log(customer_pk);
			// console.log(value);
			var url="Zone_viewUser.php?fun=changeStatus&status="+value+"&customer_pk="+customer_pk;
			const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
  	document.getElementById('id01').style.display='none';
    alert(this.responseText);
   // window.location.reload()
   Viewcustomer();
    }
  xhttp.open("GET", url, true);
  xhttp.send();
		}
		const updateReceivedAmount = (id,obj) =>{
			if(parseFloat(obj.innerText)<0){
				alert("Received Amount Can't be negative");
				obj.innerText=0;
				return false;
			}
			if(parseFloat(obj.innerText)==0){
				// console.log('not updated')
				return false;
			}
			var tr = document.getElementById(id);
			// console.log(tr);
			// console.log(url);
			var td=tr.getElementsByTagName('td');
			// console.log(td[3].innerText);
			var total_amount = parseFloat(td[6].innerText);
			var amount_received = parseFloat(td[3].innerText);

			var url = 'update_proforma_amount.php?pid='+id+'&amount='+parseFloat(amount_received);

			if(confirm("Are you sure you want to update Received Amount for!"+td[3].innerText) == false){
				return false;
			}

		}
		var editable = document.querySelectorAll('td[contenteditable=true]');
		editable.forEach(td=>{
			td.addEventListener('keyup',function(event){if(event.key==9){event.preventDefault(); console.log(event)}})
		})
	</script>

	<?php 
	if(isset($_GET['status']) && $_GET['status']=='updated'){
	?>
	<script type="text/javascript">
		alert('Customer Updated Successfully');
	</script>
	<?php
}
else if(isset($_GET['status']) && $_GET['status']=='not-updated'){
?>
<script type="text/javascript">
	alert('Something Went wrong. Please try Again!!');
</script>
<?php
}
?>
</body>
</html>