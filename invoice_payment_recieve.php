<?php
	include 'includes/session_company.php';
	$COMPANY_id = $_SESSION['COMPANY_id'];
	include 'includes/autoload.inc.php';
	include 'includes/connect.php';
	$USER = $_SESSION['username'];

  if(isset($_POST['submit'])){
    extract($_POST);
    
     $sql = "INSERT INTO [dbo].[POPS_PAYMENT_RECEIVED]
    ([INVOICE_NO]
    ,[INVOICE_AMOUNT]
    ,[RECEIVED_AMOUNT]
    ,[RECEIVED_DATE]
    ,[CHECK_NO]
    ,[CREATED_BY]
    ,[UPDATED_BY]
    )
VALUES('$invoice_num','$inv_amount','$amount_received','$startdate','$cheque_number','$USER','$USER')";
// exit;
$stmt = sqlsrv_query($conn,$sql);
if($stmt !=false){
  ?>
  <script>
    alert('Payment Received Successfully');
    window.location.href='invoice_payment_recieve.php';
  </script>
  <?php
}
else{
  // echo $sql;
  // exit;
  ?>
  <script>
    alert('Payment Failed. Try again');
    window.location.href='invoice_payment_recieve.php';

  </script>
  <?php
}
  }
  ?>
<!DOCTYPE html>
<html>
<head>
	<title>Receive Payment</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style rel="stylesheet" >
		i{
			cursor: pointer;
		}

		select{
			width: 80%;
		}
		input,select {
			height: 25px;
		}
	
	</style>
</head>
<body>
	<?php
		include 'includes/header_company.php';	
	?>
	<div class="w3-container">
<div class="body-content w3-white w3-small">
	<div class="w3-container w3-margin-bottom">
		<div class="w3-row">
			<div class="w3-col">
				<div class="w3-col l8">
					<h3>Receive Payment</h3>
					</div>
						<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom " style="margin-bottom: 3px!important;">
					<!-- <div class="w3-col l2 w3-padding-small"> </div> -->
                       
						<form action="" method="post" id="payment_form" onsubmit="return validateForm()">
                   
            <div class="w3-col l2 w3-padding-small">
						<label>Date </label>
						<input class="w3-input w3-border" type="date" name="startdate" id="startdate">
					</div>
					<div class="w3-col l2 w3-padding-small">
						<label>Invoice Number</label>
						<input class="w3-input w3-border" placeholder="Invoice Number" onchange="getInvoiceDetails(this.value)" type="text" list='invoice_list' name="invoice_num" id="invoice_num">
            <datalist id="invoice_list">
            <?php 
              $sqlinv = "select distinct INVOICE_NO from POPS_DISPATCH_ITEMS where INVOICE_NO is not null
              order by INVOICE_NO";
              $stmtinv =sqlsrv_query($conn,$sqlinv);
              while ($row = sqlsrv_fetch_array($stmtinv,SQLSRV_FETCH_ASSOC)){
                ?>
                    <option value="<?php echo $row['INVOICE_NO']; ?>">
                <?php
              }
            ?>
          </datalist>
					</div>

					 <div class="w3-col l2 w3-padding-small">
							<label>Invoice Amount</label>
              <input class="w3-input w3-border" placeholder="Invoice Amount" type="number" readonly name="inv_amount" id="inv_amount">
						</div>
            <div class="w3-col l2 w3-padding-small">
						<label>Amount Received</label>
						<input class="w3-input w3-border" type="text" placeholder="Amount Received" name="amount_received" id="amount_received">
					</div>
          <div class="w3-col l2 w3-padding-small">
						<label>Cheque Number</label>
						<input class="w3-input w3-border" placeholder="Cheque Number or Remark" type="text" name="cheque_number" id="cheque_number">
					</div>
          <!-- <div class="w3-col l2 w3-padding-small">
						<label>Invoice Number</label>
						<input class="w3-input w3-border" type="text" name="enddate" id="enddate">
					</div> -->
						<div class="w3-container w3-center w3-col l2 w3-padding-small w3-margin-top">
						<button class="w3-button w3-round w3-red tohide" name="submit" type="submit" id="submit" >Submit</button>
						
					</div>
          </form>
					</div>
          <div class="w3-col l8">
          <h3>Payment Received Details</h3>
          </div>
          <div class="w3-col l4">
           <input type="text" name="search_invoice" id="search_invoice" class="w3-input w3-border" style="margin: 5px 0" onkeyup="myFunction()" placeholder="Search Invoice">
          </div>
					<div class="w3-col l12 w3-border w3-border-black" style="min-height:200px;max-height: 200px;   overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable"   border="1">
					<thead>
						<tr><th>SNo</th><th>Invoice No</th><th>Invoice Amount</th><th>Received Amount</th><th>Received Date</th><th>Cheque Details/Remark</th><th>Action</th></tr>	
					</thead>
						<tbody id="challan_Status">
              <?php 
                $sqlp = "select * from POPS_PAYMENT_RECEIVED";
                $i=1;
                $stmtp = sqlsrv_query($conn,$sqlp);
                while ($row = sqlsrv_fetch_array($stmtp,SQLSRV_FETCH_ASSOC)){
                  ?>
                <tr><td><?= $i++ ?></td><td><?= $row['INVOICE_NO'] ?></td><td><?= $row['INVOICE_AMOUNT'] ?></td><td><?= $row['RECEIVED_AMOUNT'] ?></td><td><?= $row['RECEIVED_DATE']->format('d-m-Y') ?></td><td><?= $row['CHECK_NO'] ?></td><td class="w3-center w3-text-red"><i id='<?= $row["POPS_PAYMENT_RECEIVED_PK"] ?>' onclick="deleteReceivedAmount(this.id)" class="fa fa-trash" style='font-size: 15px;'></i></td></tr>
                  <?php
                }
              ?>
								</tbody>
				</table>
				</div>
				</div>			
			</div>
		</div>
		
						

	</div>
</div>
	</div>


	<div id="id01" class="w3-modal">
    <div class="w3-modal-content w3-card-4 w3-animate-zoom" style="max-width:450px">

      <div class="w3-center"><br>
        <span onclick="document.getElementById('id01').style.display='none'" class="w3-button w3-xlarge w3-hover-green w3-display-topright" title="Close Modal">&times;</span>
      </div>
			<div class="w3-section">
      <form class="w3-container" id="status_form" action="">
        
          <label><b>Change Status </b></label>
          <select id="statusId" name="challan_status" class="w3-select w3-border w3-margin-bottom" style="width:80% ">
          	<option value="">Change Status</option>
          	<option value="Pending">Pending</option>
          	<option value="Approve">Approve</option>
          	<option value="Reject">Reject</option>
          </select>
       
        
        <div >
						<label> Remark </label>
						<input class="w3-input w3-border" style="width:80%; margin-bottom: 10px " type="text" name="remark" id="remark">
					</div>

 
					<input  id='pk_id' class="w3-input w3-border w3-margin-bottom" type="hidden" name="pk_id">
					<input  id='vend_id' class="w3-input w3-border w3-margin-bottom" type="hidden" name="vend_id">
					<input  id='challan_date' class="w3-input w3-border w3-margin-bottom" type="hidden" name="challan_date">


       <input  id='tp_no' class="w3-input w3-border w3-margin-bottom" type="hidden" name="tp_no">
			 <div>
          <button class="w3-button w3-red" type="button" onclick="changeStatus()">SAVE</button>
			 </div>
			 </form>
      </div>
      

      <div class="w3-container w3-border-top w3-padding-16 w3-light-grey">
        <button onclick="document.getElementById('id01').style.display='none'" type="button" class="w3-button w3-orange w3-padding-small">Cancel</button>
      </div>

    </div>
  </div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
let invoice_received_amount =0;
let invoice_num = document.getElementById('invoice_num');
// console.log(invoice_num);
invoice_num.addEventListener('change',function(){getInvoiceReceivedAmount(this.value)});
//  var enddate = document.getElementById('enddate');
// 		enddate.value= getTodaysDate();

	var startdate =document.getElementById('startdate');
	startdate.value = getTodaysDate();
	 
const challan_Status = () =>{
 var Department=document.getElementById('Department');
	if(Department.value==''){
		alert("Please select Department ");
		return false;
	}
	else{
			var url = 'challan_statusdata.php?challanstatusdata=challanstatusdata&&startdate='+startdate.value+'&enddate='+enddate.value+'&Department='+Department.value;
			 // console.log(url);
			fetch(url).then(data=>data.text()).then(data=>{

				// console.log(data);
				document.getElementById('challan_Status').innerHTML=data;
				
				
			});

}
		}


const updatechallanStatus = (id) =>{
			console.log(id);
			document.getElementById('id01').style.display='block';
			document.getElementById('tp_no').value=id;
			let checkele = document.getElementsByName(id)[0];
			let chalan_no = checkele.getAttribute('data-id');
			let vend_id = checkele.getAttribute('data-vend');
			let challan_date = checkele.getAttribute('data-challandate');


			// console.log(chalan_no);
			document.getElementById('pk_id').value=chalan_no;
			document.getElementById('vend_id').value=vend_id;
			document.getElementById('challan_date').value=challan_date;



		}
		const changeStatus = ()=>{
			let status = document.getElementById('statusId');
			let remark = document.getElementById('remark');

			if(status.value==''){
				
				alert("Please change status");
				status.focus();
				return false;
			}

			if(remark.value==''){
				alert("Please Enter remark");
				remark.focus();
				return false;
			}

			let form = document.getElementById('status_form');
			let formData = new FormData(form);



			var challan_no=document.getElementById('pk_id').value;
			
			var url="challan_statusdata.php?fun=changeStatus";
			var xhttp = new XMLHttpRequest();
			xhttp.onreadystatechange = function() {
				if (this.readyState == 4 && this.status == 200) {
				document.getElementById('id01').style.display='none';
				alert(this.responseText);
				challan_Status();
				}
			};
			xhttp.open("POST", url, true);
			// xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send(formData);
     
		}
    let inv_num = document.getElementById('invoice_num');
      inv_num.addEventListener('dblclick', function(){this.value=''})
    const getInvoiceDetails = (invoice) =>{
      let url = 'challan_statusdata.php?fun=getInvoiceDetails&invoice='+invoice;
      fetch(url).then(data=>data.text()).then(data=>{
        document.getElementById('inv_amount').value=data;
      });
    }
    function getInvoiceReceivedAmount(invoice){
      let url = 'challan_statusdata.php?fun=getInvoiceReceivedAmount&invoice='+invoice;
      fetch(url).then(data=>data.text()).then(data=>{
        invoice_received_amount = parseFloat(data);        
      });
    }
    function validateForm(){
      var inv_num = document.getElementById('invoice_num');
      var received_amt = document.getElementById('amount_received');
      var inv_amount = document.getElementById('inv_amount');
      var cheque_number = document.getElementById('cheque_number');
      var due_amount = (parseFloat(inv_amount.value)-parseFloat(invoice_received_amount)).toFixed(2);
      console.log(due_amount);
      // return false;
      if(inv_num.value ==''){
        alert('Please Select Invoie Number');
        inv_num.focus();
        return false;
      }
      if(received_amt.value =='' || received_amt.value <=0){
        alert('Please Enter valid Amount Received');
        received_amt.value='';
        received_amt.focus();
        return false;
      }
      if(parseFloat(inv_amount.value)<parseFloat(received_amt.value)){
        alert("Received cannot be more than Invoice Amount");
        received_amt.focus();
        return false;
      }
      if(parseFloat(due_amount)<parseFloat(received_amt.value)){
        alert("Received amount cannot be more than Due Amount("+due_amount+")");
        received_amt.focus();
        return false;
      }
      
     
      if(cheque_number.value==''){
        alert('Please enter valid  Cheque Number or remark');
        cheque_number.focus();
        return false;
      }
      return true;
    }
    const deleteReceivedAmount = (pk) => {
      let url = 'challan_statusdata.php?fun=deleteReceivedAmount&pk='+pk;
      // console.log(url);
      if(!confirm('Are you sure you want to delete this entry??')){
        console.log("Don't delete")
        return false;
      }
      fetch(url).then(data=>data.text()).then(data=>{
        alert(data);
        window.location.reload();
      })
    }

  function myFunction() {
  var input, filter, table, tr, td, i, txtValue;
  input = document.getElementById("search_invoice");
  filter = input.value.toUpperCase();
  table = document.getElementById("challan_Status");
  tr = table.getElementsByTagName("tr");
  for (i = 0; i < tr.length; i++) {
    td = tr[i].getElementsByTagName("td")[1];
    if (td) {
      txtValue = td.textContent || td.innerText;
      if (txtValue.toUpperCase().indexOf(filter) > -1) {
        tr[i].style.display = "";
      } else {
        tr[i].style.display = "none";
      }
    }       
  }
}
	</script>
</body>
</html>

