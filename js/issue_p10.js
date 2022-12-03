var current_sale_array=[];
var priceMasterData=[];
var uid=1000;
var current_sale_quantity=0;
var scanned_barcode=[];
var p10_licence_fee='';
var p10_pay_mode='Cash';
function getPriceMasterData(){
	// var i=1;
		priceMasterData=[];
		var url = 'issue_sale_query.php?fun=getPriceMasterData';
	 var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange =  function() {
	    if (this.readyState == 4 && this.status == 200) {
	     // console.log(this.responseText);
	     var tempArray = JSON.parse(this.responseText);
	      tempArray.map(row=>{
	     	 priceMasterData.push(row);
	     })
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
	  // i=i+10000;
}

function getPriceMasterDataDate(date){
	// var i=1;
		priceMasterData=[];
		// console.log(date);
		var url = 'issue_sale_query.php?fun=getPriceMasterDataDate&priceDate='+date;
	 var xhttp = new XMLHttpRequest();
	  xhttp.onreadystatechange =  function() {
	    if (this.readyState == 4 && this.status == 200) {
	     // console.log(this.responseText);
	     var tempArray = JSON.parse(this.responseText);
	      tempArray.map(row=>{
	     	 priceMasterData.push(row);
	     })
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
	  // i=i+10000;
}

document.addEventListener('keydown', function(event) {

if(event.keyCode == 13) {
     	var active = document.activeElement;
     	if(active.hasAttribute('contenteditable')){
     		event.preventDefault();
     	}
     }

})


const validateBarcode = (barCode) => {
	var validCode = [18,20,24,28];
	return validCode.includes((barCode.length));
	//console.log(typeof(barCode));
}

const readBarCode = (barcode) => {
	//var inputid= document.getElementById(id);
	
		//var gtin='';
		var sku='';
		if(barcode.length==28){
			sku = barcode.substring(2,16);
			//return [gtin,barcode];
		}
		else if(barcode.length ==24){
			sku = barcode.substring(0,14);
			//return [gtin,barcode];
		}
		else if(barcode.length==20){
			sku = barcode.substring(5,10);
			sku =Number(sku).toString();

			//return [sku, barcode];
		}
		else{
			sku = barcode.substring(3,8);
			sku =Number(sku).toString();
			//return [sku, barcode];
		}
		return sku;
	}


const showPriceMasterData = (id,val) => {
	var table_body=document.getElementById(id);
	var i=1;
	html='';
	var tempArray='';
	if(val.length >=3){
		tempArray=priceMasterData.filter(row=>row.BRAND_NAME.toLowerCase().includes(val.toLowerCase()))
	}
	else tempArray=priceMasterData;
	tempArray.map(row=>{
		if(i<101){
			html+='<tr style="cursor:pointer"><td>'+ i++ +'</td><td class="mid-text"><a href="#" onclick="appendToSaleList(event,this.id)" id="'+ row.SKU_FK +'" class="move">'+ row.BRAND_NAME +'</a></td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.RETAIL_PRICE +'</td><td>'+ row.CLOSING_BALANCE +'</td></tr>';
		}
	})

	table_body.innerHTML= html;
}
const showPriceMasterDataPre = (id,val) => {
	var table_body=document.getElementById(id);
	var i=1;
	html='';
	var tempArray='';
	if(val.length >=3){
		tempArray=priceMasterData.filter(row=>row.BRAND_NAME.toLowerCase().includes(val.toLowerCase()))
	}
	else tempArray=priceMasterData;
	tempArray.map(row=>{
		if(i<101){
			html+='<tr style="cursor:pointer"><td>'+ i++ +'</td><td class="mid-text"><a href="#" onclick="appendToSalePre(event,this.id)" id="'+ row.SKU_FK +'" class="move">'+ row.BRAND_NAME +'</a></td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.RETAIL_PRICE +'</td><td>'+ row.CLOSING_BALANCE +'</td></tr>';
		}
	})

	table_body.innerHTML= html;
}

async function appendToSaleList (event,id){
	event.preventDefault();
	// console.log(id);
	var found = current_sale_array.some(item=>item.SKU_FK==id);
	// console.log(found);
	if(found){
		alert("This item has already been added!!")
	}
	else{
	var data = priceMasterData.filter(row=>row.SKU_FK == id);
	// console.log(data);
	var quantity = 1;
	let ml = (data[0].SIZE_VALUE)*quantity;
	object = {
		UNIQUE_ID: ++uid,
		SKU_FK: data[0].SKU_FK,
		GTIN: data[0].GTIN_NO,
		BARCODE: data[0].GTIN_NO,
		RETAIL_DISCOUNT: '',
		EXCISE_PRICE: data[0].EXCISE_PRICE,
		WSP: data[0].WSP,
		PACK_SIZE: data[0].PACK_SIZE,
		QUANTITY: quantity,
		BRAND_NAME: data[0].BRAND_NAME,
		SIZE_VALUE: data[0].SIZE_VALUE,
		MRP: data[0].RETAIL_PRICE,
		CLOSING_BALANCE:data[0].CLOSING_BALANCE,
		RETAIL_PROFIT: data[0].RETAIL_PROFIT,
		CUSTOM_DUTY: data[0].CUSTOM_DUTY,	
		RETAIL_SELLING_PRICE:parseFloat(data[0].RETAIL_SELLING_PRICE)?parseFloat(data[0].RETAIL_SELLING_PRICE):data[0].RETAIL_PRICE,
		AMOUNT: parseFloat(data[0].RETAIL_SELLING_PRICE)?parseFloat(data[0].RETAIL_SELLING_PRICE)*quantity:data[0].RETAIL_PRICE*quantity

	}
	
	current_sale_array.push(object);
	// console.log(event.target)
	showCurrentSaleList();

	document.getElementById('id01').style.display='none';
	var getid = document.getElementById(id);
	// console.log(getid.parentNode);
	getid.childNodes[5].value = '';
	getid.childNodes[5].focus();
	// getid.childNodes[5].value = quantity;
}
}




  async function appendToSaleListByBarcode(barcode) {

	if(!validateBarcode(barcode)){
		alert("Invalid Barcode!!");
	}
else{
	
	if(checkBarcode(barcode) && !scanned_barcode.includes(barcode) ){
		//alert("Barcode already Scanned");
	current_sale_quantity=0;
	var sku_gtin = readBarCode(barcode);
	var data = '';
	var quantity = 1;
	var item_total_quantity=0;
	if(sku_gtin.length > 10){
		current_sale_array.map(row=>{
			current_sale_quantity += (row.SIZE_VALUE*row.QUANTITY);
			if(row.GTIN_NO=sku_gtin){
				item_total_quantity += row.QUANTITY;
			}
		})
		// console.log(item_total_quantity);
		data = priceMasterData.filter(row=>row.GTIN_NO == sku_gtin);
		// console.log(data[0].CLOSING_BALANCE);
		if(item_total_quantity+1>data[0].CLOSING_BALANCE){
			alert("You can't Sale More than Closing Balance");
			return false;
		}
		// else if((current_sale_quantity + row.SIZE_VALUE*row.QUANTITY)>9000){
		// 	alert()
		// }

			else{
		current_sale_quantity += (data[0].SIZE_VALUE)*quantity;
		}
	}
	else{
		data = priceMasterData.filter(row=>row.SKU_FK == sku_gtin);
	
		if(data.length > 0){
			current_sale_array.map(row=>{
			current_sale_quantity += (row.SIZE_VALUE*row.QUANTITY);
		})
		quantity= parseInt(data[0].PACK_SIZE);
		current_sale_quantity += (data[0].SIZE_VALUE)*quantity;
	}
	else{
		alert("No Data found. Please Add Brand!");
		return false;
	}
	}

	if(data.length > 0){
	object = {
		UNIQUE_ID: ++uid,
		SKU_FK: data[0].SKU_FK,
		GTIN: data[0].GTIN_NO,
		BARCODE: barcode,
		PACK_SIZE: data[0].PACK_SIZE,
		EXCISE_PRICE: data[0].EXCISE_PRICE,
		WSP: data[0].WSP,
		QUANTITY: quantity,
		BRAND_NAME: data[0].BRAND_NAME,
		SIZE_VALUE: data[0].SIZE_VALUE,
		MRP: data[0].RETAIL_PRICE,
		CLOSING_BALANCE:data[0].CLOSING_BALANCE,
		RETAIL_PROFIT: data[0].RETAIL_PROFIT,
		RETAIL_DISCOUNT: 0,
		CUSTOM_DUTY: data[0].CUSTOM_DUTY,
		RETAIL_SELLING_PRICE:parseFloat(data[0].RETAIL_SELLING_PRICE)?parseFloat(data[0].RETAIL_SELLING_PRICE):data[0].RETAIL_PRICE,
		AMOUNT: parseFloat(data[0].RETAIL_SELLING_PRICE)?parseFloat(data[0].RETAIL_SELLING_PRICE)*quantity:data[0].RETAIL_PRICE*quantity
	}
	if(current_sale_quantity > 9000){
		await submitCurrentSale();
	}
	current_sale_array.unshift(object);
	showCurrentSaleList();
	document.getElementById('id01').style.display='none';

}
else{
	alert("No data found!! Please add brand!!!");
}
}
else{
	alert("Barcode already scanned!!");
}
}
}

const deleteItemSaleList = (event,id) =>{
	event.preventDefault();
	current_sale_array = current_sale_array.filter(row=>{
		return row.UNIQUE_ID != id;
	});
	// current_sale_array = tempArray;
	//console.log(typeof(id));
	showCurrentSaleList();
}

const deleteItemSalePre = (event,id) =>{
	event.preventDefault();
	current_sale_array = current_sale_array.filter(row=>{
		return row.UNIQUE_ID != id;
	});
	// current_sale_array = tempArray;
	//console.log(typeof(id));
	showCurrentSalePre();
}

const deleteSaleList = () =>{
	//event.preventDefault();
	current_sale_array = [];
	// current_sale_array = tempArray;
	//console.log(typeof(id));
	showCurrentSaleList();
}


const showCurrentSaleList = () => {
	var saleList = document.getElementById('current_sale_grid');
	var html = '';
	var i= 1;
	var grandtotal=0;
	var totalQuantity=0;
	current_sale_array.map(row=>{
		html+='<tr id="'+ row.SKU_FK +'"><td>'+ i++ +'</td><td class="min-text">...</td><td class="mid-text">'+ row.BRAND_NAME +'</td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.MRP +'</td><td contenteditable="true" onblur="updateP10Discount(this.parentNode.id,this.innerText)">'+row.RETAIL_DISCOUNT+'</td><td>'+ row.RETAIL_SELLING_PRICE +'</td><td contenteditable="true" onblur="updateCurrentSaleList(this.parentNode.id,this.innerText)">'+ row.QUANTITY +'</td><td>'+ row.AMOUNT +'</td><td><a href="" onclick="deleteItemSaleList(event,this.id)" id="'+ row.UNIQUE_ID+'">X</a></td></tr>';
		grandtotal = grandtotal+parseFloat(row.AMOUNT);
		totalQuantity=totalQuantity+parseInt(row.QUANTITY);
	});
	if(i>1){
	html+='<tr><td colspan="7" style="text-align:right; padding-right:10px;"> <b>Total Amount</td><td>'+totalQuantity+'</td><td colspan=1 style="text-align:left; padding-left:28px;">'+grandtotal+'</td><td></td>'
	// html+='<tr><td colspan="8"><button class="w3-button w3-red" type="button" onclick="submitCurrentSale()">Submit</button> <button class="w3-button w3-red" type="button" onclick="deleteSaleList()">Cancel</button></td></tr>';
	// html+='<tr><td colspan="8" style="text-align:right; padding-right:10px;"> <b>P10/P10E License Fee</td><td contenteditable="true" id="p10_amt" onblur="updateP10Fee()">'+p10_licence_fee+'</td><td><select style="height:20px" onchange="updateP10Paymode(this.value)" name="p10_mode" class="w3-select w3-border"><option value="Cash">Cash</option><option value="Credit">Credit</option></select></td>'
	// html+='<tr><td colspan="8" style="text-align:right; padding-right:10px;"> <b>Grand Total</b></td><td>'+(grandtotal)+'</td><td></td>'
	}
	saleList.innerHTML = html;
	document.getElementById('sub_btn').disabled=false
}

function updateP10Paymode(mode){
	p10_pay_mode = mode;
	console.log(p10_pay_mode);
}
function updateP10Fee(){
	var p10 = document.getElementById('p10_amt');
	console.log()
	if(p10.innerText==''){
		p10_licence_fee=0;
		// return false
	}
	else if(isNaN(p10.innerText)){
		// alert('Please Enter P10 License Fee amount only');
		// p10.focus();
		p10.innerText=0;
		p10_licence_fee=0;
		// return false;
	}
	 else{
	 	p10_licence_fee = parseFloat(p10.innerText);
	 }
	
	showCurrentSaleList();
}

const updateCurrentSaleList = async (id,value) => {
		current_sale_quantity=0;
			current_sale_array.map(item=>{
				if(item.SKU_FK!=id){
					current_sale_quantity+= item.SIZE_VALUE*item.QUANTITY;
				}
			})
	await current_sale_array.map(item=>{
		// console.log(item.CLOSING_BALANCE,"Hello",value);
		if(item.SKU_FK == id){
			
			current_sale_quantity += item.SIZE_VALUE*value;
			item.QUANTITY = value;
			item.AMOUNT = item.RETAIL_SELLING_PRICE*value;
			showCurrentSaleList();
		}
			
	})	
}

const updateP10Discount =  (id,value) => {
		// current_sale_quantity=0;
			if(value==''){
				value=0;
			}
	 current_sale_array.map(item=>{
		// console.log(item.CLOSING_BALANCE,"Hello",value);
		if(item.SKU_FK == id){
			item.RETAIL_DISCOUNT = value;
			item.RETAIL_SELLING_PRICE = item.MRP - (item.MRP*value*0.01)
			item.AMOUNT = item.RETAIL_SELLING_PRICE*item.QUANTITY;
			showCurrentSaleList();		
		}
		
	})
	
}

const checkBarcode = (barcode) => {
	return !current_sale_array.some(row=>row.BARCODE==barcode)
}

const getscannedBarcode = () =>{
	var url = 'check_duplicate_barcode.php';
	// var responseArray=[];
	const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
  	// console.log(this.responseText);
     var responseArray = JSON.parse(this.responseText);
     responseArray.map(row=>{scanned_barcode.push(row.BARCODE)});
    }
  xhttp.open("GET", url, true);
  xhttp.send();
  // return status_c;
}

const submitCurrentSale = () =>{

	// var channel = document.querySelector('input[name=channel]:checked').value;
	// console.log(channel);
	if(!document.querySelector('input[name=channel]:checked')){
		alert('Please Select channel of Sale')
		return false;
	}
	if(current_sale_array.length < 1){
		alert("Please add brand to the sale queue!!");
		return false;
	}
	
		var payment_mode = document.getElementById('payment_mode');
		var customer_name = document.getElementById('customer_name');
		var customer_mobile = document.getElementById('customer_mobile');
		var channel = document.querySelector('input[name=channel]:checked').value;

		
		// if(payment_mode.value==''){
		// 	alert('Please Select Payment Mode');
		// 	return false;
		// }
		// if(customer_name.value==''){
		// 	alert("Please Enter Customer Name");
		// 	customer_name.focus();
		// 	return false
		// }
		// if(customer_mobile.value==''){
		// 	alert("Please Enter Customer Contact Number");
		// 	customer_mobile.focus();
		// 	return false
		// }
		arraydata = JSON.stringify(current_sale_array);
		//console.log(data);
		document.getElementById('loading').style.display='block';
		var message = document.getElementById("message");
		return new Promise ((resolve,reject)=>{

			var xhttp = new XMLHttpRequest();
		  xhttp.onreadystatechange = function() {
		    if (this.readyState == 4 && this.status == 200) {
		     //document.getElementById("demo").innerHTML = this.responseText;
		     if(this.responseText.includes('All Data Submitted Successfully!!!')){
		     	
		     	message.style.display='inline-block';
		     	message.innerHTML=this.responseText;
		     	current_sale_array=[];
		     	current_sale_quantity=0;
		     	customer_name.value='';
		     	customer_mobile.value='';
		     	// showCurrentSaleList();
		     	var div_btn = document.getElementById('btn-click');
		     	var buttons_click = div_btn.querySelectorAll('button.w3-button');
		     	buttons_click.forEach(btn=>{
				btn.innerText=btn.value
				});
				document.getElementById('payment_mode').value='';
		     	resolve(this.responseText);
		     	getPriceMasterData()
		     	showCurrentSaleList();
				// getscannedBarcode();
				document.getElementById('loading').style.display='none';
		     }
		     else{
		     	//swal("Something went Wrong!");
		     	// window.location='current_sale.php?status=error';
		     	alert(this.responseText);
		     	document.getElementById('loading').style.display='none';
		     }
		    }
		  };
		  xhttp.open("POST", "issue_sale_query.php?payment_mode="+payment_mode.value+'&customer_name='+customer_name.value+'&customer_mobile='+customer_mobile.value+'&p10_fee='+p10_licence_fee+'&p10_mode='+p10_pay_mode+'&channel='+channel, true);
		  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		  xhttp.send("p10_sale_data="+encodeURIComponent(arraydata));

		});
		  
	
}


var sales_array_count=0;
var inv_lenth=0;


const printInvoice = (invoice) => {
	// console.log('hello')
	var url = 'issue_sale_query.php?fun=printInvoice&invoice='+invoice;
	// console.log(url);
	// return false
	const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
  	// var response = JSON.stringify(this.responseText)
  	// sessionStorage.setItem('print_data',this.responseText);
  	// document.cookie = "print_data="+this.responseText;
  	// window.open("print_invoice.php",'_blank');
  	// console.log(this.responseText);
   document.getElementById('sale_data').value=(this.responseText);
   document.querySelector('form').submit();
    }
  xhttp.open("GET", url, true);
  xhttp.send();
}



