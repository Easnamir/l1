<?php 
include 'includes/session_company.php';
include 'includes/autoload.inc.php';
include 'includes/connect.php';
if(isset($_GET['id'])){
	// header('Location: shop_creation.php');
		 $shop_id = $_GET['id'];
 $sql1 = "select * from POPS_SALE_MAN where POPS_SALE_MAN_PK='$shop_id'";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$shop= [];
	while($row1=sqlsrv_fetch_array($stmt1,SQLSRV_FETCH_ASSOC)){
		$shop=  $row1;

	}
}
// var_dump($shop);
// exit;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Configuration Sales Man</title>
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
				<h3 class="w3-left">Enter Sales Man  Details</h3>
				<div class="<?php echo $class; ?>">
		         <?php echo $msg; ?></div>
				<form name="adduser" action="add_sales.php" method="POST">
					<div class="w3-col l12 w3-border w3-border-grey w3-margin-bottom " style="margin-bottom: 3px!important; padding-bottom:10px">
						<input type="hidden" name="pk" value="<?php echo $shop['POPS_SALE_MAN_PK']; ?>">
						
						
						<div class="w3-col l3 w3-padding-small">
							<label>Name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" placeholder="Enter Sales Name" autocomplete="off" type="text" name="Name" required=""  value="<?php echo $shop['SALE_MAN_NAME']; ?>" >
						</div>
						
						
						<div class="w3-col l3 w3-padding-small">
							<label>Phone<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Phone no" autocomplete="on"type="number" name="phone" required="" value="<?php echo $shop['PHONE_NO'];?>">
						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>Email <span class="w3-text-red"></span></label>
							<input class="w3-input w3-border"  placeholder="Enter email id" autocomplete="on"type="email" name="email" required="" value="<?php echo $shop['EMAIL']; ?>" >
						</div>
						<div class="w3-col l3 w3-padding-small">
						<label>Status<span class="w3-text-red">*</span></label>
              <select  class="w3-select w3-border"  name="status" id="status">
								<option value="">Select Department</option>
								<option <?php echo $shop['STATUS']=='Active'?'selected':''; ?> value="Active">Active</option>
								<option <?php echo $shop['STATUS']=='Inactive'?'selected':''; ?> value="Inactive">Inactive</option>
								
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

			
				<div class="w3-col l12 w3-margin-top">
				<div class="w3-col l1">&nbsp;</div>
					
							<div class='w3-col l10 ' style=" overflow: auto; " id="product_table"  >
							<table border='1' class='w3-table w3-bordered w3-striped w3-border w3-hoverable' style="" >
								<thead>
								<tr class="w3-center  w3-red"  >
									<th width="5%">S.No</th><th>Name</th><th>Phone</th><th>Email</th><th>Status</th><th>Action</th>
								</tr>
							</thead>
								<tbody id="item_body"  >
								</tbody>
							</table>
						</div>
			
		</div>
		
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">

const list_sale = () =>{
			var url = 'update-brand.php?list_sale=list_sale';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('item_body').innerHTML=data;
			})
		}
		

		list_sale();

 

	</script>
</body>



</html>