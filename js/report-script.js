
const showReportTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var date = document.getElementById('sale_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?report=datewisesale&date='+date.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value;

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

const showReportTablecurrent = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var date = document.getElementById('sale_date');
	
	var url = 'report-query.php?invreport1=current_report&date='+date.value;

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
const showDailySale = () => {
	var fromdate = document.getElementById('fromdate').value;
	var todate = document.getElementById('todate').value;
	//var payment_mode = document.getElementById('mode').value;
  var type = document.getElementById('type').value
	var url = 'report-query.php?fun=showDailySale&fromdate='+fromdate+'&todate='+todate+'&type='+type;
	//+'&payment_mode='+payment_mode+'&type='+type;
	var html = '';
	const xhttp = new XMLHttpRequest();
	  xhttp.onload = function() {  	
	   // console.log(this.responseText);
	  // return false;
	   var data = JSON.parse(this.responseText);
	   var i=1;
	   var total_quantity=0;
	   var total_amount = 0;

	   var total_excise=0;
	   var total_WSP = 0;
	   var total_vat = 0;
	   var total_margin= 0;
	   var total=0;
	   var SUB_TOTAL=0;
	   grandtotal=0;
	   if(data.length>0){
	   data.map(row=>{

	   	//margin = row.Total-(row.EXCISE_PRICE +row.VAT+ row.WSP );
	   	html+='<tr><td>'+i+'</td><td class="mid-text">'+row.SHOP_CODE+'</td><td class="mid-text">'+row.SHOP_NAME+'</td><td>'+row.SALE_DATE+'</td><td class="mid-text">'+row.invoice+'</td><td class="mid-text">'+row.LIQUOR_TYPE.substr(0, row.LIQUOR_TYPE.indexOf("_"))+'</td><td class="mid-text">'+row.LIQUOR_CATEGORY+'</td><td class="mid-text">'+row.BRAND_NAME+'</td><td>'+row.SIZE_VALUE+'</td><td>'+row.QUANTITY+'</td> <td>'+row.MRP+'</td><td>'+row.EXCISE_PRICE+'</td><td>'+row.WSP+'</td><td>'+row.margin+'</td><td>'+row.VAT+'</td><td>'+row.SUB_TOTAL+'</td><td>'+row.TCS+'</td><td>'+row.GRAND_TOTAL+'</td><td>'+(row.MODE)+'</td><td>'+(row.TYPE_SALE)+'</td><td>'+(row.CUSTOMER_NAME)+'</td><td>'+row.MOBILE_NO+'</td></tr>';
	   total_quantity+=row.QUANTITY;
	   total_amount+=parseFloat(row.Total);
	   total_vat += parseFloat(row.VAT);

	   total_excise+=parseFloat(nullToZero(row.EXCISE_PRICE));
	   total_WSP += parseFloat(nullToZero(row.WSP));
	   total_margin += parseFloat(nullToZero(row.margin));
	   total += parseFloat(nullToZero(row.TCS));
	   SUB_TOTAL += parseFloat(nullToZero(row.SUB_TOTAL));
	   grandtotal += parseFloat(nullToZero(row.GRAND_TOTAL));

	   i++;
	   })
	   html +='<tr><td colspan=9>Total </td><td>'+total_quantity+'</td><td> </td ><td>' + total_excise.toFixed(2)+ ' </td ><td> ' + total_WSP.toFixed(2) + '</td ><td> ' + total_margin.toFixed(2)+ '</td ><td> ' + total_vat.toFixed(2)+ '</td ><td>'+SUB_TOTAL.toFixed(2)+'</td><td>'+ total.toFixed(2)+ '</td><td>'+grandtotal.toFixed(2)+'</td><td> </td ><td> </td ><td> </td ><td> </td ></tr>';
	 }
	 else{
	 	 html +='<tr><td colspan=21 class="w3-center">No Data Found </td></tr>';
	 }
	    document.getElementById('report-table').innerHTML = html;
	    document.getElementById('report_div').style.display='block';
	    }
	  xhttp.open("GET", url, true);
	  xhttp.send();
}
const ShowInvwise = () => {
	var fromdate = document.getElementById('fromdate').value;
	var todate = document.getElementById('todate').value;
	//var payment_mode = document.getElementById('mode').value;
  var type = document.getElementById('type').value
	var url = 'report-query.php?fun=ShowInvwise&fromdate='+fromdate+'&todate='+todate+'&type='+type;
	//+'&payment_mode='+payment_mode+'&type='+type;
	var html = '';
	const xhttp = new XMLHttpRequest();
	  xhttp.onload = function() {  	
	   //console.log(this.responseText);
	  // return false;
	   var data = JSON.parse(this.responseText);
	   var i=1;
	   var total_quantity=0;
	   var total_amount = 0;

	   
	   if(data.length>0){
	   data.map(row=>{

	   	//margin = row.Total-(row.EXCISE_PRICE +row.VAT+ row.WSP );
	   	html+='<tr><td>'+i+'</td><td class="mid-text">'+row.SHOP_CODE+'</td><td class="mid-text">'+row.SHOP_NAME+'</td><td>'+row.SALE_DATE+'</td><td class="mid-text">'+row.invoice+'</td><td class="mid-text">'+row.TP_NO+'</td><td class="mid-text">'+(row.STATUS_CD?'Approved':'Cancelled')+'</td><td class="mid-text">'+row.LIQUOR_TYPE.substr(0, row.LIQUOR_TYPE.indexOf("_"))+'</td><td class="mid-text">'+row.LIQUOR_CATEGORY+'</td><td class="mid-text">'+row.BRAND_NAME+'</td><td>'+row.SIZE_VALUE+'</td><td>'+row.QUANTITY+'</td> <td>'+row.MRP+'</td><td>'+row.SELLING_PRICE+'</td><td>'+row.GRAND_TOTAL+'</td><td>'+(row.MODE?row.MODE:'N/A')+'</td><td>'+(row.TYPE_SALE)+'</td><td class="mid-text">'+(row.CUSTOMER_NAME)+'</td><td>'+row.MOBILE_NO+'</td><td>'+row.STATIONERY_NUMBER+'</td></tr>';
	   total_quantity+=row.QUANTITY;
	   total_amount+=parseFloat(row.GRAND_TOTAL);
	   

	   i++;
	   })
	   html +='<tr><td colspan=11>Total </td><td>'+total_quantity+'</td><td> </td ><td> </td ><td >'+total_amount.toFixed(2) +' </td><td> </td ><td> </td ><td> </td ><td> </td ><td> </td ></tr>';
	 }
	 else{
	 	 html +='<tr><td colspan=24 class="w3-center">No Data Found </td></tr>';
	 }
	    document.getElementById('report-table').innerHTML = html;
	    document.getElementById('report_div').style.display='block';
	    }
	  xhttp.open("GET", url, true);
	  xhttp.send();
}

const showSaleDetailsTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var type = document.getElementById('type');
	var url = 'report-query.php?report1=datewisesale&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value+'&type='+type.value;
// console.log(url);
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
const showSaleDetailsTable_discount = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?report11=datewisesale_discount&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value;
// console.log(url);
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




const showCompanySaleDetailsTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');

	var url = 'report-query.php?report=showCompanySaleDetailsTable&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
 document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
       document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();

}



const showStockDetailsTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var x =document.querySelectorAll('.sb');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');

	var url = 'report-query.php?type=stock&report=stockdetails&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value;

	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
  		for (var i = 0; i < x.length; i++) {
  			x[i].style.display='inline';
  		}
  		
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
	
}

const showCompanyStockDetailsTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	// var x =document.querySelectorAll('.sb');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	
	var url = 'report-query.php?type=stock&report=showCompanyStockDetailsTable&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
// console.log(url);
// return false;
document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		
  		
  		
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
       document.getElementById('loading').style.display='none';
       div.style.display='block';
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
	
}

const showIssueTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var url = 'issue-report-query.php?fun=showIssueTable&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value;

	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (xhttp.readyState == 4 && xhttp.status == 200) {
      table.innerHTML =xhttp.responseText;
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
	
}

const showCompanyIssueTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	var url = 'issue-report-query.php?fun=showCompanyIssueTable&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (xhttp.readyState == 4 && xhttp.status == 200) {
    	console.log(this.responseText)
      table.innerHTML =xhttp.responseText;
       document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
}


const showVatReportTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?type=vat&report=datewisevat&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value;

	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	
      table.innerHTML =xhttp.responseText;
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}
const showScrapReportTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');

	var url = 'report-query.php?type=Scrap&report=datewiseScrap&fromdate='+fromdate.value+'&todate='+todate.value;
 // console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	
      table.innerHTML =xhttp.responseText;
      // console.log(xhttp.responseText) ;
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}
const showInvReportTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var date = document.getElementById('date');
	//var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?type=stock&report=showInvReportTable&date='+date.value+'&liqType='+liqType.value;

	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	
      table.innerHTML =xhttp.responseText;
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}

const showCompanyInvReportTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var date = document.getElementById('date');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	//var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	
	var url = 'report-query.php?type=stock&report=showCompanyInvReportTable&date='+date.value+'&liqType='+liqType.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
console.log(url);
document.getElementById('loading').style.display='block'

	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	
      table.innerHTML =xhttp.responseText;
    document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}


const showTpDetailsTable = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var supplier = document.getElementById('supplier_id');
	//var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?fun=showTpDetailsTable&supplier='+encodeURIComponent(supplier.value)+'&fromdate='+fromdate.value+'&todate='+todate.value;

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



const showCompanyTpDetailsTable = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var supplier = document.getElementById('supplier_id');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	//var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?fun=showCompanyTpDetailsTable&supplier='+encodeURIComponent(supplier.value)+'&fromdate='+fromdate.value+'&todate='+todate.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
document.getElementById('loading').style.display='block'
// console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
         document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
}


const showCompanyTpDetailsTable1 = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var supplier = document.getElementById('supplier_id');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	//var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?fun=showCompanyTpDetailsTable1&supplier='+encodeURIComponent(supplier.value)+'&fromdate='+fromdate.value+'&todate='+todate.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
document.getElementById('loading').style.display='block'
// console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
         document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
}








document.addEventListener('keydown', function(event) {

if(event.keyCode == 13) {
     	var active = document.activeElement;
     	if(active.hasAttribute('contenteditable')){
     		event.preventDefault();
     	}
     }

})
const showStockRegisterTable = () => {
	var date = document.getElementById('date').value;

	var url = 'report-query.php?fun=showStockRegisterTable&date='+date;
	var table = document.getElementById('report-table');
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		// div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
     
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
	
}

const showExciseRegisterTable = () => {
	var date = document.getElementById('date').value;
	var type = document.getElementById('type').value;

	document.getElementById('loading').style.display='block'
	var url = 'report-query.php?fun=showExciseRegisterTable&date='+date+'&type='+type;
	
	var table = document.getElementById('product_table');
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		// div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	document.getElementById('loading').style.display='none';

      table.innerHTML =this.responseText;
      

	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
	
}

const applyPO = () => {
	// var date = document.getElementById('date').value;
	
	var div = document.getElementById('report_div');
	var url = 'report-query.php?fun=applyPO';
	var table = document.getElementById('report-table');
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

const companyApplyPO = () => {
	// var date = document.getElementById('date').value;
	
	var div = document.getElementById('report_div');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	var url = 'report-query.php?fun=companyApplyPO&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
	var table = document.getElementById('report-table');
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
//----------------------------------------------------------showBreakageDetailsTable-----------
const showBreakageDetailsTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?Breakagereport=datewiseBreakage&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value;

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
const showCompanyBreakageDetailsTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');

	var url = 'report-query.php?Breakagereport=showCompanyBreakageDetailsTable&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
      document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();

	
}
const showBreakageReportTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var date = document.getElementById('Breakage_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?fun=datewise&date='+date.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value;

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
const showSupplierDetailsbrandTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	//var supp_name = document.getElementById('liq-type');
   var supplier = document.getElementById('Supplier');
    
	var url = 'report-query.php?fun=showsupplier&supplier='+supplier.value+'&fromdate='+fromdate.value+'&todate='+todate.value;

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
//-------------------------BrandWisePurchase-------------
const BrandWisePurchase = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	//var supp_name = document.getElementById('liq-type');
   var brand = document.getElementById('brand');
    
	var url = 'report-query.php?fun=BrandWisePurchase&brand='+brand.value+'&fromdate='+fromdate.value+'&todate='+todate.value;

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
//CostCardBrandWise---------------------------------------------------------------------
const CostCardBrandWise = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	//var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	//var supp_name = document.getElementById('liq-type');
   var brand = document.getElementById('brand');
   
	var url = 'report-query.php?card=CostCardBrandWise&brand='+brand.value+'&todate='+todate.value;

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
//----------------------------------------------------------showBreakage_sale Table-----------
const showBreakagesaleTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?Breakagesale=Breakagesale&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value;

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

const showCompanyBreakagesaleTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var liqCat = document.getElementById('liq-cat');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	var url = 'report-query.php?Breakagesale1=showCompanyBreakagesaleTable&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&liqCat='+liqCat.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
	console.log(url);

document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
      document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();

	
}


//----------------------------retail_profit--------------------------------------------
const showRetail_profitTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
		var brand_name = document.getElementById('brand_name');
	var url = 'report-query.php?report=retail_profit&fromdate='+fromdate.value+'&todate='+todate.value+'&brand_name='+brand_name.value;
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
const show_hcr_profitTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
		//var brand_name = document.getElementById('brand_name');
	var url = 'report-query.php?amir=hcr_profit&fromdate='+fromdate.value+'&todate='+todate.value;

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
//******************* mod of vat****************************************************//

const showModVatReportTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	//var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?type=Modvat&report=Modofvat&fromdate='+fromdate.value+'&todate='+todate.value;
  // console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	
      table.innerHTML =xhttp.responseText;
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}


const showCompanyModVatReportTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	//var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?type=Modvat&report=showCompanyModVatReportTable&fromdate='+fromdate.value+'&todate='+todate.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
  // console.log(url);
  document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	
      table.innerHTML =xhttp.responseText;
       document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}
//******************* SALE BOOK****************************************************//

function showSalebook () {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?type=salesbook&report=SALEBOOK&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value;
  // console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =xhttp.responseText;
      // console.log(this.responseText);
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
}
const showCompanySalebook = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	// var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?type=salesbook&report=showCompanySalebook&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
  console.log(url);
  // return false;
  var date1 = new Date(fromdate.value);
  var date2 = new Date(todate.value);

  const diffTime = Math.abs(date2 - date1);

  const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

  if(diffDays>=30){
  	alert("You can only view report of 30 days at a time!! Please change date");
  	todate.focus();
  	return false;
  }

  document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	// document.getElementById('loading').style.display='none'
      table.innerHTML =xhttp.responseText;
      document.getElementById('loading').style.display='none'
      // console.log(this.responseText);
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}
const currentinv = () => {
	// var date = document.getElementById('date').value;
	
	var div = document.getElementById('report_div');
	var url = 'report-query.php?fun=Current';
	var table = document.getElementById('report-table');
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
const companyCurrentInventory = () => {
	// var date = document.getElementById('date').value;

	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	var div = document.getElementById('report_div');
	var url = 'report-query.php?fun=companyCurrentInventory&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
	var table = document.getElementById('report-table');

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
const showStockLedgerTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var x =document.querySelectorAll('.sb');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	//var liqType = document.getElementById('liq-type');
	//var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?type=Ledger&report=stockdetailsLedger&fromdate='+fromdate.value+'&todate='+todate.value;
// console.log(url);
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
const purchase_register = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var supplier = document.getElementById('supplier_id');
	//var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?pur=purchase_register&supplier='+encodeURIComponent(supplier.value)+'&fromdate='+fromdate.value+'&todate='+todate.value;

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
const purchase_register_company = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var supplier = document.getElementById('supplier_id');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	
	//var liqCat = document.getElementById('liq-cat');
	// var url = 'report-query.php?pur=purchase_register&supplier='+encodeURIComponent(supplier.value)+'&fromdate='+fromdate.value+'&todate='+todate.value;
	var url = `report-query.php?pur=purchase_register_company&supplier=${encodeURIComponent(supplier.value)}&fromdate=${fromdate.value}&todate=${todate.value}&zone=${zone.value}&shop=${encodeURIComponent(shop.value)}`;
	document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
     // console.log(this.responseText);
         document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();

}
const purchase_register_company1 = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var supplier = document.getElementById('supplier_id');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	
	//var liqCat = document.getElementById('liq-cat');
	// var url = 'report-query.php?pur=purchase_register&supplier='+encodeURIComponent(supplier.value)+'&fromdate='+fromdate.value+'&todate='+todate.value;
	var url = `report-query.php?pur1=purchase_register_company1&supplier=${encodeURIComponent(supplier.value)}&fromdate=${fromdate.value}&todate=${todate.value}&zone=${zone.value}&shop=${encodeURIComponent(shop.value)}`;
	document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
     // console.log(this.responseText);
         document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();

}
const showQPN = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	//var supplier = document.getElementById('supplier_id');
	//var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?QPN=showQPN&fromdate='+fromdate.value+'&todate='+todate.value;
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
const showAging = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	//var fromdate = document.getElementById('from_date');
	//var todate = document.getElementById('to_date');
	//var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?type=showAging&report=showAgingreport';
  // console.log(url);
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	
      table.innerHTML =xhttp.responseText;
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}
const CompanyshowScrapReportTable = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	//var liqType = document.getElementById('liq-type');
	// var liqCat = document.getElementById('liq-cat');
	var url = `report-query.php?scrap=scrapsale&fromdate=${fromdate.value}&todate=${todate.value}&zone=${zone.value}&shop=${shop.value}`;
 
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
    	
      table.innerHTML =xhttp.responseText;

	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();


}
//******************** inv_wise_sale*******************
const showReportTableinv = () => {
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var date = document.getElementById('sale_date');
	
	var url = 'report-query.php?invreport=datewisesaleinv&date='+date.value;

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
const companyshowVatReportTable = () => {
	// var date = document.getElementById('date').value;

	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	var div = document.getElementById('report_div');
	var table = document.getElementById('report-table');
	
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var liqType = document.getElementById('liq-type');
	var url = 'report-query.php?type=vat&report=companydatewisevat&fromdate='+fromdate.value+'&todate='+todate.value+'&liqType='+liqType.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
	// console.log();
	//var table = document.getElementById('report-table');
document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
        document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
	
}
/**********************************************hcr report by customer************************/
const showCompanyHcrDetailsTable = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var supplier = document.getElementById('supplier_id');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	//var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?hcr=showCompanyHcrDetailsTable&supplier='+encodeURIComponent(supplier.value)+'&fromdate='+fromdate.value+'&todate='+todate.value+'&zone='+zone.value+'&shop='+encodeURIComponent(shop.value);
  // console.log(url);
  document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
      table.innerHTML =this.responseText;
       document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();
}
const showHcrDetailsTable = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var supplier = document.getElementById('supplier_id');
	// var zone = document.getElementById('company_zone');
	// var shop = document.getElementById('company_shop');
	//var liqCat = document.getElementById('liq-cat');
	var url = 'report-query.php?shcr=showHcrDetailsTable&supplier='+encodeURIComponent(supplier.value)+'&fromdate='+fromdate.value+'&todate='+todate.value;
  // console.log(url);
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
const companyshowQPN = () =>{
	var table = document.getElementById('report-table');
	var div = document.getElementById('report_div');
	var fromdate = document.getElementById('from_date');
	var todate = document.getElementById('to_date');
	var zone = document.getElementById('company_zone');
	var shop = document.getElementById('company_shop');
	var url = 'report-query.php?QPN1=showQPN1&fromdate='+fromdate.value+'&todate='+todate.value+'&zone='+zone.value+'&shop='+shop.value;
// console.log(url);
document.getElementById('loading').style.display='block'
	var xhttp = new XMLHttpRequest();
  	xhttp.onreadystatechange = function() {
  		div.style.display='block';
    if (this.readyState == 4 && this.status == 200) {
     table.innerHTML =this.responseText;
      // console.log(this.responseText);
      document.getElementById('loading').style.display='none'
	    }
	  };
	  xhttp.open("GET", url, true);
	  xhttp.send();

}
