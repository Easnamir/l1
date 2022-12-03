<?php 
require_once __DIR__ . '/vendor/autoload.php';
session_start();
include 'includes/autoload.inc.php';
include 'includes/connect.php';
	$USER = $_SESSION['username'];
	  $sql = "Select a.SHOP_NAME,a.SHOP_CODE from POPS_VEND_DETAILS a join POPS_ZONE b on a.ZONE=b.POPS_ZONE_PK where b.POPS_COMPANY_DETAILS_FK='$COMPANY_id'";
	// exit;
	$stmt=sqlsrv_query($conn,$sql);
 ?>
<!DOCTYPE html>
<html>
<head>
	<title>Proforma Invoice</title>
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
			min-height: 160px !important;
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
					<h3>Proforma Invoice<span class="<?php echo $class; ?>">
						<?php echo $msg; ?>
					</span></h3>
					<div class="container w3-col l12" id="form_div" >
				<form name="proforma" action="#" method="POST" autocomplete="off">
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">
						<div class="w3-col l12">							
							<div class="w3-col l3 w3-padding-small ">
							<label>Date</label>
							<input class="w3-input w3-border formVal" id="dispatch_date" type="date" name="proforma_date">							
						</div>						
						<div class="w3-col l5 w3-padding-small">
							<label>Receive Customer<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border w3-right formVal " placeholder="Enter Customer" autocomplete="off" type="text" id="customer_id"  name="customer_name" list="customer">
							<datalist id="customer"></datalist>
							</div>
							<div class="w3-col l2 w3-padding-small">
							<label >TCS Applicable</label>
							<input type="checkbox" class="w3-checkbox" id="isTcs" name="tcs" onclick="clearProformaData()" value="1" checked > Yes 
							</div>
							<div class="w3-col l2 w3-padding-small">
							<label >Duty Free</label>
							<input type="hidden"  name="duty_free"  value="0" >
							<input type="checkbox" class="w3-checkbox" id="isDuty" name="duty_free" onclick="clearProformaData()" value="1" > Yes 
							</div>
						<div class="w3-col l4 w3-padding-small">
								<label>Brand Name<span class="w3-text-red">*</span></label>
								<input list="brand" class="w3-input w3-border" placeholder="Enter Brand Name" autocomplete="off" type="text" id="bnd_id" ondblclick="this.value=''" name="brand_name" required="" maxlength="200">
								<datalist id="brand">
								</datalist>
						</div>
						<div class="w3-col l2 w3-padding-small">
							<label >Size<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border" name="size_value" id="search_size" required="">
								<option value="0">Select Size</option>
							</select>
						</div>
						<div class="w3-col l2 w3-padding-small">
								<label>Quantity(Case)</label>
								<input type="number" id="qty" placeholder="Enter Quantity" autocomplete="off" name="quantity" class="w3-input w3-border" min="1" required="" step="1">
						</div>
						<div class="w3-col l2 w3-padding-small">
								<label>Discount(%)</label>
								<input type="number" id="dis_id" placeholder="Discount" autocomplete="off" name="discount" class="w3-input w3-border" value="0" min="1" readonly step="1">
						</div>																					
							<div class="w3-col l2 w3-padding-small">
								<button style="margin-top: 14px; " name="add-item" type="button" class="w3-button w3-border w3-left w3-red w3-round" id="add_item">Add/Edit Items</button>
							</div>							
						</div>																	
					</div>
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">
					<div class="w3-col l12 w3-padding w3-responsive" id="product_table1">
						<table class="w3-table-all w3-border" cellspacing="0" cellpadding="0">
							<thead>
							<tr>
								<th width="6%">S No</th><th>Brand Name</th><th width="5%">Size</th><th width="6%">Quantity</th><th width="10%">WSP P/B</th><th width="8%">Excise</th><th width="12%">Custom Duty</th><th width="12%">MRP Amount</th> 								
								<th width="6%">Action</th>
							</tr>
							</thead>
							<tbody id="product_grid">								
							</tbody>
						</table>
					</div>
					</div>
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">						
						<input type="hidden" name="item_data" id="item_data" value="">
						<div class="w3-col l12 w3-padding-small w3-center">
						<button class="w3-button w3-round w3-red " name="submit_dispatch" type="button" id="submit" onclick="submitPerforma()">Get Proforma</button>						
						</div>
					</div>
				</form>
			</div>
				</div>
				<div class="w3-col l1">&nbsp;</div>
			</div>		
			<div class="w3-row">
			</div>
			<div class="w3-col l1">&nbsp;</div>
			
			<div class="w3-col l2">&nbsp;</div>
		</div>
	</div>
	
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
		// var search_brand = document.getElementById('bnd_id');
		// var priceMasterData = [];
		var proformat_data=[];
		var allBrandData =[];
		var isDutFree = false;
		var isTcsapplicable = true;
		// search_brand.addEventListener("change",getBrandSize);
		var add_item = document.getElementById('add_item');
		(() => {
					var options='';
					let url = 'ip_data_query.php?fun=getBrandSize';
					fetch(url).then(data=>data.json()).then(data=>{
						 allBrandData=(data);
						data.forEach(brand=>{
							options += `<option>${brand.BRAND_NAME}</option>`;
							// allBrandData.push(brand)
						})
					}).then(()=>{document.getElementById('brand').innerHTML=options});					
				})();

var product_added = add_item.addEventListener("click",getPerformaData);
setDateDispatch();
getCustomerName();

var search_brand = document.getElementById('bnd_id');
		search_brand.addEventListener("change",function(){getBrandSizePO(this.value)});

function getBrandSizePO(brand){
	// console.log(brand);
	var html = '<option value="">Select Size</option>';
	var sizeArr = [];
	sizeArr = allBrandData.filter(item=>item.BRAND_NAME.trim()==brand.trim())
	// console.log(sizeArr);
	sizeArr.map(brand=>{
		html +=`<option value='${brand.SIZE_VALUE}'>${brand.SIZE_VALUE}</option>`;
	})
	document.getElementById('search_size').innerHTML=html
}

function getPerformaData(){
var input = document.getElementById('bnd_id');
var size = document.getElementById('search_size');
var qty = document.getElementById('qty');
var dis_id = document.getElementById('dis_id');
var grid_id = document.getElementById('product_grid');
// var dispatch_vend = document.getElementById('dispatch_vend');
var check_arr =[];

if(input.value == ''){
alert("Please select Brand");
input.focus();
return false;
}
else if(size.value==0){
alert("Please select Size");
size.focus();
return false;
}
else if(qty.value == ''){

alert("Please Enter quantity");
qty.focus();
return false;
}
check_arr = proformat_data.filter(brand=>brand.BRAND_NAME.trim()==input.value.trim() && brand.SIZE_VALUE==size.value);
if(check_arr.length>0){
	alert("This item is already in list");
	return false;
}
allBrandData.map(brand=>{
	if(brand.BRAND_NAME.trim()==input.value.trim() &&  brand.SIZE_VALUE==size.value){
		brand.QUANTITY = qty.value;
		// brand.
		proformat_data.unshift(brand);
	}
})
qty.value='';
// console.log(proformat_data);
showPerformaData();
}

const showPerformaData = ()=>{
	var table = document.getElementById('product_grid');
	// var is_tcs = document.querySelector('input[name="is_tcs"]:checked').value;
	var sub_total = document.getElementById('sub_total');
	var tcs_amount=document.getElementById('tcs_amount');
	var total_amount_pay = document.getElementById('total_amount_pay');
	var total_quantity_id = document.getElementById('total_quantity');
	// (parseInt(is_tcs))
	var html='';
	var i=1;
	var total_amount=0;
	var total_quantity=0;
	var total_payable=0;
	var total_tcs=0;
	var mrp_amount=0;
	if(proformat_data.length==0){
		html+='<tr><td colspan=10 class="w3-center">No Data Found</td></tr>';
		// return false;
	}
	
		proformat_data.map(row=>{
			let total_amount_ind = parseInt(row.MRP)*row.PACK_SIZE*row.QUANTITY;
			row.TOTAL_MRP_AMOUNT = total_amount_ind;

		html+='<tr><td>'+ i++ +'</td><td>'+row.BRAND_NAME+'</td><td>'+row.SIZE_VALUE+'</td><td>'+row.QUANTITY+'</td><td>'+row.WSP+'</td><td>'+row.EXCISE_PRICE+'</td><td>'+(isDutFree?0:row.CUSTOM_DUTY)+'</td><td>'+row.TOTAL_MRP_AMOUNT+'</td><td style="cursor: pointer;" id="'+row.BRAND_CODE+'" onclick="removeItem(this.id)">X</td></tr>';
			total_quantity += parseInt(row.quantity);
			total_amount += parseFloat(row.TOTAL_MRP_AMOUNT);
			mrp_amount+=(parseInt(row.quantity)*parseInt(row.MRP));
		})		
		
	table.innerHTML=html;
}

const removeItem = (id)=>{
	// console.log(id);
	proformat_data = proformat_data.filter(row=>{
		// console.log(row.BRAND_CODE);
		return row.BRAND_CODE!=id;
	});
	// console.log(proformat_data);
	showPerformaData();
}

const submitPerforma =()=>{
	var pro_date=document.getElementById('dispatch_date');
	var customer_id=document.getElementById('customer_id');
	if(proformat_data.length==0){
		alert('Please add some Items');
		return false;
	}
	if(customer_id.value==''){
		alert('Please Enter Customer Name');
		customer_id.focus();
		return false;
	}
	var item_details=JSON.stringify(proformat_data);
	document.getElementById('item_data').value=(item_details);
	var url ='proformat_query.php';
	var form = document.querySelector('form');
	var data = new FormData(form);
	const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    if(this.responseText.includes('All data Added Successfully')){
    	alert('All data Added Successfully');
    	window.location.href='proforma_invoice_master.php';
    }
    else{
    	// alert('Something Went wrong');
			console.log(this.responseText);
    	// window.location.reload();
    }
    }
  xhttp.open("POST", url, true);
  xhttp.send(data,encodeURIComponent(item_details));
}




function clearProformaData(){
	if(document.getElementById('isTcs').checked){
		isTcsapplicable=true;
	}
	else{
		isTcsapplicable=false;
	}
	if(document.getElementById('isDuty').checked){
		isDutFree=true;
	}
	else{
		isDutFree=false;
	}
	proformat_data=[];
	showPerformaData();
}
</script>


</body>
</html>

