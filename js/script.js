//**********global variable declaration **********
var sr_no_tp=0;
var current_value=0;
var gd_total_excise =0;
var gd_total_custom =0;
var gd_total_vat =0;
var gd_total_wsp =0;
var consignment_receive = [];
var sale_declare_table = [];
var invData = [];
var updatedinvData = [];
var resultText='';
var total_dispatch_amount=0;



document.addEventListener('keydown', function(event) {

if(event.keyCode == 13) {
     	var active = document.activeElement;
     	if(active.hasAttribute('contenteditable')){
     		event.preventDefault();
     	}
     }

})
//****************************Sale declaration ************************//

const getInvData = () =>{
	var declare_date = document.getElementById('declare_date');
	var url = 'runBatch.php?firstLoad=loadData&date='+declare_date.value;
	var searh_bar = document.getElementById('seach_intab');
	searh_bar.value ='';
	
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	//document.getElementById("brand").innerHTML = this.responseText;
	console.log(this.responseText);

	resultText = xmlhttp.responseText;
	if(resultText.includes('No Data Found!!')){
		alert("No Data Found!!");
		invData = [];
	}
	else{
		console.log(resultText);
	var invArray = JSON.parse(resultText);
	//document.cookie = 'sale_declare_table=';
	invData = [];
	for(var i=0; i<invArray.length; i++){

		var object = {
					//POPS_INVENTORY_MANAGEMENT_PK: invArray[i].POPS_INVENTORY_MANAGEMENT_PK,
					SKU_FK: invArray[i].SKU_FK,
					GTIN: invArray[i].GTIN,
					INV_DATE: invArray[i].INV_DATE,

					BRAND_NAME: invArray[i].BRAND_NAME,
					CLOSING_BALANCE: invArray[i].CLOSING_BALANCE,
					COUNTER_CLOSING: invArray[i].COUNTER_CLOSING,
					COUNTER_OPENING: invArray[i].COUNTER_OPENING,

					DAMAGE_BOTTLES: invArray[i].DAMAGE_BOTTLES,
					DAMAGE_SALE: invArray[i].DAMAGE_SALE,
					ISSUE_STOCK: invArray[i].ISSUE_STOCK,
					OPENING_BOTTLES: invArray[i].OPENING_BOTTLES,

					RECEIVE_BOTTLES: invArray[i].RECEIVE_BOTTLES,
					SALE_BOTTLES: invArray[i].SALE_BOTTLES,
					//SHOP_DETAILS_FK: invArray[i].SHOP_DETAILS_FK,
					SIZE_VALUE: invArray[i].SIZE_VALUE,

					STORE_CLOSING: invArray[i].STORE_CLOSING,
					STORE_OPENING: invArray[i].STORE_OPENING,
					TOTAL_EXCISE_AMOUNT: invArray[i].TOTAL_EXCISE_AMOUNT,
					TOTAL_MRP_AMOUNT: invArray[i].TOTAL_MRP_AMOUNT,
					WSP: invArray[i].WSP,
					LIQUOR_TYPE: invArray[i].LIQUOR_TYPE_CD
					}  

					invData.push(object);

	}
	}
	//console.log(invData);
	showInventoryTable();

	}
	};
	xmlhttp.open("GET", url, true);
	xmlhttp.send(); 
}

const showInventoryTable = () =>{
	var table_id = document.getElementById('sale_declare_grid');
	var searh_bar = document.getElementById('seach_intab');
	//console.log(searh_bar);
	var liquor_type = document.getElementById('liquor_type');
	var i = 0;
	var html = "";
	var tempData = [];
	var brand_name=searh_bar.value;
	if(brand_name.length>=3 && liquor_type.value>0){
		tempData = invData.filter(data =>(data.BRAND_NAME.toLowerCase().includes(brand_name.toLowerCase()) && data.LIQUOR_TYPE==liquor_type.value));
	}
	else if(brand_name.length>=3){
		tempData = invData.filter(data =>data.BRAND_NAME.toLowerCase().includes(brand_name.toLowerCase()));
	}
	else if(liquor_type.value>0){
		tempData = invData.filter(data =>data.LIQUOR_TYPE==liquor_type.value);
	}
	else tempData = invData;
	if(tempData.length>0){
	tempData.map(row=>{
		html+="<tr id="+ row.SKU_FK +"><td>"+ ++i+"</td><td class='mid-text'>";
		html+= row.BRAND_NAME+"</td><td>";
		html+= row.SIZE_VALUE+"</td><td>";
		html+= row.TOTAL_MRP_AMOUNT+"</td><td contenteditable=true onblur="+"calculateValue(this.parentNode.id)"+">";
		html+= row.STORE_OPENING+"</td><td contenteditable=true onblur="+"calculateValue(this.parentNode.id)"+">";
		html+= row.RECEIVE_BOTTLES+"</td><td contenteditable=true onblur="+"calculateValue(this.parentNode.id)"+">";
		html+= row.ISSUE_STOCK+"</td><td contenteditable=true onblur="+"calculateValue(this.parentNode.id)"+">";
		html+= row.STORE_CLOSING+"</td><td contenteditable=true onblur="+"calculateValue(this.parentNode.id)"+">";
		html+= row.COUNTER_OPENING+"</td><td contenteditable=true onblur="+"calculateValue(this.parentNode.id)"+">";		
		html+= row.SALE_BOTTLES+"</td><td contenteditable=true onblur="+"calculateValue(this.parentNode.id)"+">";
		html+= row.COUNTER_CLOSING+"</td><td contenteditable=true onblur="+"calculateValue(this.parentNode.id)"+">";
		html+= row.DAMAGE_BOTTLES+"</td><td>";
		html+= row.DAMAGE_SALE+"</td></tr>";
	});
}
else{
	html+="<tr><td colspan=13 class='w3-center'>No Data Found</td></tr>"
}
	table_id.innerHTML = html;
}

const calculateValue = (id)=>{
	var error = false;
	var tr = document.getElementById(id);
	var cells = tr.getElementsByTagName('td');
	var store_opening = NaNtoBlank(cells[4].innerHTML);
	var counter_opening = NaNtoBlank(cells[8].innerHTML);
	var receiving = NaNtoBlank(cells[5].innerHTML);
	var issue_stoke = NaNtoBlank(cells[6].innerHTML);
	
	var sale = NaNtoBlank(cells[9].innerHTML);
	var damage = NaNtoBlank(cells[11].innerHTML);
	var store_closing = NaNtoBlank(cells[7].innerHTML);
	var counter_closing = NaNtoBlank(cells[10].innerHTML);
	var damage_sale = NaNtoBlank(cells[12].innerHTML);

	//var store_opening = parseInt(cells[4].innerHTML);
	store_closing = (store_opening + receiving - issue_stoke);
	counter_closing = counter_opening + issue_stoke - sale - damage;
	opening_balance = store_opening+counter_opening;
	closing_balance = store_closing + counter_closing;

	// if(issue_stoke > (store_closing + receiving)){
		if(store_closing<0){
		cells[6].innerHTML = '';
		alert("Issue stock cannot be more than store closing!!");
		error=true;
		cells[6].focus();
		return false;
	}
	// if(damage > (counter_closing + issue_stoke-sale)){
	// 	cells[9].innerHTML = '';
	// 	alert("Damage cannot be more than counter closing!!");
	// 	error=true;
	// 	cells[9].focus();
	// 	return false;
	// }
	// if(sale > (counter_closing + issue_stoke-damage)){
	if(counter_closing < 0){
		cells[9].innerHTML = '';
		alert("Sale cannot be more than counter closing!!");
		error=true;
		cells[9].focus();
		return false;
	}
	
	cells[4].innerHTML=(store_opening);
	cells[8].innerHTML=(counter_opening);
	cells[5].innerHTML=(receiving);
	cells[6].innerHTML=(issue_stoke);
	
	cells[9].innerHTML=(sale);
	cells[11].innerHTML=(damage);
	cells[7].innerHTML=(store_closing);
	cells[10].innerHTML=(counter_closing);
	cells[12].innerHTML=(damage_sale);
	if(!error){
	for(var i in invData){
		if(invData[i].SKU_FK == id){
			// invData = invData.filter(row=>{
			// 	return row.SKU_FK != id;
			// })
			invData[i].OPENING_BOTTLES = opening_balance;
			invData[i].CLOSING_BALANCE = closing_balance;
			invData[i].COUNTER_CLOSING = counter_closing;
			invData[i].COUNTER_OPENING = counter_opening;
			invData[i].DAMAGE_BOTTLES = damage;
			invData[i].DAMAGE_SALE = damage_sale;
			invData[i].ISSUE_STOCK = issue_stoke;
			invData[i].RECEIVE_BOTTLES = receiving;
			invData[i].SALE_BOTTLES = sale;
			invData[i].STORE_CLOSING = store_closing;
			invData[i].STORE_OPENING = store_opening;
			// invData.push(invData[i]);
		}
	}
	var sale_declare = JSON.stringify(invData);
	document.cookie='sale_declare_table='+sale_declare;
}

	// cells[4].innerHTML=store_opening;
	// cells[4].innerHTML=store_opening;
	// cells[4].innerHTML=store_opening;


	
}

const NaNtoBlank = (str) =>{
	if(str.length>0)
		return parseInt(str);
	else
		return '';
}

function getDispatchData1(){
var input = document.getElementById('bnd_id');
var size = document.getElementById('search_size');
var qty = document.getElementById('qty');
var discount_id = document.getElementById('discount_id');

var grid_id = document.getElementById('product_grid');
var url = 'update-brand.php?&brandname='+encodeURIComponent(input.value)+'&brandsize='+size.value;

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
else{
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
	// console.log(this.responseText);
var response = JSON.parse(xmlhttp.responseText);
var state = false;
var qty_state = false;
var available_qty = 0;
var tcs = 0;
var cost=0;
var vat1=0;
var customer_type = document.getElementById('customer_type').value;
consignment_receive.map(item=>{
if(item.product_id == response[0].POPS_PRICE_MASTER_PK){
state = true;
}
});

if(state){
alert("Product exists in the table !! Add another item or Change Product Size");
size.focus();
return false;
}


else{

	cost= response[0].RETAIL_PRICE*0.9901;
		// console.log(cost)
		cost = (cost*(100-parseFloat(discount_id.value))*0.01);
		// console.log(cost)

		vat1 = (cost)*0.01;
		cost = cost+vat1;
		// console.log(cost)
	if(customer_type!='CLUB'){
		

		tcs=(cost)*0.01;
		// console.log(tcs)

	}
var object = {
product_id: response[0].POPS_PRICE_MASTER_PK,
SKU_FK: response[0].SKU_FK,
RETAIL_PRICE: response[0].RETAIL_PRICE,
product_name: response[0].BRAND_NAME,
product_size: size.value,
quantity: parseInt(qty.value),
discount: parseFloat(discount_id.value),
excise_rate: parseFloat(nullToZero(response[0].EXCISE_PRICE)).toFixed(2),
TCS: tcs,
wsp: (parseFloat(nullToZero(response[0].WSP))).toFixed(2),
MRP: parseFloat(nullToZero(cost)).toFixed(2),
TOTAL_MRP_AMOUNT: parseFloat((cost)*parseInt(qty.value)).toFixed(2),

custom: parseFloat(nullToZero(response[0].CUSTOM_DUTY)).toFixed(2),

}
consignment_receive.unshift(object);
qty.value='';
showDispatchProductGrid1();
}
}
};
xmlhttp.open("GET", url, true);
xmlhttp.send();
}
}

function showDispatchProductGrid1(){
var grid_id = document.getElementById('product_grid');

grid_id.innerHTML = '';
var gd_total_quantity_id = document.getElementById('total_quantity');
var gd_total_mrp = document.getElementById('total_mrp_amount');
var gd_total_tcs = document.getElementById('total_tcs');
// var gd_total_custom_id = document.getElementById('total_custom');
var gd_total_receivable = document.getElementById('total_receivable');
var grand_total_mrp =0;
var grand_total_tcs = 0;
var grand_total_quantity=0;

var grand_total_amount=0;
var i = 0;
consignment_receive.map(brand=>{
var row = grid_id.insertRow(i);
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);
var cell5 = row.insertCell(4);
var cell6 = row.insertCell(5);
var cell7 = row.insertCell(6);
var cell8 = row.insertCell(7);
var cell9 = row.insertCell(8);
var cell10 = row.insertCell(9);



cell1.innerHTML =  ++i ;
cell2.innerHTML =  brand.product_name;
cell3.innerHTML = brand.product_size;
cell4.innerHTML = brand.quantity;
cell5.innerHTML = brand.RETAIL_PRICE;
cell6.innerHTML = brand.discount;
cell7.innerHTML = parseFloat(brand.MRP)*parseInt(brand.quantity);
cell8.innerHTML = (parseFloat(brand.TCS)*parseInt(brand.quantity)).toFixed(2);
cell9.innerHTML = (parseFloat(brand.MRP)*parseInt(brand.quantity)+(parseFloat(brand.TCS)*parseInt(brand.quantity))).toFixed(2);

cell10.innerHTML = '<a style="cursor:pointer;" id="'+brand.product_id+'" onclick="deleteItem111(this.id)" >'+ 'X' + '</a>';
grand_total_quantity=parseInt(grand_total_quantity)+parseInt(brand.quantity);
grand_total_mrp = (parseFloat(grand_total_mrp)+parseFloat(brand.MRP)*parseInt(brand.quantity)).toFixed(2);
grand_total_tcs = parseFloat(grand_total_tcs)+parseFloat(brand.TCS)*parseInt(brand.quantity);
});
grand_total_amount = parseFloat(grand_total_mrp)+parseFloat(grand_total_tcs);


 gd_total_quantity_id.value=grand_total_quantity;
 gd_total_mrp.value=grand_total_mrp;
 gd_total_tcs.value=grand_total_tcs.toFixed(2);
 gd_total_receivable.value=grand_total_amount.toFixed(2);


var consignment = JSON.stringify(consignment_receive);
document.cookie='received_consignment='+consignment;
}
const getSaleData = () => {

	var ivn_date = document.getElementById('declare_date');
	var input = document.getElementById('bnd_id');
	var size = document.getElementById('search_size');
	var store_qty = document.getElementById('store_qty');
	var counter_qty = document.getElementById('counter_qty'); 
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
		else if(store_qty.value == '' || store_qty.value < 0){

		alert("Please Enter quantity in Store");
		store_qty.focus();
		return false;
		}
		else if(counter_qty.value == ''){

		alert("Please Enter quantity at Counter");
		counter_qty.focus();
		return false;
		}
		else{

			var url = 'runBatch.php?brand='+encodeURIComponent(input.value)+'&size='+size.value+'&nextDate='+declare_date.value;
			 var xhttp = new XMLHttpRequest();
			 //console.log(url);
			  xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			     var invArray = JSON.parse(xhttp.responseText);
			     //console.log(invArray);
			     var state = false;
				invData.map(item=>{
				if(item.SKU_FK == invArray[0].SKU_FK){
				state = true;
				}
				});

				if(state){
				alert("Product exists in the table !! Add another item or Change Product Size");
				size.focus();
				return false;
				}
			     var store_opening= parseInt(store_qty.value);
			     var counter_opening = parseInt(counter_qty.value);
			     var issue_stoke = 0;
			     var receiving = 0;
			     var sale = 0;
			     var damage = 0;
			     var damage_sale = 0;
			     var store_closing = (store_opening + receiving - issue_stoke);
			     var counter_closing = counter_opening + issue_stoke - sale - damage;
			     var opening_balance = store_opening+counter_opening;
			     var closing_balance = store_closing + counter_closing;

					var object = {
					//POPS_INVENTORY_MANAGEMENT_PK: invArray[i].POPS_INVENTORY_MANAGEMENT_PK,
					SKU_FK: invArray[0].SKU_FK,
					GTIN: invArray[0].GTIN_NO,
					INV_DATE: ivn_date.value,

					BRAND_NAME: invArray[0].BRAND_NAME,
					CLOSING_BALANCE: closing_balance,
					COUNTER_CLOSING: counter_closing,
					COUNTER_OPENING: counter_opening,

					DAMAGE_BOTTLES: damage,
					DAMAGE_SALE: damage_sale,
					ISSUE_STOCK: issue_stoke,
					OPENING_BOTTLES: opening_balance,

					RECEIVE_BOTTLES: receiving,
					SALE_BOTTLES: sale,
					//SHOP_DETAILS_FK: invArray[0].SHOP_DETAILS_FK,
					SIZE_VALUE: invArray[0].SIZE_VALUE,

					STORE_CLOSING: store_closing,
					STORE_OPENING: store_opening,
					TOTAL_EXCISE_AMOUNT: invArray[0].EXCISE_PRICE,
					TOTAL_MRP_AMOUNT: invArray[0].RETAIL_PRICE,
					WSP: invArray[0].WSP
					}         
					invData.unshift(object); 
					store_qty.value='';
					counter_qty.value='';
					showInventoryTable();
					//console.log(sale_table);

			    }
			  };
			  xhttp.open("GET", url , true);
			  xhttp.send();
		}

}

const insertSaleDeclarationData = async () => {
	if(invData.length==0){
		alert("Add Product First!!");
		document.getElementById('bnd_id').focus();
		return false;
	}
	else{
		// var count1=0;
		document.getElementById('loading').style.display='block';
		var invDate = document.getElementById('declare_date').value;
		let promises = [];
		invData.map(row=>{
			promises.push(insertInvData(invDate,row));
		});	
		Promise.all(promises)
		.then((result)=>{
			document.getElementById('loading').style.display='none';
			alert("All Data Submitted Successfully");
			window.location.reload();
		})
		.catch((err)=>{
			document.getElementById('loading').style.display='none';
			alert("Something went Wrong!! Try Again");
		})
	}
}


const insertInvData = (date,row) => {
	// let count=0;
	return new Promise ((resolve,reject)=>{
		arraydata = JSON.stringify(row);
		var sku_fk=row.SKU_FK;
		var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange =  function() {
		if (this.readyState == 4 && this.status == 200) {
		 if(this.responseText.includes('All Data Submitted Successfully')){
			 resolve(this.responseText);
		 }		
		}
	  };
	  xhttp.open("POST", "runBatch.php", true);
	  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	  xhttp.send("sale_declare="+encodeURIComponent(arraydata)+"&invDate="+date+"&SKU_FK="+sku_fk);
	
	// console.log(count);
});
}


const runBatch = () => {
	let declateDate = document.getElementById('declare_date').value;
	document.getElementById('loading').style.display='block';
	let url = 'runBatch.php?runDate='+declateDate;
	//console.log("in js", declateDate);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {

	if (this.readyState == 4 && this.status == 200) {
		// console.log(this.responseText);
		if(this.responseText.includes("Batch for this Date has already been run!")){
			alert("Batch for this date has already been Run!!");
		}
		else if(this.responseText.includes("All Data Inserted!!")){
				alert("All Data Inserted!!");
		}
		else{
			// alert('Something went Wrong!!!');
			console.log(this.responseText);
		}
		document.getElementById('loading').style.display='none';
		getInvData();
	}
	};
	xmlhttp.open("GET",url,true);
	xmlhttp.send();

}




//*******************End Sale Declaration *****************************//

//***************Clock function**************

function startTime() {
var today = new Date();
var h = today.getHours();
var pre = h>=12?' PM':' AM ';if(h>12){
h=checkTime(h%12);
}
var m = today.getMinutes();
var s = today.getSeconds();
m = checkTime(m);
s = checkTime(s);
document.getElementById('txt').innerHTML =
h + ":" + m + ":" + s+pre;
var t = setTimeout(startTime, 500);
}

function setCurrentValue(obj){
current_value = obj.value;
}



function checkTime(i) {
if (i < 10) {i = "0" + i};  // add zero in front of numbers < 10
return i;
}
startTime();

function validateAlphaNumeric(str){
var id = document.getElementById(str);
var letter = /[a-zA-Z]/; 
var number = /[0-9]/;
if(id.value.length >0){
var valid = number.test(id.value) && letter.test(id.value) && id.value.length >7;
if(!valid){
var tooltip = document.getElementsByClassName('tooltiptext');
//console.log(tooltip);
tooltip[0].style.visibility='visible';
//id.focus();
}
else{
var tooltip = document.getElementsByClassName('tooltiptext');
//console.log(tooltip);
tooltip[0].style.visibility='hidden';

}
}
}

// get todays date

function getPreviousDate(){
var yesterday = new Date();
yesterday.setDate(yesterday.getDate()-1);
var dd = String(yesterday.getDate()).padStart(2, '0');
var mm = String(yesterday.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = yesterday.getFullYear();

yesterday = yyyy+'-'+mm+'-'+dd;
return yesterday;
}

function getTodaysDate(){
var today = new Date();
today.setDate(today.getDate());
var dd = String(today.getDate()).padStart(2, '0');
var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
var yyyy = today.getFullYear();

today = yyyy+'-'+mm+'-'+dd;
return today;
}

const getFirstDay = () =>{
	var date = new Date();
	var firstDay = new Date(date.getFullYear(), date.getMonth(), 1);
	var dd = String(firstDay.getDate()).padStart(2, '0');
	var mm = String(firstDay.getMonth() + 1).padStart(2, '0'); //January is 0!
	var yyyy = firstDay.getFullYear();
	firstDay = yyyy+'-'+mm+'-'+dd;
	return firstDay;
}

const setDate = () =>{
	var receive_date = document.getElementById('receive_date');
	var dispatch_date = document.getElementById('dispatch_date');
	receive_date.value = getPreviousDate();
	dispatch_date.value = getPreviousDate();
	//console.log(receive_date.value);
	receive_date.max=getTodaysDate();
	dispatch_date.max=getTodaysDate();

}

const setDateDispatch = () =>{
	// var receive_date = document.getElementById('receive_date');
	var dispatch_date = document.getElementById('dispatch_date');
	// receive_date.value = getPreviousDate();
	dispatch_date.value = getTodaysDate();
	//console.log(receive_date.value);
	// receive_date.max=getPreviousDate();
	dispatch_date.max=getTodaysDate();

}

function getTaxes(){
var retail = document.getElementById('mrp');
var excise = document.getElementById('excise_price');
var total_tax = document.getElementById('total_tax');
var retail_profit = document.getElementById('retail_profit');
var sale_tax = document.getElementById('sale_tax');

var custom = document.getElementById('custom_duty');
var ws_price = document.getElementById('ws_price');
var retailPrice = retail.value;
var wsPrice = ws_price.value;
var excise_tax=excise.value;
var custom_charge = custom.value;

var t_Tax = excise_tax;
var saleTax = ((retailPrice)*.01).toFixed(2);
var retailProfit = retailPrice - (parseFloat(wsPrice)+parseFloat(excise_tax)+parseFloat(custom_charge)+parseFloat(saleTax)).toFixed(2);
if(retailProfit<0){
alert("Please Enter the prices correctlly!!");
this.focus();
}
else{
total_tax.value = excise_tax;
retail_profit.value = retailProfit;
sale_tax.value = saleTax;
}
}



function getBrandName(){
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("brand").innerHTML = this.responseText;
}
};
xmlhttp.open("GET", "update-brand.php?brand=dsefs", true);
xmlhttp.send();
}
function getAllBrandName(){
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("brand").innerHTML = this.responseText;
}
};
xmlhttp.open("GET", "update-brand.php?Allbrand=amrit", true);
xmlhttp.send();
}


function getSupplierName(){
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {

document.getElementById("supplier").innerHTML = this.responseText;
}
};
xmlhttp.open("GET", "update-brand.php?supplier1=all", true);
xmlhttp.send();
}
function getallSupplierName(){
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {

document.getElementById("supplier").innerHTML = this.responseText;

}
};
xmlhttp.open("GET", "update-brand.php?supplier=all&fun=getSupplierName", true);
xmlhttp.send();
}




function getCustomerName(){
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
// console.log(this.responseText);
document.getElementById("customer").innerHTML = this.responseText;

}
};
xmlhttp.open("GET", "update-brand.php?customer=all", true);
xmlhttp.send();
}






function getCustomerDetails(){
var customer = document.getElementById('customer_id');
if(customer.value==''){
	alert("Please enter Customer Name!!");
	customer.focus();
}
else{
var url = 'update-brand.php?customer='+encodeURIComponent(customer.value);
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
//document.getElementById("supplier").innerHTML = this.responseText;
var response = JSON.parse(xmlhttp.responseText);
// console.log(response);
if(Object.keys(response).length !== 0){
	var business_Name = document.getElementById('Business_Name');
	var customer_Code = document.getElementById('Customer_Code')
	var companyname = document.getElementById('companyname')
	var email = document.getElementById('email')
	var licencecode = document.getElementById('licencecode')
	var tinno = document.getElementById('tinno')
	var phone = document.getElementById('phone');
	var address = document.getElementById('address');
	var pan_number = document.getElementById('pan_number');
	var pin = document.getElementById('pin');
	var owner_name = document.getElementById('Owner_name');
	var cust_type = document.getElementById('cust_type');
	var email = document.getElementById('email');
	var submit = document.getElementById('submit_id');
	

	business_Name.value = response.BUSINESS_NAME;
	customer_Code.value=response.CUSTOMER_CODE;
	companyname.value=response.COMPANY_NAME;
	email.value=response.EMAIL;
	tinno.value=response.TIN_NO;
	cust_type.value=response.DESCRIPTION;
	phone.value = response.CONTACT_NO;
	address.value = response.BUSINESS_ADDRESS;
	pan_number.value = response.PAN_NO;
	pin.value = response.PIN_NO;
	owner_name.value = response.OWNER;
	licencecode.value = response.LICENCE_CODE;
	email.value = response.EMAIL;
	submit.disabled = true;
	// update.disabled = false;
	business_Name.readOnly=true
	licence.readOnly=true
	//supp_code.value = response.SUPP_CODE;

	//console.log(response);

}
else{
alert("No Data Found");
// window.location.reload();
}
}
};
xmlhttp.open("GET", url, true);
xmlhttp.send();
}
}

const getScrapBuyer = () =>{
			var datalist = document.getElementById('scrap_buyer');
			var xmlhttp = new XMLHttpRequest();
			xmlhttp.onreadystatechange = function() {
			if (this.readyState == 4 && this.status == 200) {

			datalist.innerHTML = this.responseText;
			// console.log(this.responseText)
			}
			};
			xmlhttp.open("GET", "update-brand.php?scrap_buyer=all", true);
			xmlhttp.send();
		}

function getBrandSize(){
var brandName = document.getElementById('bnd_id').value;
if(brandName.length > 2){
var url = 'update-brand.php?brandname='+encodeURIComponent(brandName);

var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("search_size").innerHTML = this.responseText;
}
};
xmlhttp.open("GET", url, true);
xmlhttp.send();
}
}

function getBrandSizeAll(){
var brandName = document.getElementById('bnd_id').value;
if(brandName.length > 2){
var url = 'update-brand.php?brandname='+encodeURIComponent(brandName)+'&fun=getBrandSizeAll';

var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
document.getElementById("search_size").innerHTML = this.responseText;
}
};
xmlhttp.open("GET", url, true);
xmlhttp.send();
}
}



function getBrandData(){
var input = document.getElementById('bnd_id');
var size = document.getElementById('search_size');
var bnt = document.getElementById('search_btn');

var url = 'update-brand.php?brandname='+encodeURIComponent(input.value)+'&brandsize='+size.value;

var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
var response = JSON.parse(xmlhttp.responseText);
document.getElementById('brand_table_pk').value = response[0].POPS_PRICE_MASTER_PK;
document.getElementById('brand_id').value = response[0].SKU_FK;
document.getElementById('gtin_no').value = response[0].GTIN_NO;
document.getElementById('brand_name').value = response[0].BRAND_NAME;
document.getElementById('size_value').value = response[0].SIZE_VALUE;
document.getElementById('pack_size').value = response[0].PACK_SIZE;
document.getElementById('category').value = response[0].LIQUOR_TYPE_CD;
document.getElementById('sub_category').value = response[0].CATEGORY_CD;
document.getElementById('mrp').value = parseFloat(nullToZero(response[0].RETAIL_PRICE));
document.getElementById('ws_price').value = parseFloat(nullToZero(response[0].WSP));
document.getElementById('custom_duty').value = parseFloat(nullToZero(response[0].CUSTOM_DUTY));
document.getElementById('excise_price').value = parseFloat(nullToZero(response[0].EXCISE_PRICE));
document.getElementById('vend_fee').value = parseFloat(nullToZero(response[0].VEND_FEE));
document.getElementById('total_tax').value = parseFloat(nullToZero(response[0].TOT_TAX));
document.getElementById('itax').value = parseFloat(nullToZero(response[0].ITAX));
document.getElementById('retail_profit').value = parseFloat(nullToZero(response[0].RETAIL_PROFIT));
document.getElementById('sale_tax').value = parseFloat(nullToZero(response[0].SALE_TAX));
document.getElementById('rebate').value = parseFloat(nullToZero(response[0].REBATE));
document.getElementById('D_HCR').value = parseFloat(nullToZero(response[0].HCR_DISCOUNT));
document.getElementById('D_Retail').value = parseFloat(nullToZero(response[0].RETAIL_DISCOUNT));
document.getElementById('Low_inv').value = parseFloat(nullToZero(response[0].LOW_INVENTORY));
document.getElementById('brand_no').value = parseFloat(nullToZero(response[0].brand_code));
document.getElementById('case_no').value = parseFloat(nullToZero(response[0].case_no));
// document.getElementById('submit').disabled=false;
// document.getElementById('update').disabled=false;
}
};
xmlhttp.open("GET", url, true);
xmlhttp.send();
}

function getTpData(){
var input = document.getElementById('bnd_id');
var size = document.getElementById('search_size');
var qty = document.getElementById('qty');
var grid_id = document.getElementById('product_grid');
var url = 'update-brand.php?brandname='+encodeURIComponent(input.value)+'&brandsize='+size.value;

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
else{
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
var response = JSON.parse(xmlhttp.responseText);
var state = false;
consignment_receive.map(item=>{
if(item.product_id == response[0].POPS_PRICE_MASTER_PK){
state = true;
}
});

if(state){
alert("Product exists in the table !! Add another item or Change Product Size");
size.focus();
return false;
}
        
else{
var object = {
product_id: response[0].POPS_PRICE_MASTER_PK,
SKU_FK: response[0].SKU_FK,
MRP:response[0].RETAIL_PRICE,
product_name: response[0].BRAND_NAME,
product_size: size.value,
quantity: parseInt(qty.value)*parseInt(response[0].PACK_SIZE),
excise_rate: parseFloat(nullToZero(response[0].EXCISE_PRICE)).toFixed(2),
total_excise: (qty.value*parseFloat(nullToZero(response[0].EXCISE_PRICE))*parseInt(response[0].PACK_SIZE)).toFixed(2),
wsp: parseFloat(nullToZero(response[0].WSP)).toFixed(2),
total_wsp: (qty.value*parseFloat(nullToZero(response[0].WSP))*parseInt(response[0].PACK_SIZE)).toFixed(2),
custom: parseFloat(nullToZero(response[0].CUSTOM_DUTY)).toFixed(2),
total_custom: (qty.value*parseFloat(nullToZero(response[0].CUSTOM_DUTY))*parseInt(response[0].PACK_SIZE)).toFixed(2)
}              
consignment_receive.unshift(object);    
qty.value=''; 
showProductGrid();   
}             
}
};
xmlhttp.open("GET", url, true);
xmlhttp.send();
}
}

function getDispatchData(){
var input = document.getElementById('bnd_id');
var size = document.getElementById('search_size');
var qty = document.getElementById('qty');
var grid_id = document.getElementById('product_grid');
var url = 'update-brand.php?&brandname='+encodeURIComponent(input.value)+'&brandsize='+size.value;

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
else{
var xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
if (this.readyState == 4 && this.status == 200) {
	// console.log(this.responseText);
var response = JSON.parse(xmlhttp.responseText);
var state = false;
var qty_state = false;
var available_qty = 0;
var tcs = 0;
var customer_type = document.getElementById('customer_type').value;
consignment_receive.map(item=>{
if(item.product_id == response[0].POPS_PRICE_MASTER_PK){
state = true;
}
});
 
if(state){
alert("Product exists in the table !! Add another item or Change Product Size");
size.focus();
return false;
}

  
else{
	if(customer_type!='CLUB'){
		tcs=(response[0].RETAIL_PRICE)*0.01;
	}
var object = {
product_id: response[0].POPS_PRICE_MASTER_PK,
SKU_FK: response[0].SKU_FK,
MRP:response[0].RETAIL_PRICE,
product_name: response[0].BRAND_NAME,
product_size: size.value,
quantity: parseInt(qty.value),
excise_rate: parseFloat(nullToZero(response[0].EXCISE_PRICE)).toFixed(2),
TCS: tcs,
wsp: (parseFloat(nullToZero(response[0].WSP))).toFixed(2),
MRP: parseFloat(nullToZero(response[0].RETAIL_PRICE)).toFixed(2),
TOTAL_MRP_AMOUNT: parseFloat(nullToZero(response[0].RETAIL_PRICE)*parseInt(qty.value)).toFixed(2),

custom: parseFloat(nullToZero(response[0].CUSTOM_DUTY)).toFixed(2),

}              
consignment_receive.unshift(object);    
qty.value=''; 
showDispatchProductGrid();   
}             
}
};
xmlhttp.open("GET", url, true);
xmlhttp.send();
}
}



const nullToZero = (num) => {
if(num === null){
return 0.00;
}
else return num;
}
function reSetFormdata(){
document.getElementById('bnd_id').value = '';
document.getElementById('search_size').value = 0;
document.getElementById('brand_table_pk').value = '';
document.getElementById('brand_id').value = '';
document.getElementById('gtin_no').value = '';
document.getElementById('brand_name').value = '';
document.getElementById('size_value').value = 0;
document.getElementById('pack_size').value = '';
document.getElementById('category').value = 0;
document.getElementById('sub_category').value = 0;
document.getElementById('mrp').value = 0;
document.getElementById('ws_price').value = 0;
document.getElementById('custom_duty').value = 0;
document.getElementById('excise_price').value = 0;
document.getElementById('vend_fee').value = 0;
document.getElementById('total_tax').value = 0;
document.getElementById('itax').value = 0;
document.getElementById('retail_profit').value = 0;
document.getElementById('sale_tax').value = 0;
document.getElementById('rebate').value = 0;
document.getElementById('submit').disabled=false;
document.getElementById('update').disabled=true;
}
function showProductGrid(){
var grid_id = document.getElementById('product_grid');

grid_id.innerHTML = '';  
var gd_total_quantity_id = document.getElementById('total_quantity');
var gd_total_wsp_id = document.getElementById('total_wsp');
var gd_total_excise_id = document.getElementById('total_excise');
var gd_total_custom_id = document.getElementById('total_custom');
var gd_total_vat_id = document.getElementById('total_vat');
var gd_total_amount_id = document.getElementById('total_amount');
var gd_other_charges_id = document.getElementById('other_charges');


var gd_total_excise =0;
var gd_total_custom =0;
var gd_total_vat =0;


var grand_total_excise =0;
var grand_total_wsp = 0;
var grand_total_quantity=0;
var grand_total_vat=0;
var grand_total_custom=0;
var grand_total_amount=0;
// var gd_total_vat_id = document.getElementById('');
var i = 0;
consignment_receive.map(brand=>{
var row = grid_id.insertRow(i);
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);
var cell5 = row.insertCell(4);
var cell6 = row.insertCell(5);
var cell7 = row.insertCell(6);
var cell8 = row.insertCell(7);
var cell9 = row.insertCell(8);
cell1.innerHTML =  ++i ;
cell2.innerHTML =  brand.product_name;
cell3.innerHTML = brand.product_size;
cell4.innerHTML = brand.quantity;
cell5.innerHTML = brand.excise_rate;
cell6.innerHTML = brand.total_excise;
cell7.innerHTML = brand.wsp;
cell8.innerHTML = brand.total_wsp;
cell9.innerHTML = '<a style="cursor:pointer;" id="'+brand.product_id+'" onclick="deleteItem(this.id)" >'+ 'X' + '</a>';
grand_total_quantity=parseInt(grand_total_quantity)+parseInt(brand.quantity);
grand_total_wsp = parseFloat(grand_total_wsp)+parseFloat(brand.total_wsp);
grand_total_excise = parseFloat(grand_total_excise)+parseFloat(brand.total_excise);
grand_total_custom = parseFloat(grand_total_custom)+parseFloat(brand.total_custom);
});

gd_total_quantity_id.value=grand_total_quantity;
gd_total_wsp_id.value=grand_total_wsp.toFixed(2);
gd_total_excise_id.value=grand_total_excise.toFixed(2);
gd_total_custom_id.value=grand_total_custom.toFixed(2);

var tcs_amount = parseFloat(gd_other_charges_id.value);
var grand_total_vat = (grand_total_excise+grand_total_wsp+grand_total_custom)*0.01;
gd_total_vat_id.value=grand_total_vat.toFixed(2);
grand_total_tcs=(grand_total_vat+grand_total_wsp+grand_total_excise+grand_total_custom)*.01;
gd_other_charges_id.value=grand_total_tcs.toFixed(2);
gd_total_amount_id.value=(grand_total_vat+grand_total_wsp+grand_total_excise+grand_total_custom+grand_total_tcs).toFixed(2);
gd_total_wsp = grand_total_wsp;
gd_total_excise = grand_total_excise;
gd_total_vat = grand_total_vat;


var consignment = JSON.stringify(consignment_receive);
document.cookie='received_consignment='+consignment;

}

function showDispatchProductGrid(){
var grid_id = document.getElementById('product_grid');

grid_id.innerHTML = '';  
var gd_total_quantity_id = document.getElementById('total_quantity');
var gd_total_mrp = document.getElementById('total_mrp_amount');
var gd_total_tcs = document.getElementById('total_tcs');
// var gd_total_custom_id = document.getElementById('total_custom');
var gd_total_receivable = document.getElementById('total_receivable');
// var gd_total_amount_id = document.getElementById('total_amount');
// var gd_other_charges_id = document.getElementById('other_charges');


// var gd_total_excise =0;
// var gd_total_custom =0;
// var gd_total_vat =0;


var grand_total_mrp =0;
var grand_total_tcs = 0;
var grand_total_quantity=0;

var grand_total_amount=0;
// var gd_total_vat_id = document.getElementById('');
var i = 0;
consignment_receive.map(brand=>{
var row = grid_id.insertRow(i);
var cell1 = row.insertCell(0);
var cell2 = row.insertCell(1);
var cell3 = row.insertCell(2);
var cell4 = row.insertCell(3);
var cell5 = row.insertCell(4);
var cell6 = row.insertCell(5);
var cell7 = row.insertCell(6);
var cell8 = row.insertCell(7);
var cell9 = row.insertCell(8);


cell1.innerHTML =  ++i ;
cell2.innerHTML =  brand.product_name;
cell3.innerHTML = brand.product_size;
cell4.innerHTML = brand.quantity;
cell5.innerHTML = brand.MRP;
cell6.innerHTML = parseFloat(brand.MRP)*parseInt(brand.quantity);
cell7.innerHTML = (parseFloat(brand.TCS)*parseInt(brand.quantity)).toFixed(2);
cell8.innerHTML = parseFloat(brand.MRP)*parseInt(brand.quantity)+(parseFloat(brand.TCS)*parseInt(brand.quantity));

cell9.innerHTML = '<a style="cursor:pointer;" id="'+brand.product_id+'" onclick="deleteItem(this.id)" >'+ 'X' + '</a>';
grand_total_quantity=parseInt(grand_total_quantity)+parseInt(brand.quantity);
grand_total_mrp = parseFloat(grand_total_mrp)+parseFloat(brand.MRP)*parseInt(brand.quantity);
grand_total_tcs = parseFloat(grand_total_tcs)+parseFloat(brand.TCS)*parseInt(brand.quantity);
});
grand_total_amount = parseFloat(grand_total_mrp)+parseFloat(grand_total_tcs);


 gd_total_quantity_id.value=grand_total_quantity;
 gd_total_mrp.value=grand_total_mrp;
 gd_total_tcs.value=grand_total_tcs.toFixed(2);
 gd_total_receivable.value=grand_total_amount;


var consignment = JSON.stringify(consignment_receive);
document.cookie='received_consignment='+consignment;
}


// function showDispatchProductGrid(){
// var grid_id = document.getElementById('product_grid');

// grid_id.innerHTML = '';  
// var gd_total_quantity_id = document.getElementById('total_quantity');
// var gd_total_wsp_id = document.getElementById('total_wsp');
// var gd_total_excise_id = document.getElementById('total_excise');
// var gd_total_custom_id = document.getElementById('total_custom');
// var gd_total_vat_id = document.getElementById('total_vat');
// var gd_total_amount_id = document.getElementById('total_amount');
// var gd_other_charges_id = document.getElementById('other_charges');
// var final_amount=0;

// var gd_total_excise =0;
// var gd_total_custom =0;
// var gd_total_vat =0;


// var grand_total_excise =0;
// var grand_total_wsp = 0;
// var grand_total_quantity=0;
// var grand_total_vat=0;
// var grand_total_custom=0;
// var grand_total_amount=0;
// var grand_total_amount_mrp = 0;
// // var gd_total_vat_id = document.getElementById('');
// var i = 0;
// consignment_receive.map(brand=>{
// var row = grid_id.insertRow(i);
// var cell1 = row.insertCell(0);
// var cell2 = row.insertCell(1);
// var cell3 = row.insertCell(2);
// var cell4 = row.insertCell(3);
// var cell5 = row.insertCell(4);
// var cell6 = row.insertCell(5);
// var cell7 = row.insertCell(6);
// var cell8 = row.insertCell(7);
// var cell9 = row.insertCell(8);
// // var cell10 = row.insertCell(9);
// // var cell11 = row.insertCell(10);
// // var cell12 = row.insertCell(11);
// cell1.innerHTML =  ++i ;
// cell2.innerHTML =  brand.product_name;
// cell3.innerHTML = brand.product_size;
// cell4.innerHTML = brand.quantity;
// cell5.innerHTML = brand.excise_rate;
// cell6.innerHTML = brand.total_excise;
// cell7.innerHTML = brand.wsp;
// cell8.innerHTML = brand.total_wsp;
// // cell9.innerHTML = brand.MRP;
// // cell10.innerHTML = brand.MRP;
// // cell10.innerHTML = brand.TOTAL_MRP_AMOUNT;
// cell9.innerHTML = '<a style="cursor:pointer;" id="'+brand.product_id+'" onclick="deleteItem(this.id)" >'+ 'X' + '</a>';
// grand_total_quantity=parseInt(grand_total_quantity)+parseInt(brand.quantity);
// grand_total_wsp = parseFloat(grand_total_wsp)+parseFloat(brand.total_wsp);
// grand_total_excise = parseFloat(grand_total_excise)+parseFloat(brand.total_excise);
// grand_total_custom = parseFloat(grand_total_custom)+parseFloat(brand.total_custom);
// grand_total_amount_mrp += parseFloat(brand.TOTAL_MRP_AMOUNT);

// });

// gd_total_quantity_id.value=grand_total_quantity;
// gd_total_wsp_id.value=grand_total_wsp.toFixed(2);
// gd_total_excise_id.value=grand_total_excise.toFixed(2);
// gd_total_custom_id.value=grand_total_custom.toFixed(2);

// var tcs_amount = parseFloat(gd_other_charges_id.value);
// var grand_total_vat = (grand_total_excise+grand_total_wsp+grand_total_custom)*0.01;
// gd_total_vat_id.value=grand_total_vat.toFixed(2);
// grand_total_tcs=(grand_total_vat+grand_total_wsp+grand_total_excise+grand_total_custom)*.01;
// gd_other_charges_id.value=grand_total_tcs.toFixed(2);

// final_amount = (parseFloat(grand_total_wsp)+parseFloat(grand_total_excise)+parseFloat(grand_total_custom)+parseFloat(grand_total_vat)+parseFloat(grand_total_tcs));
// // gd_total_amount_id.value=(grand_total_vat+grand_total_wsp+grand_total_excise+grand_total_custom+grand_total_tcs).toFixed(2);
// // console.log(final_amount);

// gd_total_amount_id.value=final_amount.toFixed(2);
// gd_total_wsp = grand_total_wsp;
// gd_total_excise = grand_total_excise;
// gd_total_vat = grand_total_vat;
// total_dispatch_amount = document.getElementById('total_amount').value;

// var consignment = JSON.stringify(consignment_receive);
// document.cookie='received_consignment='+consignment;

// }


const deleteItem = (itemId) =>{
	consignment_receive = consignment_receive.filter(brand=>{
		return brand.product_id != itemId;
	});
	//console.log(consignment_receive);
	showDispatchProductGrid1();
}



function updateTotalReceivingAmount(obj){

var gd_total_wsp_id = document.getElementById('total_wsp');
var gd_total_excise_id = document.getElementById('total_excise');
var gd_total_custom_id = document.getElementById('total_custom');
var gd_total_vat_id = document.getElementById('total_vat');
var gd_total_amount_id = document.getElementById('total_amount');
var gd_other_charges_id = document.getElementById('other_charges');


var final_amount = parseFloat(gd_total_wsp_id.value) + parseFloat(gd_total_excise_id.value) + parseFloat(gd_total_custom_id.value) 
+ parseFloat(gd_total_vat_id.value) + parseFloat(gd_other_charges_id.value);
gd_total_amount_id.value = final_amount.toFixed(2);
}

const updateDispatchAmount = (obj)=>{
	var discount = obj.value;
	// console.log(discount)
	var total_amount = document.getElementById('total_amount');
	var final_amount=0;
	if(total_dispatch_amount > discount){
	 final_amount = total_dispatch_amount - discount;
	 // console.log('Here1')
	}
	else{
		final_amount = total_dispatch_amount;
	}

	total_amount.value = final_amount;

	// console.log(final_amount);
}

function submitTp11(){

var bnd_id = document.getElementById('bnd_id');
var invoice_id = document.getElementById('invoice_id');
var party_invoice_id = document.getElementById('party_invoice_id');
var receive_date = document.getElementById('receive_date');
var dispatch_date = document.getElementById('total_custom');
var slno = document.getElementById('slno');
var consig_num = document.getElementById('consig_num');
var supplier_id = document.getElementById('supplier_id');
var total_quantity = document.getElementById('total_quantity');
var gd_total_wsp_id = document.getElementById('total_wsp');
var gd_total_excise_id = document.getElementById('total_excise');
var gd_total_custom_id = document.getElementById('total_custom');
var gd_total_vat_id = document.getElementById('total_vat');
var gd_total_amount_id = document.getElementById('total_amount');
var gd_other_charges_id = document.getElementById('other_charges');
var invoice_doc = document.getElementById('invoice_doc');

var bill_wsp = document.getElementById('bill_wsp');
var bill_excise = document.getElementById('bill_excise');
var bill_vat = document.getElementById('bill_vat');
var bill_amount = document.getElementById('bill_amount');


if(consig_num.value.length < 15){
  alert("Please Enter Valid Consignment Number");
  consig_num.focus();
}
else if(supplier_id.value == ''){
  alert("Please select Supplier");
  supplier_id.focus();
}
else if(total_quantity.value == '' || total_quantity.value == 0 ){
  alert("Total Quantity can't be ZERO!! Please add some items");
  bnd_id.focus();
  }
else if(gd_total_wsp_id.value == '' || gd_total_wsp_id.value == 0 ){
  alert("Total WSP can't be ZERO!!");
  gd_total_wsp_id.focus();
  gd_total_wsp_id.value = gd_total_wsp;
}
else if(gd_total_excise_id.value == ''){
    alert("Total Excise can't be blank!!");
    gd_total_excise_id.focus();
    gd_total_excise_id.value = gd_total_excise;
}
else if(gd_total_vat_id.value == ''){
    alert("Total Excise can't be blank!!");
    gd_total_vat_id.focus();
    gd_total_vat_id.value = gd_total_vat;
}
else if(invoice_doc.value==''){
	alert('Please Upload Invoice');
	invoice_doc.focus();
	return false;
}
else if(bill_wsp.value==0 || bill_wsp.value==''){
	alert('Please Enter Bill WSP')
	bill_wsp.focus();
	return false
}
else if(bill_excise.value==0 || bill_excise.value==''){
	alert('Please Enter Bill Excise Duty')
	bill_excise.focus();
	return false
}
else if(bill_vat.value==0 || bill_vat.value==''){
	alert('Please Enter Bill VAT Amount')
	bill_vat.focus();
	return false
}
else if(bill_amount.value==0 || bill_amount.value==''){
	alert('Please Enter Bill Total Amount')
	bill_amount.focus();
	return false
}
else{
	
	var url ='submitTp.php';
	var form = document.querySelector('form');
	var data = new FormData(form);

	// console.log(data.party_invoice_id);
	// return false;

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	//console.log(xmlhttp.responseText);
	if(xmlhttp.responseText == consignment_receive.length){
		//document.cookie='received_consignment=';
		alert("TP added Successfully!!");
		window.location.reload();
	}
	else{
		alert("This TP Number already Exist!!");
		// window.location.reload();
		console.log(this.responseText)
	}
	}
	};
	xmlhttp.open("POST",url,true);
	xmlhttp.send(data);

}

}



const submitDispatch = ()=>{
// console.log('HERE')
// return false;
var bnd_id = document.getElementById('bnd_id');
var invoice_id = document.getElementById('invoice_id');
// var receive_date = document.getElementById('receive_date');
var dispatch_date = document.getElementById('total_custom');
var slno = document.getElementById('slno');
var vender = document.getElementById('vender');
var customer_id = document.getElementById('customer_id');
var consignment_id = document.getElementById('consignment_id');

var stationery_code = document.getElementById('stationery_code');
var stationery_num = document.getElementById('stationery_num');

var total_quantity = document.getElementById('total_quantity');
var gd_total_wsp_id = document.getElementById('total_wsp');
var gd_total_excise_id = document.getElementById('total_excise');
var gd_total_custom_id = document.getElementById('total_custom');
var gd_total_vat_id = document.getElementById('total_vat');
var gd_total_amount_id = document.getElementById('total_amount');

var discount_id = document.getElementById('discount');

if(total_quantity.value == '' || total_quantity.value == 0 ){
	alert("Total Quantity can't be ZERO!! Please add some items");
  
  bnd_id.focus();
  return false;
  }

  if(((consignment_id.value).trim()).length != 15){
  	alert('Please Enter Valid TP Number');
  	consignment_id.focus();
  	return false;
  }

  
   if(customer_id.value==''){
  	alert("Please select Customer");
  	customer_id.focus();
  	return false;
  }

	
	var url ='accounts_query.php';
	var form = document.querySelector('form');
	var data = new FormData(form);

	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
	if (this.readyState == 4 && this.status == 200) {
	console.log(xmlhttp.responseText);
	if(xmlhttp.responseText == consignment_receive.length){
		//document.cookie='received_consignment=';
		alert("Dispach details added Successfully!!");
		window.location.reload();
	}
	else{
		// alert("This Dispach details already Exist!!");
		// window.location.reload();
		console.log(this.responseText)
	}
	}
	};
	xmlhttp.open("POST",url,true);
	xmlhttp.send(data);



}

const returnTP111 = () => {
	var tpNumber = document.getElementById('tpId');
	if(tpNumber.value.length < 3){
		alert("Please Enter proper TP Number");
		tpNumber.focus();
	}
	else if(confirm('Are you sure!! You want to return TP Number '+tpNumber.value+'?')){
	var url = 'submitTp.php?return_tpNumber='+tpNumber.value;
	// console.log(url);
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {

	if (this.readyState == 4 && this.status == 200) {
    // console.log(this.responseText);
    // return false;
	if(xmlhttp.responseText.trim() == "Returned"){
		tpNumber.value = '';
		tpNumber.focus();
		alert("TP Returned Successfully!!");
		window.location.reload();
	}
	else if(xmlhttp.responseText.trim() == "Not Returned"){
		alert("Could Not Return TP!!");

		tpNumber.focus();
	}
	}
	};
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}
}




const deleteTP = () => {
	var tpNumber = document.getElementById('tpId');
	if(tpNumber.value.length < 3){
		alert("Please Enter proper TP Number");
		tpNumber.focus();
	}
	else if(confirm('Are you sure!! You want to delete TP Number '+tpNumber.value+'?')){
	var url = 'submitTp.php?tpNumber='+tpNumber.value;
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {

	if (this.readyState == 4 && this.status == 200) {
	
	if(xmlhttp.responseText.trim() == "Deleted"){
		tpNumber.value = '';
		tpNumber.focus();
		alert("TP Deleted Successfully!!");
		window.location.reload();
	}
	else if(xmlhttp.responseText.trim() == "Not Deleted"){
		alert("Could Not Delete TP!! Please enter correct TP");
		tpNumber.focus();
	}
	}
	};
	xmlhttp.open("GET",url,true);
	xmlhttp.send();
}
}

