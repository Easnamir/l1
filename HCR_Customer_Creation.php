<?php 
include 'includes/session_company.php';
	$msg = "";
	$class="";
	$inv = '10001';

	$COMPANY_id = $_SESSION['COMPANY_id'];


		if(isset($_GET['status']) && $_GET['status']=='not-added'){
		// echo '<script>alert("This supplier already Exist, Kindly add another supplier")</script>';
		$msg = "This Customer already Exist, Kindly add another Customer";
  		$class = "w3-orange w3-small w3-padding-small";
	}
	elseif(isset($_GET['status']) && $_GET['status']=='sup-added'){
		// echo '<script>alert("Supplier Added Successfully!!")</script>';
		$msg = "Customer Added/Updated Successfully!!";
  		$class = "w3-green w3-small w3-padding-small";
	}
	
	
	include 'includes/autoload.inc.php';
include 'includes/connect.php';
	// $new_cc = 'cc10001';
	$sql = "SELECT CUSTOMER_CODE from POPS_CUSTOMER_DETAILS where CUSTOMER_DETAILS_PK = (SELECT MAX(CUSTOMER_DETAILS_PK) from  POPS_CUSTOMER_DETAILS where COMPANY_DETAILS_FK ='$COMPANY_id')";
	// exit;
	$stmt = sqlsrv_query($conn,$sql);
	// var_dump(sqlsrv_num_rows($stmt));
	if($stmt != FALSE){
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		$cc_code = $row['CUSTOMER_CODE'];
	}
	// echo $invoice;
	if($cc_code != NULL){
		$cc = substr($cc_code, 2);
		$inv = (int)($cc)+1;
	}
	}
	$new_cc = 'CC'.$inv;
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Add HCR Customer</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style rel="stylesheet" >
		input{
			height: 15px;
		}
		select{
			height: 18px;
		}
		#product_table table {
    width: 100% !important;
}

table tr th{
			padding: 0px !important;
			/*background-color: red !important;*/
			color: white;
		}

	</style>
</head>
<body>
	<?php
	include 'includes/header_company.php';

	?>
	<div class="body-content w3-white w3-small">
		<div class="w3-container w3-margin-bottom">

			<div class="w3-row">
				<div class="w3-col l1">&nbsp;</div>
				<div class="w3-col l10">
					<h3>HCR Customer Creation <span class="<?php echo $class; ?>">
						<?php echo $msg; ?>
					</span></h3>

						<div class="w3-row">
								<div class="container w3-col l12">
				<form name="adduser" action="company_customer.php" method="POST" enctype="multipart/form-data">
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">

                      <div class="w3-col l3 w3-padding">
							<label>Customer Code</label>
							<input class="w3-input w3-border" placeholder="Enter Customer Code " autocomplete="off" type="text" id="Customer_Code" name="Customer_Code" required="" maxlength="20" value="<?php echo $new_cc ?>" readonly>
						</div>
						 <div class="w3-col l3 w3-padding">
							<label>Company Name</label>
							<input class="w3-input w3-border" placeholder="Enter Company Name " autocomplete="off" type="text" id="companyname" name="companyname" required="" onchange="this.value=this.value.toUpperCase()">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Customer Name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" placeholder="Enter Customer Name " autocomplete="off" type="text" onchange="this.value=this.value.replace(/[^a-zA-Z0-9& ]/g, '').toUpperCase()"  id="Business_Name" name="Business_Name" required="" maxlength="100">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Owner Name</label>
							<input class="w3-input w3-border"  placeholder="Enter Owner Name" autocomplete="off" type="text" id="Owner_name" name="Owner_name" maxlength="40">
						</div>
						

						<div class="w3-col l3 w3-padding">
							<label>Email Id<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Email Id" autocomplete="off" type="email" id="email" name="email" required="" maxlength="50">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Contact Number<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Contact Number" autocomplete="off" type="number" min="1000000000" max="999999999999" name="phone" maxlength="12"required="" id="phone">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Customer Address<span class="w3-text-red">*</span></label>
							<input id="address" placeholder="Enter Customer Address" autocomplete="off" name="address" rows="1" onchange="this.value=this.value.toUpperCase()" style="width:100%" required=""></input>
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Pin Code<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"   placeholder="Enter Customer Pin Code" autocomplete="off" maxlength="6" type="number" id="pin" name="pin" required="">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>LICENCE  code</label>
							<input class="w3-input w3-border"  placeholder="Enter Licence Code" autocomplete="off" type="text" id="licencecode" name="licencecode" maxlength="40" onchange="this.value=this.value.toUpperCase()">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>TIN</label>
							<input class="w3-input w3-border" placeholder="Enter TIN Number " autocomplete="off" type="text" id="tinno" name="tinno" required="" maxlength="15">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>PAN Number</label>
							<input class="w3-input w3-border" placeholder="Enter PAN Number " autocomplete="off" type="text" id="pan_number" name="pan_number" required="" maxlength="10" onchange="this.value=this.value.toUpperCase()">
						</div>
						
						<div class="w3-col l3 w3-padding">
							<label>Customer Type</label>
							
							<select class="w3-select" id="cust_type" name="description" >
								<option value="">Select Customer Type</option>
								<option value="Hotel">Hotel</option>
								<option value="CLUB">Club</option>
								<option value="Restuarant">Restuarant</option>

							</select>
						</div>
				
					<div class="w3-container w3-center">
						<button class="w3-button w3-round w3-red "  id="submit_id" type="Submit">Submit</button>
						<!-- <button class="w3-button w3-round w3-red "  id="submit_id" type="Submit">Submit</button> -->
						
						<button class="w3-button w3-round w3-red " type="button" onclick="window.location.reload()">Reset</button>
					</div>

				</form>

</div></div>
<div class="w3-row w3-margin-top " >
	<div class ="w3-col l12"> 	
	<div class ="w3-col l2"> Serch Custemer name </div>
			<div class ="w3-col l5">
				<input class=" w3-input w3-border" type="text" id="myInput" onkeyup="myserch()"  placeholder="Search for names.." title="Type in a name">
			</div>
			</div >
				<div class="w3-col l12 w3-margin-top">
			
							<div class='w3-col l12 ' style=" overflow: auto; " id="product_table" >
							<table border='1' class='w3-table' id="myTable" >
								<thead>
								<tr class="w3-center w3-red "  >
									<th width="5%">S.No</th><th>Customer Code</th><th>Company Name</th><th>Customer Name</th><th>Email Id.</th><th>Owner Name</th><th>Phone Number</th>
									<th>Business Address </th><th>Pin Code</th><th>TIN No</th><th>Licence Number</th><th>PAN Number</th><th>Customer Type</th><th>Status</th><th>Action</th>
								</tr>
							</thead>
								<tbody id="item_body"  >
								</tbody>
							</table>
						</div>
			</div>
		</div>


			

				
				
		
		</div>
		<div class="w3-col l1">&nbsp;</div>
	</div>
		</div>
	</div>
	
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
function myserch() {
  
  var input, filter, table, tr, td, i;
  input = document.getElementById("myInput");
  var str = input.value.length;
  console.log(str);
  filter = input.value.toUpperCase();
  table = document.getElementById("item_body");
  var rows = table.getElementsByTagName("tr");

  for (i = 0; i < rows.length; i++) {
    var cells = rows[i].getElementsByTagName("td");
    var j;
    var rowContainsFilter = false;
    for (j = 0; j < cells.length; j++) {
      if (cells[j]) {
        if (cells[j].innerHTML.toUpperCase().indexOf(filter) > -1) {
          rowContainsFilter = true;
          continue;
        }
      }
    }

    if (!rowContainsFilter) {
      rows[i].style.display = "none";
    } else {
      rows[i] = "dsfdsfsdfds";
    }
  }
}


		const Viewcustomer = () =>{
			var url = 'update-brand.php?list_customer=Viewcustomer';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('item_body').innerHTML=data;
			})
		}
		

		Viewcustomer();

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
			
			var url="update-brand.php?fun=changeStatus&status="+value+"&customer_pk="+customer_pk;
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

	</script>
</body>
</html>