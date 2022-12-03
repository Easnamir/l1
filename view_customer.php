<?php 
include 'includes/session_company.php';

$obj = new Dbh($database);;
$conn = $obj->connect();
if(!isset($_GET['cust_id'])){
	
	header('Location: HCR_Customer_Creation.php');
	exit;
}
else{
	$cust_id = base64_decode($_GET['cust_id']);
	// $cust_id;
	 $sql1 = "Select * from POPS_CUSTOMER_DETAILS where CUSTOMER_DETAILS_PK='$cust_id'";
	// exit;
	$stmt1 = sqlsrv_query($conn,$sql1);
	$customer= [];
	while($row1=sqlsrv_fetch_array($stmt1,SQLSRV_FETCH_ASSOC)){
		$customer=  $row1;
	}

// var_dump($customer);
// exit;
}

 ?>


<!DOCTYPE html>
<html>
<head>
	<title> Customer Details</title>
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
					<h3> Customer Details</h3>

						<div class="w3-row">
				
				<div class="container w3-col l12">
				<form name="adduser" action="update_customer.php" method="POST" enctype="multipart/form-data">
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">

                      <div class="w3-col l3 w3-padding">
							<label>Customer Code<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" placeholder="Enter Customer Code " autocomplete="off" type="text" id="Customer_Code" name="Customer_Code" value="<?php echo $customer['CUSTOMER_CODE'] ?>" readonly="" maxlength="100">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Business Name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" placeholder="Enter Business Name " autocomplete="off" type="text" readonly id="Business_Name" value="<?php echo $customer['BUSINESS_NAME'] ?>" name="Business_Name" required="" maxlength="100">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Owner Name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Owner Name" autocomplete="off" type="text" id="Owner_name" value="<?php echo $customer['OWNER'] ?>" name="Owner_name" required="" maxlength="30" onchange="this.value=this.value.toUpperCase()">
						</div>
						

						<div class="w3-col l3 w3-padding">
							<label>Email Id<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Email Id" autocomplete="off" type="email" id="email" value="<?php echo $customer['EMAIL'] ?>" name="email" required="" maxlength="25">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Contact Number<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Contact Number" autocomplete="off" type="number" min="10000000" value="<?php echo $customer['CONTACT_NO'] ?>" max="999999999999" name="phone" required="" id="phone">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Business Address<span class="w3-text-red">*</span></label>
							<input id="address" placeholder="Enter Business Address" autocomplete="off" name="address" rows="1" style="width:100%" value="<?php echo $customer['BUSINESS_ADDRESS'] ?>" required="" onchange="this.value=this.value.toUpperCase()"></input>
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Pin Code<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" value="<?php echo $customer['PIN_NO'] ?>"  placeholder="Enter Business Pin Code" autocomplete="off" type="text" id="pin" name="pin" required="">
						</div>
						
						<div class="w3-col l3 w3-padding">
							<label>Licence Number</label>
							<input class="w3-input w3-border" placeholder="Enter Licence Number " autocomplete="off" type="text" id="licence" value="<?php echo $customer['LICENCE_CODE'] ?>" name="licence" required="" maxlength="15">
						</div>
						<div class="w3-col l12">
						<div class="w3-col l3 w3-padding">
							<label>PAN Number</label>
							<input class="w3-input w3-border" placeholder="Enter PAN Number " autocomplete="off" type="text" id="pan_number" value="<?php echo $customer['PAN_NO'] ?>" name="pan_number" required="" maxlength="10" onchange="this.value=this.value.toUpperCase()">
						</div>
						<div class="w3-col l3 w3-padding">
							<label>Tin no<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" value="<?php echo $customer['TIN_NO'] ?>"  placeholder="Enter Business Tin No" autocomplete="off" type="text" id="tin" name="tin" required="">
						</div>

						<div class="w3-col l3 w3-padding">
							<label>Customer Status</label>
							<!-- <input class="w3-input w3-border" placeholder="Enter PAN Number " autocomplete="off" type="text" id="pan_number" value="<?php echo $customer['PAN_NO'] ?>" name="pan_number" required="" maxlength="10"> -->
							<select name="customer_status" class="w3-select w3-border w3-border-black">
								<!-- <option value="">Change Status</option> -->
								<option <?php echo $customer['CUSTOMER_STATUS']==0?'selected':'' ?> value="0">Pending</option>
								<option <?php echo $customer['CUSTOMER_STATUS']==1?'selected':'' ?> value="1">Approved</option>
								<option <?php echo $customer['CUSTOMER_STATUS']==2?'selected':'' ?> value="2">Rejected</option>

							</select>
						</div>

						<div class="w3-col l3 w3-padding">
							<label>Customer Type</label>
							<!-- <input class="w3-input w3-border" placeholder="Enter PAN Number " autocomplete="off" type="text" id="pan_number" value="<?php echo $customer['PAN_NO'] ?>" name="pan_number" required="" maxlength="10"> -->
							<select name="customer_type" class="w3-select w3-border w3-border-black">
								<!-- <option value="">Change Status</option> -->
								<option <?php echo $customer['DESCRIPTION']=='Hotel'?'selected':'' ?> value="Hotel">Hotel</option>
								<option <?php echo $customer['DESCRIPTION']=='CLUB'?'selected':'' ?> value="CLUB">Club</option>
								<option <?php echo $customer['DESCRIPTION']=='Restaurant'?'selected':'' ?> value="Restaurant">Restaurant</option>

							</select>
						</div>
						<input type="hidden" name="customer_pk" value="<?php echo $cust_id; ?>" >
						

						
						
					</div>
					<div class="w3-container w3-center">
						<!-- <button class="w3-button w3-round w3-red tohide" id="submit_id" type="Submit">Submit</button> -->
						<button class="w3-button w3-round w3-red tohide" id="update_id" type="Submit" >Update</button>
						<!-- <button class="w3-button w3-round w3-red">Delete</button> -->
						<button class="w3-button w3-round w3-red tohide" onclick="window.location='HCR_Customer_Creation.php'" type="button">Back</button>
					</div>

				</form>
			</div>

			</div>
				</div>
				<div class="w3-col l1">&nbsp;</div>
			</div>

		
			<div class="w3-col l2">&nbsp;</div>
			
			<div class="w3-col l2">&nbsp;</div>
		</div>
	</div>
	<div id="id01" class="w3-modal">
    <div class="w3-modal-content">
      <header class="w3-container w3-red"> 
        <span onclick="document.getElementById('id01').style.display='none'" 
        class="w3-button w3-display-topright w3-padding-small">&times;</span>
        <p class="w3-padding-small">Add Brand</p> 
      </header>
     
      
      <div class="w3-container" >
       <img src="" style="max-width: 100%;" id="img_src" />
      </div>

    </div>
  </div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
		// getCustomerName();
		const showDocument = (imgSrc) =>{
			var img = document.getElementById('img_src');
			img.src = imgSrc;

			document.getElementById('id01').style.display='block';
		}
	</script>
</body>
</html>