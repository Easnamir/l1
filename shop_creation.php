<?php 
include 'includes/session_company.php';
include 'includes/autoload.inc.php';
include 'includes/connect.php';
if(isset($_GET['id'])){
	// header('Location: shop_creation.php');
		 $shop_id = $_GET['id'];
 $sql1 = "select * from POPS_VEND_DETAILS where VEND_DETAILS_PK='$shop_id'";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$shop= [];
	while($row1=sqlsrv_fetch_array($stmt1,SQLSRV_FETCH_ASSOC)){
		$shop=  $row1;
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Configuration Party</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style rel="stylesheet" >
		input{
			height: 18px;
		}
		select{
			height: 18px;
			padding: 0;
			font-size: 11px;
			line-height: 20px;
		}
		table tr th{
			padding: 0px !important;
		
			color: white;
		}
		#product_table table {
    width: 100% !important;
}


	</style>
</head>
<body>
	<?php
	include 'includes/header_company.php';
	?>
			<div class="w3-container ">

			<div class="w3-col l1">&nbsp;</div>
			<div class="container w3-col l10">
				<h3 class="w3-left">Enter Party Details</h3>
				<div class="<?php echo $class; ?>">
		         <?php echo $msg; ?></div>
				<form name="adduser" action="add_shop.php" method="POST">
					<div class="w3-col l12 w3-border w3-border-grey w3-margin-bottom " style="margin-bottom: 3px!important; padding-bottom:10px">
						<input type="hidden" name="pk" value="<?php echo $shop['VEND_DETAILS_PK']; ?>">
						<div class="w3-col l3 w3-padding-small">
							<label>Vend ID<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"   placeholder="Enter Vend ID" autocomplete="off"  type="text" name="vanderId" id="vanderId" required="" value="<?php echo $shop['VEND_CODE']; ?>" onchange="this.value=this.value.replace(/[^a-zA-Z0-9&]/g, '').toUpperCase()">
						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>Department<span class="w3-text-red">*</span></label>
              <select  class="w3-select w3-border"  name="Department" id="Department">
								<option value="">Select Department</option>
								<option <?php echo $shop['DEPARTMENT']=='DSIIDC'?'selected':''; ?> value="DSIIDC">DSIIDC</option>
								<option <?php echo $shop['DEPARTMENT']=='DCCWS'?'selected':''; ?> value="DCCWS">DCCWS</option>
								<option <?php echo $shop['DEPARTMENT']=='DTTDC'?'selected':''; ?> value="DTTDC">DTTDC</option>
								<option <?php echo $shop['DEPARTMENT']=='DSCSC'?'selected':''; ?> value="DSCSC">DSCSC</option>
								<option <?php echo $shop['DEPARTMENT']=='HOTEL'?'selected':''; ?> value="HOTEL">HOTEL</option>
								<option <?php echo $shop['DEPARTMENT']=='CLUB'?'selected':''; ?> value="CLUB">CLUB</option>
								<option <?php echo $shop['DEPARTMENT']=='RESTAURANT'?'selected':''; ?> value="RESTAURANT">RESTAURANT</option>
								<option <?php echo $shop['DEPARTMENT']=='PRIVATE'?'selected':''; ?> value="PRIVATE">PRIVATE VEND</option>	
							</select>


						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>Name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" placeholder="Enter Shop Name" autocomplete="off" type="text" name="shopName" required=""  value="<?php echo $shop['VEND_NAME']; ?>" onchange="this.value=this.value.replace(/[^a-zA-Z0-9&_\- ]/g, '').toUpperCase()">
						</div>
						
						<div class="w3-col l3 w3-padding-small">
							<label>License No<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter License No" autocomplete="on"  type="text" name="exciseNo" required="" value="<?php echo $shop['ExciseNO']; ?>">
						</div>
						<div class="w3-row">
						<div class="w3-col l3 w3-padding-small">
							<label>Address<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"placeholder="Enter Shop Address" autocomplete="off" type="text" name="address" required="" value="<?php echo $shop['VEND_ADDRESS']; ?>" onchange="this.value=this.value.replace(/[^a-zA-Z0-9&_\- ]/g, '').toUpperCase()">
						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>Pin Code<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Pin Code" autocomplete="off"type="number" name="pinCode" required="" value="<?php echo $shop['PIN_CODE']; ?>">
						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>TIN<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter tin no" autocomplete="on"type="text" name="tinno" required="" value="<?php echo $shop['TIN']; ?>">
						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>Pan no <span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter pan no" autocomplete="on"type="text" name="panno" required="" value="<?php echo $shop['PAN_NO']; ?>" >
						</div>
						
						<div class="w3-col l3 w3-padding-small" >
							<label>Gst no<span class="w3-text-red"></span></label>
							<input class="w3-input w3-border"  placeholder="Enter Gst no " autocomplete="on"type="text" name="gstno" required="" value="<?php echo $shop['GST_NO']; ?>">
						</div>
						<div class="w3-col l3 w3-padding-small" >
							<label>FSSAI No<span class="w3-text-red"></span></label>
							<input class="w3-input w3-border"  placeholder="Enter FSSAI no " autocomplete="on"type="text" name="FSSAI" required="" value="<?php echo $shop['FSSAI']; ?>">
						</div>

						<div class="w3-col l3 w3-padding-small">
							<label>sales Man <span class="w3-text-red">*</span></label>
							<select name="salesman" class="w3-select w3-border" id="salesman">
								<option value="">Select sales Man</option>
								?> <?php
								$sql1 = "select SALE_MAN_NAME from POPS_SALE_MAN ";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$shop1= [];
	while($row1=sqlsrv_fetch_array($stmt1,SQLSRV_FETCH_ASSOC)){
		?>

	                   <option  value="<?php echo $row1['SALE_MAN_NAME'] ?> "> <?php echo $row1['SALE_MAN_NAME'] ?></option>
							<?php
	}
					?>
							</select>
						</div>
						</div>
						
					
					<div class="w3-container w3-center ">
						<button class="w3-button w3-round w3-red"  type="submit">Submit</button>
						<button class="w3-button w3-round w3-red" type="button" onclick="location.href='shop_creation.php'">Cancel</button>
						<!-- <button class="w3-button w3-round w3-red" type="button" onclick="location.href='shop_list.php'">Shop List</button> -->
						<!-- <button class="w3-button w3-round w3-red" type="reset">Reset</button> -->
					</div>
</div>
				</form>

				<div class="w3-row w3-margin-top " > <i class="w3-right">Download  List <img src="images/microsoft-excel.png" alt="amrit" style="height: 20px;" onclick="location.href='shop_list.php'">  </i>
				
				<div class="w3-col l12 w3-margin-top">
					
							<div class='w3-col l12 ' style=" overflow: auto; " id="product_table" width="120%"  >
							<table border='1' class='w3-table w3-bordered w3-striped w3-border w3-hoverable' style="" >
								<thead>
								<tr class="w3-center  w3-red"  >
									<th width="5%">S.No</th><th>Vend ID</th><th>Name</th><th>License No</th>
									<th> Address </th><th>Pin</th><th>TIN No</th><th>PAN Number</th><th>Gst No</th><th>Fssai no </th><th>Department</th><th>Sale Man Name</th><th>Action</th>
								</tr>
							</thead>
								<tbody id="item_body"  >
								</tbody>
							</table>
						</div>
			</div>
		</div>
		
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">

 var vend=document.getElementById('vanderId');

 vend.addEventListener('change',function(){
 	// console.log(this.value);
 	var id= this.value;
 	var check=false;
 	zone_array.map(row=>{
 		if(row.VEND_CODE==id)
 			check=true;
 	})
 	if(check){
 		alert('This vend id is already present')
 		this.value='';
 		this.focus();
 	}


 })
const Viewshop = () =>{
			var url = 'update-brand.php?list_shop=Viewshop';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('item_body').innerHTML=data;
			})
		}
		

		Viewshop();

 

	</script>
</body>



</html>