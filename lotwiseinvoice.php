<?php
session_start();
   include 'includes/session_company.php';
   $COMPANY_id = $_SESSION['COMPANY_id'];
   include 'includes/autoload.inc.php';
   include 'includes/connect.php';
   $USER = $_SESSION['username'];
   // var_dump($databse);
   // exit;
    ?>
<!DOCTYPE html>
<html>
   <head>
      <title>Challan Creation</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <link rel="stylesheet" href="css/w3.css">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
      <link rel="stylesheet" type="text/css" href="css/style.css">
      <style rel="stylesheet" >
      <style>
         select{
         width: 80%;
         }
         input,select {
         height: 25px;
         }
      </style>
      <script type="text/javascript"></script>
   </head>
   <body>
      <?php
         include 'includes/header_company.php';	
         ?>
      <div class="w3-container">
      <div class="body-content w3-white w3-small">
         <div class="w3-container w3-margin-bottom">
            <div class="w3-row">
               <div class="w3-col l8">
                  <h3> Lot Wise Submission</h3>
               </div>
               <div class="w3-col l12 w3-border w3-border-black w3-card w3-margin-bottom " style="margin-bottom: 3px!important;">
                  <!-- <div class="w3-col l2 w3-padding-small"> </div> -->
                  <div class="w3-col l2 w3-padding-small">
                     <label>Department<span class="w3-text-red">*</span></label>
                     <select name="Department" class="w3-select" id="Department" onchange="Department(this.value)">
                        <option value="">Select Department</option>
                        <?php
                  $sqlc = "select DEPARTMENT_NAME,DEPARTMENT from POPS_DEP_DETAILS";
                  $stmtc = sqlsrv_query($conn,$sqlc);
                  while($rowc = sqlsrv_fetch_array($stmtc,SQLSRV_FETCH_ASSOC)){

                   ?>
                   <option value="<?php echo $rowc['DEPARTMENT'] ?>"><?php echo $rowc['DEPARTMENT_NAME'] ?></option>
                   <?php
                  }
                  ?>
                     </select>
                  </div>
                  <div class="w3-col l2 w3-padding-small">
                     <label>Submission Date</label>
                     <input class="w3-input w3-border" type="date" name="Submission" id="Submission">
                  </div>
                  <div class="w3-col l2 w3-padding-small">
                     <label>PO no<span class="w3-text-red">*</span></label>
                     <select name="po_no" class="w3-select" id="po_no">
                        <option value="">Select PO </option>
                     </select>
                  </div>
                  <div class="w3-container w3-center w3-col l2 w3-padding-small w3-margin-top">
                     <button class="w3-button w3-round w3-red tohide" name="submit" type="Submit" id="submit" onclick='lotwiseinvoice()'>Submit</button>
                  </div>
               </div>
               <div class="w3-col l12 w3-border w3-border-black" style="min-height: 200px;max-height: 200px;  overflow:auto">
                  <div class="w3-border w3-border-grey">
                     <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
                        <thead>
                           <tr>
                              <th>SNo</th>
                              <th>Challan No</th>
                              <th>Tp_No</th>
                              <th>Department</th>
                              <th>Party Name</th>
                              <th>QTY</th>
                              <th>wsp</th>
                              <th>Excise</th>
                             <th> <input type="checkbox" name="checkAllchallan" id="checkAllchallan" value="" onclick="checkAll(this,'tp_inv[]')"/> All</th>
                           </tr>
                        </thead>
                        <tbody id="show_item_lot"  >
                        </tbody>
                     </table>
                  </div>
               </div>
               <table class="w3-table w3-margin-bottom w3-margin-top" border="1">
                  <!-- <tr><td colspan=12 style="height:1px !important; width:100%">&nbsp;</td></tr> -->
                  <tr>
                     <td width=70%>&nbsp;</td><td class="mid-text" width=15%>Selected Cases: <span id="total_case">0</span> </td>
                     <td style="width: 15%; font-weight: bold !important;">Generate Invoice</td>
                     <td style="width: 15%;">
                        <button type='button' onclick='processinvoicelot()' class='w3-red w3-button w3-round'>Process</button>
                     </td>
                  </tr>
               </table>
            </div>

             <div class="w3-col l12" >
               <div class="w3-border w3-border-grey" style="max-height: 200px;min-height: 200px;  overflow:auto">
               <table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
               <thead>
                  <tr><th>SNo</th><th>Inv_No</th><th>Inv_Date</th><th>PO_N0</th><th>Department</th><th>Case</th><th>wsp</th><th>excise</th><th>Vat</th><th>Total</th><th style="text-align: left !important; padding-left: 10px !important; ">  Action </th></tr> 
               </thead>
                  <tbody id="show_invoice"  >
                        </tbody>
            </table>
            </div>
            </div>
            
         </div>

         <form method='POST' action="#" id='tp_data_form'>
					<input type="hidden" name="item_str" id="tp_list_id" value="">
				 </form>	


         </div>
      </div>
      <?php include 'includes/footer.php'; ?>
      <script type="text/javascript">
         var Submission = document.getElementById('Submission');
         		Submission.value= getTodaysDate();
         	var po = document.getElementById('po_no');	
         	var Dep = document.getElementById('Department');	
         const Department=(Department)=>{
         
            var url = 'invoicedata.php?Dept=Dept&Department='+Department;
         			 // console.log(url);
         			fetch(url).then(data=>data.text()).then(data=>{
                    // console.log(data);
         				document.getElementById('po_no').innerHTML=data;
         				
         				
         			});
         
         
         	}
          const lotwiseinvoice = () =>{
         			var url = 'invoicedata.php?lotinvoice=lotinvoice&Submission='+Submission.value+'&po='+po.value+'&Department='+Dep.value;
         			 // console.log(url);
         			fetch(url).then(data=>data.text()).then(data=>{
         				document.getElementById('show_item_lot').innerHTML=data;
         				
         				
         			});
         
         
         		}
         
         const processinvoicelot = () =>{
         			// var checkedItem = document.getElementById('item_body');
         			var lists = document.getElementsByName('tp_inv[]');
         			// console.log(typeof(lists));
         			var tp_list = [];
         			lists.forEach(function(list){
         				if(list.checked==true) {
         					tp_list.push(list.value);
         				}
         			})
         			// console.log(tp_list);
         			if(tp_list.length==0) {
         				alert('Please select items to process');
         				return false;
         			}
         			var item_str = JSON.stringify(tp_list);
         			// console.log(item_str);
         			var url = 'invoicedata.php?fun=processinvoicetp&Submission='+Submission.value;
         		// console.log(url);
         	// 		fetch(url).then(data=>data.text()).then(data=>{
         	// 			// document.getElementById('show_item').innerHTML=data;
         	//  // console.log(data);
         	// 			alert(data);
         	// 			window.location.reload();
         	// 		})
            document.getElementById('tp_list_id').value=item_str;
			var form = document.getElementById('tp_data_form');
			var data_form = new FormData(form);
	// 		fetch(url).then(data=>data.text()).then(data=>{
	// 			// document.getElementById('show_item').innerHTML=data;
	//  // console.log(data);
	// 			alert(data);
	// 			window.location.reload();
	// 		})
			const xhttp = new XMLHttpRequest();
			xhttp.onload = function() {
				alert(this.responseText);
				window.location.reload();
				}
			xhttp.open("POST", url, true);
			xhttp.send(data_form);
         		}

const getSelectedCases = () =>{
         var input = document.getElementsByName('tp_inv[]');
         var total_cases = 0;
         var case_id = document.getElementById('total_case');

         input.forEach(item=>{
               if(item.checked == true){
                  total_cases += parseInt(item.dataset.case);
               }
            })
            console.log(total_cases);
            case_id.innerHTML = total_cases;

      }


function checkAll(obj,elementname){
         // console.log(obj.checked);
         var input = document.getElementsByName(elementname);
         if(obj.checked == true){
            input.forEach(item=>{
               if(item.disabled == false){
               item.checked = true;
               }
            })
         }
         else{
            input.forEach(item=>{
               item.checked = false;
            })
         }
         getSelectedCases();
      }

      const Check_po_list=()=>{
         var input = document.getElementsByName('tp_inv[]');
         var all = document.getElementById('checkAllchallan');
         var all_checked = true;
         input.forEach(item=>{
               if(item.checked == false){
                  all_checked = false;
               }
            })
         if(all_checked){
            all.checked= true;
         }
         else{
            all.checked= false;
         }
         getSelectedCases();
      }
      
       

   const Show_invoice_list = () =>{
         var url = 'invoicedata.php?Show_invoice_list=Show_invoice_list';
          // console.log(url)
         fetch(url).then(data=>data.text()).then(data=>{
            document.getElementById('show_invoice').innerHTML=data;

            // console.log(data);
         })
      }
      Show_invoice_list();

// const printInvoice1 = (invoice)=>{
//          // console.log(tp);
//          var url1 = 'print_tp_invoice_gov.php?fun=printInvoice1&invoice='+invoice;
//          var print = 'invoicedata.php?print_invoice='+invoice;

//          // console.log(print);
//          window.open(url1);
         
         
//       }
      const printInvoice = (invoice)=>{
         // console.log(tp);
         var url1 = 'print_tp_invoice_gov.php?fun=printInvoice1&invoice='+invoice;
         var url2 = 'table.php?invoice='+invoice;
         var print = 'invoicedata.php?invoice121='+invoice;
         
         // window.open(url2);
         setTimeout(()=>window.open(url2),0);
         // setTimeout(()=>window.open(url1),500);
         //  window.open(url1);
         //   window.open(print);

          fetch(print).then(data=>data.text()).then(data=>{
            // document.getElementById('show_invoice').innerHTML=data;
window.location.reload();
            // console.log(data);
         })
         
         
      }


              
      </script>
   </body>
</html>