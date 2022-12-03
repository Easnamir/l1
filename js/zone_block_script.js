const getBlock = (str) =>{
		
		// var shop = document.getElementById('shop');
		// console.log(str);
		var data=str.split('=');
		var id= data[1];
		var url = 'getblock.php?bid='+id;
		const xhttp = new XMLHttpRequest();
	  xhttp.onload =  function() {
	    document.getElementById("block").innerHTML = this.responseText;	

	    if(id=='101'){
		    var today = document.getElementById('all_date');
			today.value= getTodaysDate();
			}
			else if(id=='102'){
		    var today = document.getElementById('to_date');
			today.value= getTodaysDate();
			}
			
		else if(id=='100') {
			var today = document.getElementById('todate');
		today.value= getTodaysDate();

		var firstday =document.getElementById('fromdate');
		firstday.value = getFirstDay();
		}
		else {
			var today = document.getElementById('to_date');
		today.value= getTodaysDate();

		var firstday =document.getElementById('from_date');
		firstday.value = getFirstDay();
		}
	}
	  xhttp.open("GET", url, true);
	  xhttp.send();

	
	}
const showAllSaleReport =()=>{
	var sale_date = document.getElementById('all_date');
	var url='zone_report_query.php?date='+sale_date.value;
	var xhttp = new XMLHttpRequest();
  xhttp.onreadystatechange = function() {
    if (this.readyState == 4 && this.status == 200) {
     document.getElementById("report-table").innerHTML = this.responseText;
     document.getElementById('report_div').style.display='block'
     // console.log(this.responseText);
    }
  };
  xhttp.open("GET", url, true);
  xhttp.send();
}
//Inv_BrandWise---------------------------------------------------------------------
const InvzoneBrandWise = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	//var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	//var supp_name = document.getElementById('liq-type');
   // var brand = document.getElementById('brand');
   var shop_id = document.getElementById('shop_id').value;
   // console.log("he",shop_id);
	var url = 'zone_report_query.php?zone=ZoneBrandWise&brand=All&shop_id='+shop_id+'&todate='+todate.value;
// console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	// console.log(this.responseText)
      table.innerHTML =this.responseText;
     
      
	    }
	  }
	  // console.log(url);
	  xhttp.open("GET", url, true);
	  xhttp.send();

	

}

const getInvoiceDetails = (id)=>{
		var url="report-query.php?fun=getInvoiceDetails&tp="+id;
		const xhttp = new XMLHttpRequest();
		  xhttp.onload = function() {
		    document.getElementById('report_detail').innerHTML=(this.responseText);
		    document.getElementById('id01').style.display ='block'
		    }
		  xhttp.open("GET", url, true);
		  xhttp.send();
	}

//AVG_BrandWise---------------------------------------------------------------------
const AVGzoneBrandWise = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	//var supp_name = document.getElementById('liq-type');
   //var brand = document.getElementById('brand');
   var shop_id = document.getElementById('shop_id').value;
   
	var url = 'zone_report_query.php?zone=AVGzoneBrandWise&fromdate='+fromdate.value+'&todate='+todate.value+'&shop_id='+shop_id;
// console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
     
      
	    }
	  }
	  // console.log(url);
	  xhttp.open("GET", url, true);
	  xhttp.send();

	

}
////////////////////////////////////Low_inv///////////////////////////////
const Low_inv = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	//var fromdate = document.getElementById('from_date');
	//var todate = document.getElementById('to_date');
	var shop_id = document.getElementById('shop_id').value;
   // var brand = document.getElementById('brand');
   
	var url = 'zone_report_query.php?zone1=Low_inv&shop_id='+shop_id;
 // console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
     // console.log(this.responseText);
      
	    }
	  }
	  
	  xhttp.open("GET", url, true);
	  xhttp.send();

	

}
const showVatReportTableZone = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	//var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	var shop_id = document.getElementById('shop_id').value;
	var url = 'zone_report_query.php?type=vat&report=Zone&fromdate='+fromdate.value+'&todate='+todate.value+'&shop_id='+shop_id;

	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	
      table.innerHTML =xhttp.responseText;
      // console.log(xhttp.responseText);
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}

const showDailySaleZone = () => {
	var fromdate = document.getElementById('fromdate').value;
	var todate = document.getElementById('todate').value;
	var shop_id = document.getElementById('shop_id').value;

  var type = document.getElementById('type').value
	var url = 'zone_report_query.php?fun=showDailySaleZone&fromdate='+fromdate+'&todate='+todate+'&type='+type+'&shop_id='+shop_id;
	//+'&payment_mode='+payment_mode+'&type='+type;
	var html = '';
	document.getElementById('loading').style.display='block';
	const xhttp = new XMLHttpRequest();
	  xhttp.onload = function() {  	
	    console.log(this.responseText)
	   var data = JSON.parse(this.responseText);
	   var i=1;
	   var total_quantity=0;
	   var total_amount = 0;

	   var total_excise=0;
	   var total_WSP = 0;
	   var total_vat = 0;
	   var total_margin= 0;
	   var total=0;
	   var total_discount=0
	   var SUB_TOTAL=0;
	   var grandtotal=0;

	   // var qty=0;
	   // var amount=0;
	   // var excise=0;
	   // var wsp=0;
	   // var discount=0;
	   // var tcs =0;
	   // var vat=0;
	   // var margin=0;
	   // var sub=0;
	   // var gt_amt=0;
	   if(data.length>0){
	   data.map(row=>{
	   	var qty=0;
	   var amount=0;
	   var excise=0;
	   var wsp=0;
	   var discount=0;
	   var tcs =0;
	   var vat=0;
	   var margin=0;
	   var sub=0;
	   var gt_amt=0;
	   	if(row.STATUS_CD>0){
	   		tcs = (row.DESCRIPTION=='CLUB')?0:row.TCS;
	   		qty = row.QUANTITY;
	   		amount=parseFloat(row.Total);
	   		vat = parseFloat(row.VAT);
	   		excise = parseFloat(nullToZero(row.EXCISE_PRICE));
	   		discount = parseFloat(nullToZero(row.discount));
	   		wsp = parseFloat(nullToZero(row.WSP));
	   		margin = parseFloat(nullToZero(row.margin));
	   		sub = parseFloat(nullToZero(row.SUB_TOTAL));
	   		gt_amt = parseFloat(sub+tcs);
	   	}
	   	// var tcs = row.DESCRIPTION=='CLUB'?0:row.TCS;

	   	//margin = row.Total-(row.EXCISE_PRICE +row.VAT+ row.WSP );
	   	html+='<tr><td>'+i+'</td><td class="mid-text">'+(row.ZONE).substring(0,4)+' '+(row.ZONE).substring(4)+'</td><td class="mid-text">'+row.WARD_NUM+'</td><td>'+row.WARD_NAME+'</td><td class="mid-text">'+row.SHOP_CODE+'</td><td class="mid-text">'+row.SHOP_NAME+'</td><td>'+row.SALE_DATE+'</td><td class="mid-text">'+row.invoice+'</td><td>'+row.STATIONERY_NUMBER+'</td><td class="mid-text">'+row.TP_NO+'</td><td class="mid-text">'+(row.STATUS_CD?'Approved':'Cancelled')+'</td><td class="mid-text">'+capitalizeFirstLetter(row.LIQUOR_TYPE.substr(0, row.LIQUOR_TYPE.indexOf("_")))+'</td><td class="mid-text">'+capitalizeFirstLetter(row.LIQUOR_CATEGORY)+'</td><td class="mid-text">'+row.BRAND_NAME+'</td><td>'+row.SIZE_VALUE+'</td><td>'+qty+'</td> <td>'+row.MRP+'</td><td>'+excise+'</td><td>'+wsp+'</td><td>'+margin+'</td><td>'+(discount)+'</td><td>'+vat+'</td><td>'+sub+'</td><td>'+(tcs)+'</td><td>'+gt_amt+'</td><td>'+(row.MODE)+'</td><td>'+(row.TYPE_SALE)+'</td><td>'+(row.CUSTOMER_NAME)+'</td><td>'+row.MOBILE_NO+'</td><td>'+(row.LICENCE_CODE)+'</td><td>'+row.PAN_NO+'</td></tr>';
	   total_quantity+=qty;
	   total_amount+=amount;
	   total_vat += vat;

	   total_excise+=excise;
	   total_WSP += wsp;
	   total_margin += margin;
	   total_discount += discount;
	   total += parseFloat(nullToZero(tcs));
	   SUB_TOTAL += sub;
	   grandtotal += gt_amt;

	   i++;
	   })
	   document.getElementById('loading').style.display='none';
	   html +='<tr><td colspan=15>Total </td><td>'+total_quantity+'</td><td> </td ><td>' + total_excise.toFixed(2)+ ' </td ><td> ' + total_WSP.toFixed(2) + '</td ><td> ' + total_margin.toFixed(2)+ '</td ><td> ' + total_discount.toFixed(2)+ '</td ><td> ' + total_vat.toFixed(2)+ '</td ><td>'+SUB_TOTAL.toFixed(2)+'</td><td>'+ total.toFixed(2)+ '</td><td>'+grandtotal.toFixed(2)+'</td><td> </td ><td> </td ><td> </td ><td> </td ><td> </td ><td> </td ><td> </td ></tr>';
	 }
	 else{
	 	 html +='<tr><td colspan=30 class="w3-center">No Data Found </td></tr>';
	   document.getElementById('loading').style.display='none';
	 	 
	 }
	    document.getElementById('report-table').innerHTML = html;
	    document.getElementById('report_div').style.display='block';
	    }
	  xhttp.open("GET", url, true);
	  xhttp.send();
}

function capitalizeFirstLetter(string) {
  return string.charAt(0).toUpperCase() + (string.slice(1)).toLowerCase();
}
const Zone_purchase_register = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var supplier = document.getElementById('supplier_id');
	var shop_id = document.getElementById('shop_id').value;
	var url = 'zone_report_query.php?pur=purchase_register&supplier='+encodeURIComponent(supplier.value)+'&fromdate='+fromdate.value+'&todate='+todate.value+'&shop_id='+shop_id;
// console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
     // console.log(this.responseText);
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();

}
//----------------------------retail_profit--------------------------------------------
const ZoneRetail_profitTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
		var brand_name = document.getElementById('brand_name');
		var shop_id = document.getElementById('shop_id').value;
	var url = 'zone_report_query.php?report=retail_profit&fromdate='+fromdate.value+'&todate='+todate.value+'&brand_name='+brand_name.value+'&shop_id='+shop_id;
//console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();

}
const Zone_hcr_profitTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var shop_id = document.getElementById('shop_id').value;
		//var brand_name = document.getElementById('brand_name');
	var url = 'zone_report_query.php?amir=hcr_profit&fromdate='+fromdate.value+'&todate='+todate.value+'&shop_id='+shop_id;

	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
      //console.log(this.responseText);
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();

}
