<?php 
include 'includes/session_company.php';
$msg = "";
$class="";
$slno =1;
$vendid = $_SESSION['CINV'];
if(date('m-d')<'04-02'){
			 $finyear = (date('y')-1).'-'.date('y');
		}
		else{
			$finyear = date('y').'-'.(date('y')+1);
		}
		// var_dump($finyear);
		// exit;
		if(isset($_GET['status']) && $_GET['status']=='not-added'){
		// echo '<script>alert("This supplier already Exist, Kindly add another supplier")</script>';
		$msg = "This item already Exist, Kindly add another item";
  		$class = "w3-red w3-left w3-col l8 w3-col l6 w3-padding w3-panel w3-small";
	}
	elseif(isset($_GET['status']) && $_GET['status']=='sup-added'){
		// echo '<script>alert("Supplier Added Successfully!!")</script>';
		$msg = "Item Added Successfully!!";
  		$class = "w3-green w3-left w3-col l8 w3-col l6 w3-padding w3-panel w3-small";
	}
	elseif(isset($_GET['status']) && $_GET['status']=='not-updated'){
		// echo '<script>alert("Supplier Added Successfully!!")</script>';
		$msg = "Item Not Updated, something went Wrong!!";
  		$class = "w3-red w3-left w3-col l8 w3-col l6 w3-padding w3-panel w3-small";
	}
	elseif(isset($_GET['status']) && $_GET['status']=='item-updated'){
		// echo '<script>alert("Supplier Added Successfully!!")</script>';
		$msg = "Item Updated Successfully!!";
  		$class = "w3-orange w3-left w3-col l8 w3-col l6 w3-padding w3-panel w3-small";
	}
	
	include 'includes/autoload.inc.php';
include 'includes/connect.php';
 $sql = "SELECT INVOICE_NO,S_NO FROM POPS_IP_RECEIVE WHERE S_NO=(SELECT MAX(S_NO) from POPS_IP_RECEIVE where INVOICE_NO LIKE '$vendid/$finyear/%')";
// exit;
	$stmt = sqlsrv_query($conn, $sql);
	if($stmt){
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
	 $slno = $row['INVOICE_NO'];
		$inv = explode('/',$slno);
		// var_dump($inv);
		// exit;
		if($finyear==$inv[1]){
			$slno=$row['S_NO']+1;
		}
		else{
			$slno=1;
		}
	}
}
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>IP Creation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		label{
			display: inline-block !important;
			margin-right: 10px;			
		}
		input{
			display: inline-block !important;
			padding: 0;			
		}
		.w3-table th{
			border: 1px solid #ccc;
			background-color: pink;
		}
		.w3-modal-content{
			width: 60% !important;
		}
		.w3-modal{
			padding-top: 10px !important;
		}
		.w3-modal input{
			width: 100% !important;
		}
		#product_table1{
			min-height: 60px !important;
			max-height: 160px !important;
			overflow: auto;
			font-weight: bold;
		}
		tbody#product_grid  tr td:nth-child(2){
			text-align: left !important;
		}
		tbody#product_grid  tr td{
			border: 1px solid #ccc;
		}
		#form_div{
			font-size: 12px;
		}
		#form_div .w3-input, #form_div .w3-select{
			height: 20px;
			font-size: 8px;
		}
	</style>
</head>
<body id="mainBody">
	<?php
	include 'includes/header_company.php';
	?>
	<div class="body-content w3-white w3-small">
		<div class="w3-container w3-margin-bottom">

			<div class="w3-row">
				<div class="w3-col l1">&nbsp;</div>
				<div class="w3-col l10">
					<h3>IP Receive <span class="<?php echo $class; ?>">
						<?php echo $msg; ?>
					</span></h3>
					<div class="container w3-col l12" id="form_div" >
				<form name="ip" action="#" method="POST" autocomplete="off">
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom" style="padding-bottom:0 !important">
						<div class="w3-col l12">						
						<div class="w3-col l3 w3-padding-small">
							<label>IP Number<span class="w3-text-red">*</span></label>
						<input class="w3-input w3-border formVal" id="ip_num" type="text" name="ip_num" autofocus="" required="">
						</div>
						<!-- <input type="hidden" name="brand_table_pk" id="brand_table_pk" value=""> -->
						<div class="w3-col l3 w3-padding-small">
							<label>IP Date</label>
							<input class="w3-input w3-border formVal" id="ip_date" max="<?php echo date('Y-m-d');?>" type="date" name="ip_date" value="<?php echo date('Y-m-d');?>">							
						</div>
						<div class="w3-col l3 w3-padding-small ">
							<label>Receive Date</label>							 
							<input class="w3-input w3-border formVal" id="receive_date" max="<?php echo date('Y-m-d');?>" type="date" name="receive_date" value="<?php echo date('Y-m-d');?>">							
						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>Supplier<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border w3-right formVal" placeholder="Enter Supplier" autocomplete="off" type="text" id="supplier_id" name="supplier_name" required="" list="supplier">
							<datalist id="supplier"></datalist>
							</div>
						<div class="w3-col l3 w3-right w3-padding-small">							
												
						</div>
						</div>
						<div class="w3-col l12">
						
						<input class="w3-input w3-border w3-right formVal" type="hidden" id="supplier_idd" name="supplier_id" required="" list="supplier">
						<div class="w3-col l6 w3-padding-small">
						<label>Brand Name<span class="w3-text-red">*</span></label>
						<input list="brand" class="w3-input w3-border" placeholder="Enter Brand Name" autocomplete="off" type="text" id="bnd_id" name="brand_name" required="" maxlength="200">
						<datalist id="brand">
						</datalist>
						</div>						
							<!-- <label >Size<span class="w3-text-red">*</span></label> -->
							<input type="hidden" name="brand_code" id="brand_code" value="">
							<div class="w3-col l2 w3-padding-small">
								<label>Quantity(in case) </label>
								<input type="number" id="qty" placeholder="Enter Bottle Quantity" autocomplete="off" name="quantity" class="w3-input w3-border" min="1" required="" step="1">
							</div>
							<div class="w3-col l2 w3-padding-small">
								<button style="margin-top: 15px; " name="add-item" type="button" class="w3-button w3-border w3-left w3-red w3-round" onclick="addItemIp()" id="add_item">Add/Edit Items</button>
							</div>
						</div>
						</div>
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">
					<div class="w3-col l12 w3-padding w3-responsive" id="product_table1">
						<table class="w3-table-all w3-border" cellspacing="0" cellpadding="0">
							<thead>
							<tr class="w3-red">
								<th width="6%">SNo</th><th>Brand Name</th><th width="10%">Size</th><th width="10%">Quantity(Case)</th><th width="10%">Quantity(Bottle)</th><th width="16%">Supplier Name</th><th width="6%">Action</th>
							</tr>
							</thead>
							<tbody id="product_grid">
								
							</tbody>
						</table>

					</div>
					</div>
					<div class="w3-col l12">						
						<div class="w3-col l12 w3-center"><button class="w3-button w3-round w3-red tohide" name="submit" onclick="submitIpData()" type="button" id="submit">Submit</button>
						
					</div>
				</form>
			</div>
				</div>
				<div class="w3-col l1">&nbsp;</div>
			</div>		
			<div class="w3-row">
				<form class="w3-form w3-center" id="form_ip" action="#" method="POST">
					<input type="hidden" name="ip_data" id="ip_data" value="">
					<input class="w3-input w3-border formVal" id="invoice_id" type="hidden" name="invoice_id" required="" value="<?php echo "$vendid/".$finyear."/".$slno ?>">
							<input class="w3-input w3-border formVal" type="hidden" id="slno" name="slno" required="" maxlength="14" value="<?php echo $slno ?>">		
				</form>
			</div>
			<div class="w3-col l1">&nbsp;</div>
			
			<div class="w3-col l2">&nbsp;</div>
		</div>
	</div>
	
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
			var allBrandData =[];
			var ipData = [];
			var bnd_id = document.getElementById('bnd_id');
		    (() => {
					var options='';
					let url = 'ip_data_query.php?fun=getBrandSize';
					fetch(url).then(data=>data.json()).then(data=>{
						 allBrandData=(data);
						data.forEach(brand=>{
							options += `<option>${brand.BRAND_NAME}(${brand.SIZE_VALUE})</option>`;
							// allBrandData.push(brand)
						})
					}).then(()=>{document.getElementById('brand').innerHTML=options});					
				})();
				bnd_id.addEventListener('dblclick',function (){this.value=''});
				bnd_id.addEventListener('change',function (event){
					let str = event.target.value;
					let arr = str.split('(');
					let brand_name = arr[0];
					let brand_size = arr[1].split(')')[0];
					// console.log(brand_name, brand_size);
					let brand_code = allBrandData.filter(brand=>{
						if(brand.BRAND_NAME==brand_name && brand.SIZE_VALUE==brand_size) 
						 document.getElementById('brand_code').value=brand.BRAND_CODE
					})
				});
				function addItemIp() {
					var code = document.getElementById('brand_code').value;
					var qty = document.getElementById('qty');
					var brand_id = document.getElementById('bnd_id'); //supplier_id
					var supplier_id = document.getElementById('supplier_id'); 

					var exist_brand=[];
					if(supplier_id.value==''){
						alert('Please Enter Supplier Name');
						supplier_id.focus();
						return false;
					}
					if(brand_id.value==''){
						alert('Please select a brand');
						brand_id.focus();
						return false;
					}
					if(qty.value==''|| qty.value <=0){
						qty.value=''
						alert('Please enter valid quantity');
						qty.focus();
						return false;
					}
					exist_brand = ipData.filter(brand=>brand.BRAND_CODE==code)
					if(exist_brand.length>0){
						alert('This brand already added!!');
						return false;
					}
					allBrandData.map(brand =>{
						if(brand.BRAND_CODE == code){
							brand.QUANTITY = qty.value;
							brand.SUPP_NAME = supplier_id.value;
							ipData.unshift(brand);
						}
					})
					// console.log(ipData);
					showIpData();
				}
				function showIpData (){
					var html ='';
					var i=1;
					if(ipData.length==0){
						 html += "<tr><td colspan=7 style='text-align: center !important'>No Data to display!!</td></tr>";
					}
					else{
						ipData.map(brand=>{
							html += `<tr><td>${i++}</td><td>${brand.BRAND_NAME}</td><td>${brand.SIZE_VALUE}</td><td>${brand.QUANTITY}</td><td>${brand.QUANTITY}</td><td>${brand.SUPP_NAME}</td><td><i class='fa fa-trash' id='${brand.BRAND_CODE}' onclick='deleteItemIp(this.id)'></i></td></tr>`;
						})
						
					}
					document.getElementById('product_grid').innerHTML=html;
				}
				
				function deleteItemIp (code){
					ipData = ipData.filter(brand=>brand.BRAND_CODE!=code);
					showIpData();
				}

				function submitIpData() {
					let ip_num = document.getElementById('ip_num');
					let ip_date = document.getElementById('ip_date');
					let receive_date = document.getElementById('receive_date');
					let ip_data = document.getElementById('ip_data');
					let form_ip = document.getElementById('form_ip');
					let url = 'ip_data_query.php?fun=submitIpData&ip_date='+ip_date.value+'&receive_date='+receive_date.value+'&ip_num='+ip_num.value;
					// console.log(url);
					if(ipData.length<=0){
						alert('Nothing to submit yet!! Please add Items');
						return false;
					}
					if(ip_num.value==''){
						alert('Please enter IP number!!');
						ip_num.focus();
						return false;
					}	
					ip_data.value=JSON.stringify(ipData);
					let form_data = new FormData(form_ip);
					const xhttp = new XMLHttpRequest();
				xhttp.onload = function() {
					alert(this.responseText);
					window.location.reload();
					}
				xhttp.open("POST", url, true);
				xhttp.send(form_data);
					
				}
				
	</script>
</body>
</html>

