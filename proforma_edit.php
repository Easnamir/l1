<?php 
include 'includes/session_company.php';
$msg = "";
$class="";
include 'includes/autoload.inc.php';
	 // $shop = $_SESSION['SHOP_NAME'];
	$obj = new Dbh();
	$conn = $obj->connect();

	if(!isset($_GET['pid'])){
		header("Location: proforma_invoice_master.php");
		exit;
	}

	$pid = $_GET['pid'];
	
	$sql1 = "select * from POPS_PROFORMA_INVOICE where invoice_number='$pid'";
	$stmt1 = sqlsrv_query($conn,$sql1);
	$inv_data = [];
	while($row1=sqlsrv_fetch_array($stmt1,SQLSRV_FETCH_ASSOC)){
		$inv_data = $row1;
	}
	extract($inv_data);

	// echo $RECEIVE_CUSTOMER;

	// exit;

	  $sql = "Select a.SHOP_NAME,a.SHOP_CODE from POPS_SHOP_DETAILS a join POPS_ZONE b on a.ZONE=b.POPS_ZONE_PK where b.POPS_COMPANY_DETAILS_FK='$COMPANY_id'";
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
							<input class="w3-input w3-border w3-right formVal " placeholder="Enter Customer" autocomplete="off" type="text" id="customer_id" value="<?php echo $RECEIVE_CUSTOMER; ?>"  name="customer_name" list="customer">
							<datalist id="customer"></datalist>
							</div>
							<div class="w3-col l3 w3-padding-small">
							<label >TCS Applicable<span class="w3-text-red">*</span></label>
							<input type="radio" class="w3-radio" name="is_tcs" onclick="applyTCS(this.value)" value="1" <?php echo $TOTAL_TCS_AMOUNT>0?'checked':''; ?> > Yes <input type="radio" class="w3-radio" <?php echo $TOTAL_TCS_AMOUNT>0?'':'checked'; ?> onclick="applyTCS(this.value)" name="is_tcs" value="0"> No
							</div>
						<div class="w3-col l4 w3-padding-small">
								<label>Brand Name<span class="w3-text-red">*</span></label>
								<input list="brand" class="w3-input w3-border" placeholder="Enter Brand Name" autocomplete="off" type="text" id="bnd_id" ondblclick="this.value=''" name="brand_name" required="" maxlength="200">
								<datalist id="brand">
								</datalist>
						</div>

						<div class="w3-col l1 w3-padding-small">
							<label >Size<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border" name="size_value" id="search_size" required="">
								<option value="0">Select Size</option>
							</select>
						</div>
						<div class="w3-col l1 w3-padding-small">
								<label>Bottle Qty</label>
								<input type="number" id="qty" placeholder="Enter Bottle Quantity" autocomplete="off" name="quantity" class="w3-input w3-border" min="1" required="" step="1">
						</div>	
						<div class="w3-col l1 w3-padding-small">
								<label>Discount(%)</label>
								<input type="number" id="dis_id" placeholder="Discount" autocomplete="off" name="discount" class="w3-input w3-border" value="0" min="1" required="" step="1">
						</div>
							<div class="w3-col l3 w3-padding-small">
							<label>Dispatch vend<span class="w3-text-red">*</span></label>
							<select class="w3-select w3-border w3-padding-small" id="dispatch_vend" name="vend_name" >
								<option value="">Select Vend</option>
								<?php 
								while($row=sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
									print_r($row);
									?>

									<option value="<?php echo $row['SHOP_NAME']; ?>"><?php echo $row['SHOP_NAME'].'('.$row['SHOP_CODE'].')'; ?></option>

									<?php
								}

								 ?>
							</select>
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
							<tr class="w3-red">
								<th width="6%">S No</th><th>Vend Name</th><th>Brand Name</th><th width="5%">Size</th><th width="6%">Quantity</th><th width="10%">Rate</th><th width="12%">VAT</th><th width="6%">Discount(%)</th><th width="6%">Final Rate</th><th width="8%">Amount</th> 
								<!-- <th width="6%">MRP</th> <th width="6%"> Amount</th> -->
								<th width="6%">Action</th>
							</tr>
							</thead>
							<tbody id="product_grid">								
							</tbody>
						</table>
					</div>
					</div>
					<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom">
						<div class="w3-col l3 w3-padding-small">
							<label>Total Quantity</label>
							<input class="w3-input w3-border formVal" id="total_quantity" value="0"  type="number" name="total_quantity" step=".01" readonly="">
						</div>
						
						<div class="w3-col l3 w3-padding-small">
							<label>Sub Total</label>
							<input class="w3-input w3-border formVal" value="0"  type="number" name="sub_total" step=".01" id="sub_total" readonly="">
						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>TCS Amount</label>
							<input class="w3-input w3-border formVal" value="0"  type="number" name="tcs_amount"   id="tcs_amount" readonly="" step=".01">
						</div>
						<!-- <div class="w3-col l3 w3-padding-small">
							<label>Discount</label>
							<input class="w3-input w3-border formVal" value="0"  type="number" name="discount"   id="disc" readonly="" step=".01" onchange="updateDispatchAmount(this)">
						</div> -->
						<div class="w3-col l3 w3-padding-small">
							<label>Total Amount</label>
							<input class="w3-input w3-border formVal" value="0" step=".01"  type="number" name="total_amount_pay"  maxlength="20" id="total_amount_pay" readonly="">
						</div>
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
		// search_brand.addEventListener("change",getBrandSize);
		var add_item = document.getElementById('add_item');


var product_added = add_item.addEventListener("click",getPerformaData);
setDateDispatch();
getCustomerName();
getAllBrandName();
var search_brand = document.getElementById('bnd_id');
		search_brand.addEventListener("change",getBrandSizeAll);



function getPerformaData(){
var input = document.getElementById('bnd_id');
var size = document.getElementById('search_size');
var qty = document.getElementById('qty');
var dis_id = document.getElementById('dis_id');
var grid_id = document.getElementById('product_grid');
var dispatch_vend = document.getElementById('dispatch_vend');


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
else if(dispatch_vend.value==''){
	alert('Please Select Vend Name');
	dispatch_vend.focus();
	return false;
}
else{
var url ='update-brand.php?&brandname='+encodeURIComponent(input.value)+'&brandsize='+size.value;

var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
	// console.log(this.responseText);
var response = JSON.parse(xmlhttp.responseText);
var state = false;
var qty_state = false;
var available_qty = 0;
var is_tcs = document.getElementById('is_tcs');
var cost=0;
var final_rate=0;
var discount = dis_id.value;
var amount=0;
var vat = 0;

proformat_data.map(item=>{
if(item.SKU_FK == response[0].SKU_FK && item.vend_name==dispatch_vend.value){
state = true;
}
});
 
if(state){
alert("Product exists in the table !! Add another item or Change Product Size");
size.focus();
return false;
}
else{
	cost = (parseFloat(nullToZero(response[0].EXCISE_PRICE))+(parseFloat(nullToZero(response[0].WSP)))+(parseFloat(nullToZero(response[0].RETAIL_PROFIT))*0.9901)+parseFloat(nullToZero(response[0].CUSTOM_DUTY))).toFixed(2);
	vat = ((parseFloat(nullToZero(response[0].EXCISE_PRICE))+(parseFloat(nullToZero(response[0].WSP)))+(parseFloat(nullToZero(response[0].RETAIL_PROFIT))*0.9901)+parseFloat(nullToZero(response[0].CUSTOM_DUTY)))*0.01);
	final_rate=cost-cost*parseFloat(discount*0.01)+vat;
	amount = (final_rate)*parseInt(qty.value);
var object = {
vend_name: dispatch_vend.value,
SKU_FK: response[0].SKU_FK,
product_name: response[0].BRAND_NAME,
product_size: size.value,
quantity: parseInt(qty.value),
discount: parseInt(dis_id.value),
excise_rate: parseFloat(nullToZero(response[0].EXCISE_PRICE)).toFixed(2),
wsp: (parseFloat(nullToZero(response[0].WSP))).toFixed(2),
custom: parseFloat(nullToZero(response[0].CUSTOM_DUTY)).toFixed(2),
margin:(parseFloat(nullToZero(response[0].RETAIL_PROFIT))),
cost: cost,
VAT: vat.toFixed(2),
MRP: parseFloat(nullToZero(response[0].RETAIL_PRICE)).toFixed(2),
final_rate:final_rate.toFixed(2),
TOTAL_MRP_AMOUNT: amount.toFixed(2)
}              
proformat_data.unshift(object);    
qty.value=''; 
showPerformaData();   
}             
}
};
xmlhttp.open("GET", url, true);
xmlhttp.send();
}
}
const getInvoiceData = (pid) =>{
	var url = 'getPIdata.php?pid='+pid;


	const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    var response = JSON.parse(this.responseText);
    proformat_data = response.map(row=>({
        	vend_name: row.VEND_NAME,
    			SKU_FK: parseInt(row.SKU_FK),
    			product_name: row.BRAND_NAME,
    			product_size: row.SIZE_VALUE,
    			quantity: row.QUANTITY,
    			excise_rate: row.EXCISE_DUTY,
    			wsp: row.WSP,
    			custom: row.CUSTOM_DUTY,
    			margin: 0,
    			cost: (parseFloat(row.MRP)*0.9901).toFixed(2),
    			VAT: row.VAT,
    			MRP: row.MRP,
    			discount: row.DISCOUNT,
    			final_rate: row.FINAL_RATE,
    			TOTAL_MRP_AMOUNT: parseFloat(nullToZero(row.FINAL_RATE)*parseInt(row.QUANTITY)).toFixed(2)
        }))
    showPerformaData();
    }
  xhttp.open("GET", url, true);
  xhttp.send();
}
	
getInvoiceData(<?php echo $PID ?>)

const showPerformaData = ()=>{
	var table = document.getElementById('product_grid');
	var is_tcs = document.querySelector('input[name="is_tcs"]:checked').value;
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
	}
	else{
		proformat_data.map(row=>{
		html+='<tr><td>'+ i++ +'</td><td>'+row.vend_name+'</td><td>'+row.product_name+'</td><td>'+row.product_size+'</td><td>'+row.quantity+'</td><td>'+row.cost+'</td><td>'+row.VAT+'</td><td>'+row.discount+'</td><td>'+row.final_rate+'</td><td>'+row.TOTAL_MRP_AMOUNT+'</td><td style="cursor: pointer;" id="'+row.SKU_FK+'" onclick="removeItem(this.id,\''+row.vend_name+'\')">X</td></tr>';
			total_quantity += parseInt(row.quantity);
			total_amount += parseFloat(row.TOTAL_MRP_AMOUNT);
			mrp_amount+=(parseInt(row.quantity)*parseInt(row.MRP));
			if(is_tcs){
				row.tcs=parseFloat(row.MRP)*parseInt(row.quantity)*0.01;
			}
			else{
				row.tcs = 0;
			}
		})
		
		if(parseInt(is_tcs)){
		total_tcs = parseFloat(mrp_amount)*0.01;
		}
		else{
			total_tcs=0;
		}

	}
	total_payable= parseFloat(total_amount)+total_tcs;
	sub_total.value=total_amount.toFixed(2);
	tcs_amount.value=total_tcs;
	total_amount_pay.value=total_payable.toFixed(2);
	total_quantity_id.value=total_quantity;
	table.innerHTML=html;
}

const removeItem = (id,vend)=>{
	proformat_data = proformat_data.filter(row=>{
		return !(row.SKU_FK==id && row.vend_name==vend);
	});
	showPerformaData();
}
const applyTCS =(val)=>{
	let sub_total = document.getElementById('sub_total');
	let tcs_amount=document.getElementById('tcs_amount');
	let total_amount_pay_id = document.getElementById('total_amount_pay');
	let tcs_value=0;
	let total_amount=0;
	let total_amount_pay=0;

	if(parseInt(val) && proformat_data.length>0){

		proformat_data.map(row=>{
			total_amount += parseFloat(row.TOTAL_MRP_AMOUNT)
		})
		tcs_value=(total_amount*0.01).toFixed(2);
	}
	else{
		// console.log('No TCS');
		if(proformat_data.length>0){
		proformat_data.map(row=>{
			total_amount += parseFloat(row.TOTAL_MRP_AMOUNT)
		})
	}
		tcs_value=0
	}
	
	total_amount_pay = parseFloat(total_amount)+parseFloat(tcs_value);
	// console.log(total_amount_pay)
	tcs_amount.value=tcs_value;
	total_amount_pay_id.value=total_amount_pay;

}
const submitPerforma =()=>{
	var pro_date=document.getElementById('dispatch_date');
	// var dispatch_vend=document.getElementById('dispatch_vend');
	var customer_id=document.getElementById('customer_id');
	// var pro_date=document.getElementById('');
	if(proformat_data.length==0){
		alert('Please add some Items');
		return false;
	}
	// if(dispatch_vend.value==''){
	// 	alert('Please Select Vend Name');
	// 	dispatch_vend.focus();
	// 	return false
	// }
	if(customer_id.value==''){
		alert('Please Enter Customer Name');
		customer_id.focus();
		return false;
	}

	var item_details=JSON.stringify(proformat_data);
	// console.log(item_details);
	document.getElementById('item_data').value=(item_details);
	var url ='proformat_query.php';
	var form = document.querySelector('form');
	var data = new FormData(form);
	// console.log(data);
	// return false

	const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
    if(this.responseText.includes('All data Added Successfully')){
    	alert('All data Added Successfully');
    	window.location.href='proforma_invoice_master.php';
    }
    else{

    	// console.log(this.responseText);
    	alert('Something Went wrong');
    	window.location.reload();
    }
    }
  xhttp.open("POST", url, true);
  xhttp.send(data,encodeURIComponent(item_details));

}
</script>


</body>
</html>

