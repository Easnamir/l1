var current_sale_array=[];
var priceMasterData=[];
var uid=1000;
var current_sale_quantity=0;
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

async function appendToSaleList (event,id) {
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
		RETAIL_DISCOUNT: parseFloat(data[0].RETAIL_PRICE-data[0].RETAIL_SELLING_PRICE),
		EXCISE_PRICE: data[0].EXCISE_PRICE,
		WSP: data[0].WSP,
		PACK_SIZE: data[0].PACK_SIZE,
		QUANTITY: quantity,
		BRAND_NAME: data[0].BRAND_NAME,
		SIZE_VALUE: data[0].SIZE_VALUE,
		MRP: data[0].RETAIL_PRICE,
		CLOSING_BALANCE:data[0].CLOSING_BALANCE,
		RETAIL_PROFIT: data[0].RETAIL_PROFIT,
				
		RETAIL_SELLING_PRICE:parseFloat(data[0].RETAIL_SELLING_PRICE)?parseFloat(data[0].RETAIL_SELLING_PRICE):data[0].RETAIL_PRICE,
		AMOUNT: parseFloat(data[0].RETAIL_SELLING_PRICE)?parseFloat(data[0].RETAIL_SELLING_PRICE)*quantity:data[0].RETAIL_PRICE*quantity

	}
	if(current_sale_quantity+ml > 9000){
		await submitCurrentSale();
	}
	current_sale_array.unshift(object);
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


async function appendToSalePre (event,id) {
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
		RETAIL_DISCOUNT: parseFloat(data[0].RETAIL_PRICE-data[0].RETAIL_SELLING_PRICE),
		EXCISE_PRICE: data[0].EXCISE_PRICE,
		WSP: data[0].WSP,
		PACK_SIZE: data[0].PACK_SIZE,
		QUANTITY: quantity,
		BRAND_NAME: data[0].BRAND_NAME,
		SIZE_VALUE: data[0].SIZE_VALUE,
		MRP: data[0].RETAIL_PRICE,
		CLOSING_BALANCE:data[0].CLOSING_BALANCE,
		RETAIL_PROFIT: data[0].RETAIL_PROFIT,		
		RETAIL_SELLING_PRICE:parseFloat(data[0].RETAIL_SELLING_PRICE)?parseFloat(data[0].RETAIL_SELLING_PRICE):data[0].RETAIL_PRICE,
		AMOUNT: parseFloat(data[0].RETAIL_SELLING_PRICE)?parseFloat(data[0].RETAIL_SELLING_PRICE)*quantity:data[0].RETAIL_PRICE*quantity

	}
	
	current_sale_array.unshift(object);
	// console.log(event.target)
	showCurrentSalePre();

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
	if(checkBarcode(barcode)){
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
		RETAIL_DISCOUNT: (data[0].RETAIL_PRICE-data[0].RETAIL_SELLING_PRICE),
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
		html+='<tr id="'+ row.SKU_FK +'"><td>'+ i++ +'</td><td class="min-text">...</td><td class="mid-text">'+ row.BRAND_NAME +'</td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.RETAIL_SELLING_PRICE +'</td><td>'+ row.MRP +'</td><td contenteditable="false" onblur="updateCurrentSaleList(this.parentNode.id,this.innerText)">'+ row.QUANTITY +'</td><td>'+ row.AMOUNT +'</td><td><a href="" onclick="deleteItemSaleList(event,this.id)" id="'+ row.UNIQUE_ID+'">X</a></td></tr>';
		grandtotal = grandtotal+parseFloat(row.AMOUNT);
		totalQuantity=totalQuantity+parseInt(row.QUANTITY);
	});
	if(i>1)
	html+='<tr><td colspan="6" style="text-align:right; padding-right:10px;"> <b>Total Amount</td><td>'+totalQuantity+'</td><td colspan=2 style="text-align:left; padding-left:28px;">'+grandtotal+'</td>'
	// html+='<tr><td colspan="8"><button class="w3-button w3-red" type="button" onclick="submitCurrentSale()">Submit</button> <button class="w3-button w3-red" type="button" onclick="deleteSaleList()">Cancel</button></td></tr>';
	saleList.innerHTML = html;
	document.getElementById('sub_btn').disabled=false
}


const showCurrentSalePre = () => {
	var saleList = document.getElementById('current_sale_grid');
	var html = '';
	var i= 1;
	var grandtotal=0;
	var totalQuantity=0;
	current_sale_array.map(row=>{
		html+='<tr id="'+ row.SKU_FK +'"><td>'+ i++ +'</td><td class="mid-text">'+ row.BRAND_NAME +'</td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.RETAIL_SELLING_PRICE +'</td><td>'+ row.MRP +'</td><td contenteditable="true" onblur="updateCurrentSaleList(this.parentNode.id,this.innerText)">'+ row.QUANTITY +'</td><td>'+ row.AMOUNT +'</td><td><a href="" onclick="deleteItemSalePre(event,this.id)" id="'+ row.UNIQUE_ID+'">X</a></td></tr>';
		grandtotal = grandtotal+parseFloat(row.AMOUNT);
		totalQuantity=totalQuantity+parseInt(row.QUANTITY);
	});
	if(i>1)
	html+='<tr><td colspan="6" style="text-align:right; padding-right:10px;"> <b>Total Amount</td><td>'+totalQuantity+'</td><td colspan=2 style="text-align:left; padding-left:28px;">'+grandtotal+'</td>'
	// html+='<tr><td colspan="8"><button class="w3-button w3-red" type="button" onclick="submitCurrentSale()">Submit</button> <button class="w3-button w3-red" type="button" onclick="deleteSaleList()">Cancel</button></td></tr>';
	saleList.innerHTML = html;
	document.getElementById('sub_btn').disabled=false
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
			if(item.CLOSING_BALANCE<value){
				alert("You can't Sale More than Counter Closing");
				value=1;
				item.QUANTITY = value;
				item.AMOUNT = item.MRP*value;
				showCurrentSalePre();
			}
			
			else{
			current_sale_quantity += item.SIZE_VALUE*value;
			item.QUANTITY = value;
			item.AMOUNT = item.MRP*value;
			showCurrentSalePre();
		}
		}
		
	})
	
}

const checkBarcode = (barcode) => {
	return !current_sale_array.some(row=>row.BARCODE==barcode)
}


const submitCurrentSale = () =>{
	if(current_sale_array.length < 1){
		alert("Please add brand to the sale queue!!");
		return false;
	}
	
		var payment_mode = document.getElementById('payment_mode');
		var customer_name = document.getElementById('customer_name');
		var customer_mobile = document.getElementById('customer_mobile');


		// var cr = confirm('Is the mode of payment - '+payment_mode.value);
		// if(cr!=true){
		// 	payment_mode.focus();
		// 	return false;
		// }
		if(payment_mode.value==''){
			alert('Please Select Payment Mode');
			return false;
		}
		arraydata = JSON.stringify(current_sale_array);
		//console.log(data);
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
		     }
		     else{
		     	//swal("Something went Wrong!");
		     	// window.location='current_sale.php?status=error';
		     	console.log(this.responseText);
		     }
		    }
		  };
		  xhttp.open("POST", "issue_sale_query.php?payment_mode="+payment_mode.value+'&customer_name='+customer_name.value+'&customer_mobile='+customer_mobile.value, true);
		  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		  xhttp.send("current_sale_data="+encodeURIComponent(arraydata));

		});
		  
	
}

const submitCurrentSalePre = () =>{
	if(current_sale_array.length < 1){
		alert("Please add brand to the sale queue!!");
		return false;
	}
		var sale_date = document.getElementById('sale_date');
		var payment_mode = document.getElementById('payment_mode');
		var customer_name = document.getElementById('customer_name');
		var customer_mobile = document.getElementById('customer_mobile');


		// var cr = confirm('Is the mode of payment - '+payment_mode.value);
		// if(cr!=true){
		// 	payment_mode.focus();
		// 	return false;
		// }
		if(payment_mode.value==''){
			alert('Please Select Payment Mode');
			return false;
		}
		arraydata = JSON.stringify(current_sale_array);
		//console.log(data);
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
		     	showCurrentSalePre();
		     }
		     else{
		     	//swal("Something went Wrong!");
		     	// window.location='current_sale.php?status=error';
		     	console.log(this.responseText);
		     }
		    }
		  };
		  xhttp.open("POST", "issue_sale_query.php?payment_mode="+payment_mode.value+'&customer_name='+customer_name.value+'&customer_mobile='+customer_mobile.value+'&sale_date='+sale_date.value, true);
		  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		  xhttp.send("current_sale_data="+encodeURIComponent(arraydata));

		});
		  
	
}

// ****************************----Issue Script Starts Here-------************************//




var min_date='';
var inventory_data_array = [];
var invertory_issue_list = [];
async function getInventoryManagementData(){
	var url='issue_sale_query.php?fun=getInventoryManagementData';
	var xhttp = new XMLHttpRequest();
	var batch = document.getElementById('last_batch');
	// console.log(batch.value);
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     // console.log(this.responseText);
     var tempArray = JSON.parse(this.responseText);
     tempArray.map(row=>{
     	inventory_data_array.push(row);
     });
     //console.log(JSON.stringify(inventory_data_array[0].INV_DATE.date))
     if(inventory_data_array.length > 0){
     var date = new Date(inventory_data_array[0].INV_DATE);
	  var day = date.getDate();
	  if(day<10){
	  	day = '0'+day;
	  }
	  var month = date.getMonth();
	  if(month<9){
	  	month = '0'+(month+1);
	  }
	  else{
	  	month = month+1;
	  }
	  var year = date.getFullYear();
     batch.innerHTML = 'Last Batch Run: '+day+'/'+(month)+'/'+year;
     min_date = year+'-'+(month)+'-'+day;
    }
    else{batch.style.display="none";}
}
  };
  xhttp.open("GET", url, true);
  xhttp.send();
}

// const getLastRunBatchDate = () =>{
// 	var url='issue_sale_query.php?fun=getLastRunBatchDate';
// 	var xhttp = new XMLHttpRequest();
// 	 xhttp.onreadystatechange = function() {
//     if (this.readyState == 4 && this.status == 200) {
//     	console.log(this.responseText);
//     }
// }
//     xhttp.open("GET", url, true);
// 	xhttp.send();
// }




// const showInventoryManagementData = () =>{

// 	inventory_data_array.map(row=>{

// 	})
// }


const showInventoryManagementData = (id,val) => {
	var table_body=document.getElementById(id);
	var i=1;
	html='';
	var tempArray='';
	if(val.length >=3){
		tempArray=inventory_data_array.filter(row=>row.BRAND_NAME.toLowerCase().includes(val.toLowerCase()))
	}
	else tempArray=inventory_data_array;
	tempArray.map(row=>{
		
			html+='<tr style="cursor:pointer"><td>'+ i++ +'</td><td class="mid-text"><a href="#" onclick="appendToIssueList(event,this.id)" id="'+ row.SKU_FK +'" class="move">'+ row.BRAND_NAME +'</a></td><td>'+ row.SIZE_VALUE +'</td><td>'+ (row.STORE_CLOSING)/(row.PACK_SIZE) +'</td><td>'+ row.CLOSING_BALANCE +'</td></tr>';
		
	})

	table_body.innerHTML= html;
}


const appendToIssueList = (event,id) => {
	event.preventDefault();
	const found = invertory_issue_list.some(row=>row.SKU_FK==id);
	if(found){
		alert("Already added to the List!!");
		return false;
	}
	var data = inventory_data_array.filter(row=>row.SKU_FK == id);
	// console.log(data);
	var issue = 0;
	object = {
		SKU_FK: data[0].SKU_FK,
		GTIN: data[0].GTIN,
		ISSUE_STOCK: issue,
		//INV_DATE: data[0].INV_DATE,
		STORE_OPENING: data[0].STORE_CLOSING,
		STORE_CLOSING: data[0].STORE_CLOSING,
		BRAND_NAME: data[0].BRAND_NAME,
		SIZE_VALUE: data[0].SIZE_VALUE,
		PACK_SIZE: data[0].PACK_SIZE
		}
	invertory_issue_list.unshift(object);
	showCurrentIssueList();
	document.getElementById('id02').style.display='none';
	var getid = document.getElementById(id);
	//console.log(getid.parentNode.parentNode.childNodes[4]);
	getid.childNodes[4].focus();
	getid.childNodes[4].innerHTML='';
}
const showCurrentIssueListBarcode = () => {
	var issueList = document.getElementById('issue_inventory_grid');
	var html = '';
	var i= 1;
	// var grandtotal=0;
	// var totalQuantity=0;
	invertory_issue_list.map(row=>{
		html+='<tr id="'+ row.UID+'"><td>'+ i++ +'</td><td>'+ (row.BARCODE?row.BARCODE:"") +'</td><td class="mid-text">'+ row.BRAND_NAME +'</td><td>'+ row.SIZE_VALUE +'</td><td>'+ (row.STORE_OPENING)/(row.PACK_SIZE) +'</td><td contenteditable="true" onblur="updateIssueInventoryList(this.parentNode.id)">'+ (row.ISSUE_STOCK)/(row.PACK_SIZE) +'</td><td>'+ (row.STORE_CLOSING)/(row.PACK_SIZE) +'</td><td><a href="" onclick="deleteIssueItemList(event,this.parentNode.parentNode.id)">X</a></td></tr>';
		// grandtotal = grandtotal+parseInt(row.AMOUNT);
		// totalQuantity=totalQuantity+parseInt(row.QUANTITY);
	});

	//html+='<tr><td colspan="5" style="text-align:right; padding-right:10px;"> <b>Total Amount</td><td>'+totalQuantity+'</td><td colspan=2 style="text-align:left; padding-left:28px;">'+grandtotal+'</td>'
	// var button ='';
	issueList.innerHTML = html;
	// document.getElementById('buttons_issue').innerHTML=button;
}
const showCurrentIssueList = () => {
	var issueList = document.getElementById('issue_inventory_grid');
	var html = '';
	var i= 1;
	// var grandtotal=0;
	// var totalQuantity=0;
	invertory_issue_list.map(row=>{
		html+='<tr id="'+ row.SKU_FK+'"><td>'+ i++ +'</td><td class="mid-text">'+ row.BRAND_NAME +'</td><td>'+ row.SIZE_VALUE +'</td><td>'+ (row.STORE_OPENING)/(row.PACK_SIZE) +'</td><td contenteditable="true" onblur="updateIssueInventoryList(this.parentNode.id)">'+ (row.ISSUE_STOCK)/(row.PACK_SIZE) +'</td><td>'+ (row.STORE_CLOSING)/(row.PACK_SIZE) +'</td><td><a href="" onclick="deleteIssueItemList(event,this.parentNode.parentNode.id)">X</a></td></tr>';
		// grandtotal = grandtotal+parseInt(row.AMOUNT);
		// totalQuantity=totalQuantity+parseInt(row.QUANTITY);
	});

	//html+='<tr><td colspan="5" style="text-align:right; padding-right:10px;"> <b>Total Amount</td><td>'+totalQuantity+'</td><td colspan=2 style="text-align:left; padding-left:28px;">'+grandtotal+'</td>'
	// var button ='';
	issueList.innerHTML = html;
	// document.getElementById('buttons_issue').innerHTML=button;
}
const updateIssueInventoryList =(id)=>{
	//console.log(id);
	//var error = false;
	var tr = document.getElementById(id);
	var cells = tr.getElementsByTagName('td');
	var store_opening = NaNtoBlank(cells[3].innerHTML);
	//var counter_opening = NaNtoBlank(cells[5].innerHTML);
	//var receiving = NaNtoBlank(cells[6].innerHTML);
	var issue_stock = NaNtoBlank(cells[4].innerHTML);
	var store_closing = 0;
	if(store_opening<issue_stock){
		alert("Issue Value cannot be more that Store Opening");
		cells[4].innerHTML=0;
		cells[4].focus();
	}
	else{
		store_closing = store_opening - issue_stock;
		cells[4].innerHTML= issue_stock;
		cells[5].innerHTML= store_closing;

		invertory_issue_list.map(row=>{
			if(row.SKU_FK==id){
				row.ISSUE_STOCK=issue_stock*row.PACK_SIZE;
				row.STORE_CLOSING=store_closing*row.PACK_SIZE;
			}
		});
	}
}

const deleteIssueItemList=(event,id)=>{
	event.preventDefault();
	invertory_issue_list = invertory_issue_list.filter(row=>{
		return row.SKU_FK != id;
	});
	showCurrentIssueList();
}

const deleteIssueList=()=>{
	//event.preventDefault();
	invertory_issue_list = [];
	
	showCurrentIssueList();
}

var issue_data_count=0;
const submitIssueItems = () =>{
	if(invertory_issue_list.length < 1){
		alert("Please Add Items to the Issue List!!");
	}
	else{
    document.getElementById('loading').style.display='block';
		var date = document.getElementById('issue_date').value;
		var invoice = document.getElementById('invoice_id').value;
		// document.getElementById('submit_issue111').disabled=true;
		//console.log(data);
	



			let promises = [];
			invertory_issue_list.map(row=>{
			promises.push(insertIssueData(date,invoice,row));
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
			console.log(this.responseText);
		})	
		
}
}



const insertIssueData = (date,invoice,row) => {
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
		 else{
		 	console.log(this.responseText);
		 }
		}
	  };
	  xhttp.open("POST", "issue_sale_query.php", true);
		  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		  xhttp.send("issue_data="+encodeURIComponent(arraydata)+"&date="+date+"&invoice="+invoice+"&SKU_FK="+sku_fk);
	// console.log(count);
});
}

const submitIssueItemsBarcode = () =>{
	if(invertory_issue_list.length < 1){
		alert("Please Add Items to the Issue List!!");
	}
	else{
    document.getElementById('loading').style.display='block';
		var date = document.getElementById('issue_date').value;
		var invoice = document.getElementById('invoice_id').value;
		// document.getElementById('submit_issue111').disabled=true;
		//console.log(data);
	



			let promises = [];
			invertory_issue_list.map(row=>{
				// console.log(row);
			promises.push(insertIssueData(date,invoice,row));
			});	
			// console.log(promises);
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
		// console.log(promises);
}
}



const insertIssueDataBarcode = (date,invoice,row) => {
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
		 else{
		 	reject("Something went Wrong");
		 }	
		}
	  };
	  xhttp.open("POST", "issue_sale_query.php", true);
		  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		  xhttp.send("issue_data="+encodeURIComponent(arraydata)+"&fun=insertIssueDataBarcode&date="+date+"&invoice="+invoice+"&SKU_FK="+sku_fk);
	// console.log(count);
});
}
const getInvoiceData = (invoice) => {
	var url = 'issue_sale_query.php?fun=getInvoiceData&invoice='+invoice;
	invertory_issue_list=[];
	var issue_date = document.getElementById('issue_date');
		var xhttp = new XMLHttpRequest();
	  	xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     // console.log(this.responseText);
	     var tempArray = JSON.parse(this.responseText);
	     tempArray.map(row=>{
	     	invertory_issue_list.push(row);
	     })
	     if(invertory_issue_list.length>0){
	     showCurrentIssueList();
	     // document.getElementById('submit_issue').disabled=true
	     var date = new Date(invertory_issue_list[0].CREATED_DATE.date);
	     var day = date.getDate();
	     if(day <10){
	     	day = '0'+day;
	     }
		 var month = date.getMonth();
		 if(month<9){
		 	month='0'+(month+1)
		 }
		 else{
		 	month = month+1;
		 }
		 var year = date.getFullYear();

		 issue_date.value = year+'-'+(month)+'-'+day;
		 // issue_date.min = min_date;
		}
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
}

/* -- ************************************ SCRIPT ISSUE PAGE ENDS ************************** */


/* -- ************************************ SCRIPT SALE PAGE BEGINS ************************** */



var min_date='';
var inventory_data_array_sale = [];
var inventory_data_array_damage = [];
var invertory_sale_list = [];
var invertory_damage_list = [];
var breakage_array = [];

 function getInventoryManagementDataForSale () {
	var url='issue_sale_query.php?fun=getInventoryManagementDataForSale';
	var xhttp = new XMLHttpRequest();
	var batch = document.getElementById('last_batch');
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    // console.log(this.responseText);
     var tempArray = JSON.parse(this.responseText);
     tempArray.map(row=>{
     	inventory_data_array_sale.push(row);
     });
     //console.log(JSON.stringify(inventory_data_array_sale[0].INV_DATE.date))
     if(inventory_data_array_sale.length>0){
    var date = new Date(inventory_data_array_sale[0].INV_DATE);
	  var day = date.getDate();
	  if(day<10){
	  	day = '0'+day;
	  }
	  var month = date.getMonth();
	  if(month<9){
	  	month = '0'+(month+1);
	  }
	  else{
	  	month = month+1;
	  }
	  var year = date.getFullYear();
     batch.innerHTML = 'Last Batch Run: '+day+'/'+(month)+'/'+year;
     min_date = year+'-'+(month)+'-'+day;
    }
    else{batch.style.display="none";}
}
  };
  xhttp.open("GET", url, true);
  xhttp.send();
}



const getBreakage = async () => {
  var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     // console.log(this.responseText);
     var tempArray = JSON.parse(this.responseText);
     tempArray.map(row=>{
     	breakage_array.push(row);
     });
    }
  };
  xhttp.open("GET", "issue_sale_query.php?fun=getBreakage", true);
  xhttp.send();
  return 1;
}




 function getInventoryManagementDataForDamage () {
	var url='issue_sale_query.php?fun=getInventoryManagementDataForDamage';
	var xhttp = new XMLHttpRequest();
	var batch = document.getElementById('last_batch');
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    // console.log(JSON.parse(this.responseText));
     var tempArray = JSON.parse(this.responseText);
     tempArray.map(row=>{
     	inventory_data_array_damage.push(row);
     });
     //console.log(JSON.stringify(inventory_data_array_sale[0].INV_DATE.date))
     if(inventory_data_array_damage.length>0){
    var date = new Date(inventory_data_array_damage[0].INV_DATE);
	  var day = date.getDate();
	  if(day<10){
	  	day = '0'+day;
	  }
	  var month = date.getMonth();
	  if(month<9){
	  	month = '0'+(month+1);
	  }
	  else{
	  	month = month+1;
	  }
	  var year = date.getFullYear();
     batch.innerHTML = 'Last Batch Run: '+day+'/'+(month)+'/'+year;
     min_date = year+'-'+(month)+'-'+day;
    }
    else{batch.style.display="none";}
}
  };
  xhttp.open("GET", url, true);
  xhttp.send();
}


const showInventoryManagementDataForSale = (id,val) => {
	//getInventoryManagementDataForSale();
	var table_body=document.getElementById(id);
	var i=1;
	html='';
	var tempArray='';
	if(val.length >=3){
		tempArray=inventory_data_array_sale.filter(row=>row.BRAND_NAME.toLowerCase().includes(val.toLowerCase()))
	}
	else tempArray=inventory_data_array_sale;
	tempArray.map(row=>{
			// let match = breakage_array.filter(data=>data.SKU_FK == row.SKU_FK);
			// console.log(match[0].BREAKAGE);
			// let breakage = match[0].BREAKAGE;
			breakage_array.map(data =>{if(data.SKU_FK == row.SKU_FK) row.bkj=data.BREAKAGE} )
			html+='<tr style="cursor:pointer"><td>'+ i++ +'</td><td class="mid-text"><a href="#" onclick="appendToSalesList(event,this.id)" id="'+ row.SKU_FK +'" class="move">'+ row.BRAND_NAME +'</a></td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.STORE_CLOSING +'</td><td>'+ row.COUNTER_CLOSING +'</td><td>'+ row.bkj +'</td><td>'+ row.CLOSING_BALANCE +'</td></tr>';
		
	})

	table_body.innerHTML= html;
}
const showInventoryManagementDataForDamage = (id,val) => {
	//getInventoryManagementDataForSale();
	var table_body=document.getElementById(id);
	var i=1;
	html='';
	var tempArray='';
	if(val.length >=3){
		tempArray=inventory_data_array_damage.filter(row=>row.BRAND_NAME.toLowerCase().includes(val.toLowerCase()))
	}
	else tempArray=inventory_data_array_damage;
	tempArray.map(row=>{
		
			html+='<tr style="cursor:pointer"><td>'+ i++ +'</td><td class="mid-text"><a href="#" onclick="appendToDamageList(event,this.id)" id="'+ row.SKU_FK +'" class="move">'+ row.BRAND_NAME +'</a></td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.COUNTER_CLOSING +'</td><td>'+ row.CLOSING_BALANCE +'</td></tr>';
		
	})

	table_body.innerHTML= html;
}


const appendToSalesList = (event,id) => {
	event.preventDefault();
	const found = invertory_sale_list.some(row=>row.SKU_FK==id);
	if(found){
		alert("Already added to the List!!");
		return false;
	}
	console.log(id);
	var data = inventory_data_array_sale.filter(row=>row.SKU_FK == id);
	// console.log(data);
	var SALE = 0;
	object = {
		SKU_FK: data[0].SKU_FK,
		GTIN: data[0].GTIN,
		QUANTITY: SALE,
		//INV_DATE: data[0].INV_DATE,
		COUNTER_OPENING: data[0].COUNTER_CLOSING,
		COUNTER_CLOSING: data[0].COUNTER_CLOSING,
		BRAND_NAME: data[0].BRAND_NAME,
		SIZE_VALUE: data[0].SIZE_VALUE,
		MRP: data[0].TOTAL_MRP_AMOUNT,
		RETAIL_DISCOUNT: data[0].RETAIL_SELLING_PRICE?(parseFloat(data[0].TOTAL_MRP_AMOUNT)-parseFloat(data[0].RETAIL_SELLING_PRICE)):0,
		RETAIL_PROFIT: data[0].RETAIL_PROFIT,
		RETAIL_SELLING_PRICE: (data[0].RETAIL_SELLING_PRICE)?parseFloat(data[0].RETAIL_SELLING_PRICE):parseFloat(data[0].TOTAL_MRP_AMOUNT),
		TOTAL_AMOUNT: 0
		}
	invertory_sale_list.unshift(object);
	showCurrentSalesList();
	document.getElementById('id02').style.display='none';
	var getid = document.getElementById(id);
	//console.log(getid.parentNode.parentNode.childNodes[4]);
	getid.childNodes[5].focus();
	getid.childNodes[5].innerHTML='';
	// if(getid.childNodes[5].innerHTML=='0' || getid.childNodes[5].innerHTML==''){
		
	// }
}


const appendToDamageList = (event,id) => {
	event.preventDefault();
	const found = invertory_damage_list.some(row=>row.SKU_FK==id);
	if(found){
		alert("Already added to the List!!");
		return false;
	}
	var data = inventory_data_array_damage.filter(row=>row.SKU_FK == id);
	// console.log(data);
	var SALE = 0;
	object = {
		SKU_FK: data[0].SKU_FK,
		GTIN: data[0].GTIN,
		QUANTITY: SALE,
		//INV_DATE: data[0].INV_DATE,
		COUNTER_OPENING: data[0].COUNTER_CLOSING,
		COUNTER_CLOSING: data[0].COUNTER_CLOSING,
		BRAND_NAME: data[0].BRAND_NAME,
		SIZE_VALUE: data[0].SIZE_VALUE,
		MRP: data[0].TOTAL_MRP_AMOUNT,
		TOTAL_AMOUNT: SALE*parseFloat(data[0].TOTAL_MRP_AMOUNT)
		}
	invertory_damage_list.unshift(object);
	showCurrentDamageList();
	document.getElementById('id02').style.display='none';
	var getid = document.getElementById(id);
	//console.log(getid.parentNode.parentNode.childNodes[4]);
	getid.childNodes[4].focus();
	getid.childNodes[4].innerHTML='';
	// if(getid.childNodes[5].innerHTML=='0' || getid.childNodes[5].innerHTML==''){
		
	// }
}

const showCurrentSalesList = () => {
	var saleList = document.getElementById('sale_inventory_grid');
	var html = '';
	var i= 1;
	var grandtotal=0;
	var totalQuantity=0;
	invertory_sale_list.map(row=>{
		html+='<tr id="'+ row.SKU_FK+'"><td>'+ i++ +'</td><td class="mid-text">'+ row.BRAND_NAME +'</td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.COUNTER_OPENING +'</td><td>'+ row.QUANTITY +'</td><td contenteditable="true" onblur="updateSaleInventoryList(this.parentNode.id)">'+ row.COUNTER_CLOSING +'</td><td>'+ (row.RETAIL_SELLING_PRICE) +'</td><td>'+ (row.TOTAL_AMOUNT) +'</td><td class="mid-text"><a href="" onclick="deleteSaleItemList(event,this.parentNode.parentNode.id)">X</a></td></tr>';
		grandtotal = grandtotal+parseFloat(row.TOTAL_AMOUNT);
		totalQuantity=totalQuantity+parseInt(row.QUANTITY);
	});

	html+='<tr><td colspan="4" style="text-align:right; padding-right:10px;"> <b>Total Quantity</td><td>'+totalQuantity+'</td><td colspan=2>Total Amount</td><td colspan=2>'+grandtotal.toFixed(2)+'</td>'
	// html+='<tr><td colspan="9"><button class="w3-button w3-red" id="submit_sale" type="button" onclick="submitSalesItems()">Submit</button> <button class="w3-button w3-red" type="button" onclick="deleteSalesList()">Cancel</button></td></tr>';
	saleList.innerHTML = html;
}

const showCurrentDamageList = () => {
	var saleList = document.getElementById('sale_inventory_grid');
	var html = '';
	var i= 1;
	var grandtotal=0;
	var totalQuantity=0;
	invertory_damage_list.map(row=>{
		html+='<tr id="'+ row.SKU_FK+'"><td>'+ i++ +'</td><td class="mid-text">'+ row.BRAND_NAME +'</td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.COUNTER_OPENING +'</td><td contenteditable="true" onblur="updateDamageInventoryList(this.parentNode.id)">'+ row.QUANTITY +'</td><td>'+ row.COUNTER_CLOSING +'</td><td>'+ row.MRP +'</td><td>'+ row.TOTAL_AMOUNT +'</td><td><a href="" onclick="deleteDamageItemList(event,this.parentNode.parentNode.id)">X</a></td></tr>';
		grandtotal = grandtotal+parseFloat(row.TOTAL_AMOUNT);
		totalQuantity=totalQuantity+parseInt(row.QUANTITY);
	});

	html+='<tr><td colspan="4" style="text-align:right; padding-right:10px;"> <b>Total Quantity</td><td>'+totalQuantity+'</td><td colspan=2>Total Amount</td><td colspan=2>'+grandtotal+'</td>'
	// html+='<tr><td colspan="9"><button class="w3-button w3-red" id="submit_sale" type="button" onclick="submitSalesItems()">Submit</button> <button class="w3-button w3-red" type="button" onclick="deleteSalesList()">Cancel</button></td></tr>';
	saleList.innerHTML = html;
}

const updateSaleInventoryList =(id)=>{
	//console.log(id);
	//var error = false;
	var tr = document.getElementById(id);
	var cells = tr.getElementsByTagName('td');
	var counter_opening = NaNtoBlank(cells[3].innerHTML);
	//var counter_opening = NaNtoBlank(cells[5].innerHTML);
	//var receiving = NaNtoBlank(cells[6].innerHTML);
	var counter_closing = cells[5].innerHTML;
	if(counter_closing==''){
		// sale=0;
		counter_closing=counter_opening;
	}
	else{
		counter_closing=NaNtoBlank(counter_closing)
	}
	var MRP = NaNtoBlank(cells[6].innerHTML);
	var sale = 0;
	if(counter_opening < counter_closing){
		alert("Counter Closing cannot be more that Counter Opening");
		cells[5].innerHTML='';
		cells[5].focus();
			
	}
	else{
		sale  = counter_opening - counter_closing;
		cells[4].innerHTML=  sale;
		// cells[5].innerHTML= counter_closing;
		cells[7].innerHTML= sale*MRP;

		invertory_sale_list.map(row=>{
			if(row.SKU_FK==id){
				row.QUANTITY= sale;
				row.COUNTER_CLOSING=counter_closing;
				row.TOTAL_AMOUNT = row.RETAIL_SELLING_PRICE?parseFloat(sale)*parseFloat(row.RETAIL_SELLING_PRICE):parseFloat(sale)*parseFloat(row.MRP);
			}
		});
		showCurrentSalesList();
	}
	
}

const updateDamageInventoryList =(id)=>{
	//console.log(id);
	//var error = false;
	var tr = document.getElementById(id);
	var cells = tr.getElementsByTagName('td');
	var counter_opening = NaNtoBlank(cells[3].innerHTML);
	//var counter_opening = NaNtoBlank(cells[5].innerHTML);
	//var receiving = NaNtoBlank(cells[6].innerHTML);
	var counter_closing = NaNtoBlank(cells[5].innerHTML);
	var damage = NaNtoBlank(cells[4].innerHTML);
	// if(counter_closing==''){
	// 	// sale=0;
	// 	counter_closing=counter_opening;
	// }
	// else{
	// 	counter_closing=NaNtoBlank(counter_closing)
	// }
	var MRP = NaNtoBlank(cells[6].innerHTML);
	var sale = 0;
	if(counter_opening < damage){
		alert("Damage cannot be more that Counter Opening");
		cells[4].innerHTML='';
		cells[4].focus();
			
	}
	else{
		counter_closing  = counter_opening - damage;
		// cells[4].innerHTML=  sale;
		cells[5].innerHTML= counter_closing;
		cells[7].innerHTML= damage*MRP;

		invertory_damage_list.map(row=>{
			if(row.SKU_FK==id){
				row.QUANTITY= damage;
				row.COUNTER_CLOSING=counter_closing;
				row.TOTAL_AMOUNT = parseInt(damage)*parseFloat(row.MRP);
			}
		});
		showCurrentDamageList();
	}
	
}

const deleteSaleItemList=(event,id)=>{
	event.preventDefault();
	invertory_sale_list = invertory_sale_list.filter(row=>{
		return row.SKU_FK != id;
	});
	showCurrentSalesList();
}

const deleteDamageItemList=(event,id)=>{
	event.preventDefault();
	invertory_damage_list = invertory_damage_list.filter(row=>{
		return row.SKU_FK != id;
	});
	showCurrentDamageList();
}

const deleteSalesList=()=>{
	//event.preventDefault();
	invertory_sale_list = [];
	
	showCurrentSalesList();
}

var sales_array_count=0;
var inv_lenth=0;


const submitSalesItems = () =>{
	 inv_lenth = invertory_sale_list.length;
	if(inv_lenth < 1){
		alert("Please Add Items to the Issue List!!");
	}
	else{
		sales_array_count=0;
		// arraydata = JSON.stringify(invertory_sale_list);
		var date = document.getElementById('sale_date').value;
		var invoice = document.getElementById('invoice_id').value;
		document.getElementById('submit_sale').disabled = true;
		//console.log(data);
		var c=10;
		// debugger;
		document.getElementById('loading').style.display='block';
		invertory_sale_list.map(async row=>{
			arraydata = JSON.stringify(row);
			// console.log(c);
			var sku_fk = row.SKU_FK;
		await insert_sales_data(arraydata,date,invoice,sku_fk);
		});
		setTimeout(function(){
			if(sales_array_count == inv_lenth){
			alert("All Data Submitted Successfully!!!");
			document.getElementById('loading').style.display='none';
			window.location.reload();
		}
		else{
			// window.location='sales.php?status=error&msg=Something Went Wrong!!';
			alert("error");
		}
		},5000)
		}
}

const submitDamageItems = () =>{
	 inv_lenth = invertory_damage_list.length;
	if(inv_lenth < 1){
		alert("Please Add Items to the Issue List!!");
	}
	else{
		sales_array_count=0;
		// arraydata = JSON.stringify(invertory_sale_list);
		var date = document.getElementById('sale_date').value;
		var invoice = document.getElementById('invoice_id').value;
		// document.getElementById('submit_sale').disabled = true;
		//console.log(data);
		var c=10;
		// debugger;
		invertory_damage_list.map(async row=>{
			arraydata = JSON.stringify(row);
			// console.log(c);
			var sku_fk = row.SKU_FK;
		var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			     //document.getElementById("demo").innerHTML = this.responseText;
			     //console.log(this.responseText);
			     if(this.responseText.includes('All Data Submitted Successfully')){
			     		
			     		sales_array_count++;
			     	 
			     }
			     else{
			     	console.log(this.responseText);
			     }
			    }
			  };
			  xhttp.open("POST", "issue_sale_query.php", true);
			  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			  xhttp.send("damage_data="+encodeURIComponent(arraydata)+"&date="+date+"&invoice="+invoice+"&SKU_FK="+sku_fk);

		});
		setTimeout(function(){
			if(sales_array_count == inv_lenth){
			alert("All Data Submitted Successfully!!!");
			window.location.reload();
		}
		else{
			// window.location='sales.php?status=error&msg=Something Went Wrong!!';
			alert("error");
		}
		},5000)
		}
		

		// if(invertory_sale_list.length == sales_array_count){
		// 	window.location='sales.php?status=success&msg=All Data Submitted Successfully!!!..Your Invoice No is: '+invoice;
		// }
		// else{
		// 	window.location='sales.php?status=error&msg=Something Went Wrong!!';
		// }
}

const insert_sales_data =(arraydata,date,invoice,sku_fk)=>{

			var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			     //document.getElementById("demo").innerHTML = this.responseText;
			     //console.log(this.responseText);
			     if(this.responseText.includes('All Data Submitted Successfully')){
			     		
			     		sales_array_count++;
			     	 
			     }
			     else{
			     	console.log(this.responseText);
			     }
			    }
			  };
			  xhttp.open("POST", "issue_sale_query.php", true);
			  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			  xhttp.send("sale_data="+encodeURIComponent(arraydata)+"&date="+date+"&invoice="+invoice+"&SKU_FK="+sku_fk);
	// console.log(arraydata);

}


 function getInvoiceDataForSale (invoice) {
	var url = 'issue_sale_query.php?fun=getInvoiceDataForSale&invoice='+invoice;
	invertory_sale_list=[];
	var sale_date = document.getElementById('sale_date');
		var xhttp = new XMLHttpRequest();
	  	xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     //console.log(this.responseText);
	     var tempArray = JSON.parse(this.responseText);
	     tempArray.map(row=>{
	     	invertory_sale_list.push(row);
	     });

	     if(invertory_sale_list.length>0){
	     showCurrentSalesList();
	     // document.getElementById('submit_sale').disabled=true;
	     var date = new Date(invertory_sale_list[0].CREATED_DATE.date);
	     // console.log(invertory_sale_list[0].CREATED_DATE.date)
	     var day = date.getDate();
	     if(day <10){
	     	day = '0'+day;
	     }
		 var month = date.getMonth();
		 if(month<9){
		 	month='0'+(month+1)
		 }
		 else{
		 	month = month+1;
		 }
		 var year = date.getFullYear();

		 sale_date.value = year+'-'+month+'-'+day;
		 // issue_date.min = min_date;
		}
		else{
			alert('No data found for this Invoice');
		}
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
}

 function getInvoiceDataForDamage (invoice) {
	var url = 'issue_sale_query.php?fun=getInvoiceDataForDamage&invoice='+invoice;
	invertory_damage_list=[];
	var sale_date = document.getElementById('sale_date');
		var xhttp = new XMLHttpRequest();
	  	xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     //console.log(this.responseText);
	     var tempArray = JSON.parse(this.responseText);
	     tempArray.map(row=>{
	     	invertory_damage_list.push(row);
	     });

	     if(invertory_damage_list.length>0){
	     showCurrentDamageList();
	     // document.getElementById('submit_sale').disabled=true;
	     var date = new Date(invertory_damage_list[0].CREATED_DATE.date);
	     var day = date.getDate();
	     if(day <10){
	     	day = '0'+day;
	     }
		 var month = date.getMonth();
		 if(month<9){
		 	month='0'+(month+1)
		 }
		  else{
		 	month = month+1;
		 }
		 var year = date.getFullYear();

		 sale_date.value = year+'-'+month+'-'+day;
		 // issue_date.min = min_date;
		}
		else{
			alert('No data found for this Invoice');
		}
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
}

const printInvoice = () => {
	// console.log('hello')
	var url = 'issue_sale_query.php?fun=printInvoice';
	const xhttp = new XMLHttpRequest();
  xhttp.onload = function() {
  	// var response = JSON.stringify(this.responseText)
  	// sessionStorage.setItem('print_data',this.responseText);
  	document.cookie = "print_data="+this.responseText;
  	window.open("print_invoice.php",'_blank');
  	
   // console.log(response);
    }
  xhttp.open("GET", url, true);
  xhttp.send();
}




//********************* Script for Breakage sale ***************************************//



 function getInventoryManagementDataForBreakage () {
	var url='issue_sale_query.php?fun=getInventoryManagementDataForBreakage';
	var xhttp = new XMLHttpRequest();
	var batch = document.getElementById('last_batch');
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
    // console.log(this.responseText);
     var tempArray = JSON.parse(this.responseText);
     tempArray.map(row=>{
     	inventory_data_array_damage.push(row);
     });
     //console.log(JSON.stringify(inventory_data_array_sale[0].INV_DATE.date))
     if(inventory_data_array_damage.length>0){
    var date = new Date(inventory_data_array_damage[0].INV_DATE);
	  var day = date.getDate();
	  if(day<10){
	  	day = '0'+day;
	  }
	  var month = date.getMonth();
	  if(month<9){
	  	month = '0'+(month+1);
	  }
	  else{
	  	month = month+1;
	  }
	  var year = date.getFullYear();
     batch.innerHTML = 'Last Batch Run: '+day+'/'+(month)+'/'+year;
     min_date = year+'-'+(month)+'-'+day;
    }
    else{batch.style.display="none";}
}
  };
  xhttp.open("GET", url, true);
  xhttp.send();
}


const showInventoryManagementDataForBreakage = (id,val) => {
	//getInventoryManagementDataForSale();
	var table_body=document.getElementById(id);
	var i=1;
	html='';
	var tempArray='';
	if(val.length >=3){
		tempArray=inventory_data_array_damage.filter(row=>row.BRAND_NAME.toLowerCase().includes(val.toLowerCase()))
	}
	else tempArray=inventory_data_array_damage;
	tempArray.map(row=>{
		
			html+='<tr style="cursor:pointer"><td>'+ i++ +'</td><td class="mid-text"><a href="#" onclick="appendToBreakageList(event,this.id)" id="'+ row.SKU_FK +'" class="move">'+ row.BRAND_NAME +'</a></td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.OPENING_BREAKAGE +'</td><td>'+ row.TOTAL_MRP_AMOUNT +'</td></tr>';
		
	})

	table_body.innerHTML= html;
}

const appendToBreakageList = (event,id) => {
	event.preventDefault();
	const found = invertory_damage_list.some(row=>row.SKU_FK==id);
	if(found){
		alert("Already added to the List!!");
		return false;
	}
	var data = inventory_data_array_damage.filter(row=>row.SKU_FK == id);
	// console.log(data);
	var SALE = 0;
	object = {
		SKU_FK: data[0].SKU_FK,
		GTIN: data[0].GTIN,
		QUANTITY: SALE,
		//INV_DATE: data[0].INV_DATE,
		BREAKAGE_OPENING: data[0].OPENING_BREAKAGE,
		BREAKAGE_CLOSING: data[0].OPENING_BREAKAGE,
		BRAND_NAME: data[0].BRAND_NAME,
		SIZE_VALUE: data[0].SIZE_VALUE,
		MRP: data[0].TOTAL_MRP_AMOUNT,
		TOTAL_AMOUNT: SALE*parseFloat(data[0].TOTAL_MRP_AMOUNT)
		}
	invertory_damage_list.unshift(object);
	showCurrentBreakageList();
	document.getElementById('id02').style.display='none';
	var getid = document.getElementById(id);
	//console.log(getid.parentNode.parentNode.childNodes[4]);
	getid.childNodes[4].focus();
	getid.childNodes[4].innerHTML='';
	// if(getid.childNodes[5].innerHTML=='0' || getid.childNodes[5].innerHTML==''){
		
	// }
}

const showCurrentBreakageList = () => {
	var saleList = document.getElementById('sale_inventory_grid');
	var html = '';
	var i= 1;
	var grandtotal=0;
	var totalQuantity=0;
	invertory_damage_list.map(row=>{
		html+='<tr id="'+ row.SKU_FK+'"><td>'+ i++ +'</td><td class="mid-text">'+ row.BRAND_NAME +'</td><td>'+ row.SIZE_VALUE +'</td><td>'+ row.BREAKAGE_OPENING +'</td><td contenteditable="true" onblur="updateBreakageInventoryList(this.parentNode.id)">'+ row.QUANTITY +'</td><td>'+ row.BREAKAGE_CLOSING +'</td><td>'+ row.MRP +'</td><td>'+ row.TOTAL_AMOUNT +'</td><td><a href="" onclick="deleteBreakageItemList(event,this.parentNode.parentNode.id)">X</a></td></tr>';
		grandtotal = grandtotal+parseFloat(row.TOTAL_AMOUNT);
		totalQuantity=totalQuantity+parseInt(row.QUANTITY);
	});

	html+='<tr><td colspan="4" style="text-align:right; padding-right:10px;"> <b>Total Quantity</td><td>'+totalQuantity+'</td><td colspan=2>Total Amount</td><td colspan=2>'+grandtotal+'</td>'
	// html+='<tr><td colspan="9"><button class="w3-button w3-red" id="submit_sale" type="button" onclick="submitSalesItems()">Submit</button> <button class="w3-button w3-red" type="button" onclick="deleteSalesList()">Cancel</button></td></tr>';
	saleList.innerHTML = html;
}

const updateBreakageInventoryList =(id)=>{
	//console.log(id);
	//var error = false;
	var tr = document.getElementById(id);
	var cells = tr.getElementsByTagName('td');
	var counter_opening = NaNtoBlank(cells[3].innerHTML);
	//var counter_opening = NaNtoBlank(cells[5].innerHTML);
	//var receiving = NaNtoBlank(cells[6].innerHTML);
	var counter_closing = NaNtoBlank(cells[5].innerHTML);
	var damage = NaNtoBlank(cells[4].innerHTML);
	if(damage==''){
		damage=0;
	}
	var MRP = NaNtoBlank(cells[6].innerHTML);
	var sale = 0;
	if(counter_opening < damage){
		alert("Damage cannot be more that Counter Opening");
		cells[4].innerHTML='';
		cells[4].focus();
			
	}
	else{
		counter_closing  = counter_opening - damage;
		// cells[4].innerHTML=  sale;
		cells[5].innerHTML= counter_closing;
		cells[7].innerHTML= damage*MRP;

		invertory_damage_list.map(row=>{
			if(row.SKU_FK==id){
				row.QUANTITY= damage;
				row.BREAKAGE_CLOSING=counter_closing;
				row.TOTAL_AMOUNT = parseInt(damage)*parseFloat(row.MRP);
			}
		});
		showCurrentBreakageList();
	}
	
}

const deleteBreakageItemList=(event,id)=>{
	event.preventDefault();
	invertory_damage_list = invertory_damage_list.filter(row=>{
		return row.SKU_FK != id;
	});
	showCurrentBreakageList();
}

const submitBreakageItems = () =>{
	 inv_lenth = invertory_damage_list.length;
	if(inv_lenth < 1){
		alert("Please Add Items to the Issue List!!");
	}
	else{
		sales_array_count=0;
		// arraydata = JSON.stringify(invertory_sale_list);
		var date = document.getElementById('sale_date').value;
		var invoice = document.getElementById('invoice_id').value;
		// document.getElementById('submit_sale').disabled = true;
		//console.log(data);
		var c=10;
		// debugger;
		invertory_damage_list.map(async row=>{
			arraydata = JSON.stringify(row);
			// console.log(c);
			var sku_fk = row.SKU_FK;
		var xhttp = new XMLHttpRequest();
			  xhttp.onreadystatechange = function() {
			    if (this.readyState == 4 && this.status == 200) {
			     //document.getElementById("demo").innerHTML = this.responseText;
			     //console.log(this.responseText);
			     if(this.responseText.includes('All Data Submitted Successfully')){
			     		
			     		sales_array_count++;
			     	 
			     }
			     else{
			     	console.log(this.responseText);
			     }
			    }
			  };
			  xhttp.open("POST", "issue_sale_query.php", true);
			  xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			  xhttp.send("breakage_data="+encodeURIComponent(arraydata)+"&date="+date+"&invoice="+invoice+"&SKU_FK="+sku_fk);

		});
		setTimeout(function(){
			if(sales_array_count == inv_lenth){
			alert("All Data Submitted Successfully!!!");
			window.location='breakage_sale.php';
		}
		else{
			// window.location='sales.php?status=error&msg=Something Went Wrong!!';
			alert("error");
		}
		},5000)
		}
		

		// if(invertory_sale_list.length == sales_array_count){
		// 	window.location='sales.php?status=success&msg=All Data Submitted Successfully!!!..Your Invoice No is: '+invoice;
		// }
		// else{
		// 	window.location='sales.php?status=error&msg=Something Went Wrong!!';
		// }
}



function getInvoiceDataForBreakage (invoice) {
	var url = 'issue_sale_query.php?fun=getInvoiceDataForBreakage&invoice='+invoice;
	invertory_damage_list=[];
	var sale_date = document.getElementById('sale_date');
		var xhttp = new XMLHttpRequest();
	  	xhttp.onreadystatechange = function() {
	    if (this.readyState == 4 && this.status == 200) {
	     // console.log(this.responseText);
	     var tempArray = JSON.parse(this.responseText);
	     tempArray.map(row=>{
	     	invertory_damage_list.push(row);
	     });

	     if(invertory_damage_list.length>0){
	     showCurrentBreakageList();
	     // document.getElementById('submit_sale').disabled=true;
	     var date = new Date(invertory_damage_list[0].CREATED_DATE.date);
	     var day = date.getDate();
	     if(day <10){
	     	day = '0'+day;
	     }
		 var month = date.getMonth();
		 if(month<9){
		 	month='0'+(month+1)
		 }
		 else{
		 	month=(month+1)
		 }
		 var year = date.getFullYear();

		 sale_date.value = year+'-'+month+'-'+day;
		 // issue_date.min = min_date;
		}
		else{
			alert('No data found for this Invoice');
		}
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
}




