<?php
	include 'includes/session_company.php';
	$COMPANY_id = $_SESSION['COMPANY_id'];
	include 'includes/autoload.inc.php';
	include 'includes/connect.php';
	$USER = $_SESSION['username'];
  ?>
<!DOCTYPE html>
<html>
<head>
	<title>Update Challan status </title>
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
					<h3>Update challan</h3>
					</div>
						<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom " style="margin-bottom: 3px!important;">
						<div class="w3-col l2 w3-padding-small">
							<label for="tp_number">Tp Number</label>
							<input type="text" placeholder="Enter TP Number" name="tp_num" id="tp_num" class="w3-input w3-border">
						</div>

					 <div class="w3-col l2 w3-padding-small">
					 <label>Status<span class="w3-text-red">*</span></label>
              <select name="tp_status" class="w3-select" id="tp_status">
							<option value="">Change Status</option>
							<option value="TP Received">TP Received</option>
							<option value="TP Not Received">TP Not Received</option>
							</select>
						</div>
						<div class="w3-col l6 w3-padding-small">
							<label for="tp_number">Remark</label>
							<input type="text" placeholder="Enter Remark"  name="tp_remark" id="tp_remark" class="w3-input w3-border">
						</div>
						<div class="w3-container w3-center w3-col l1 w3-padding-small w3-margin-top">
						<button class="w3-button w3-round w3-red tohide" onclick="updateChallanStatus()" name="submit" type="button" id="submit" >Submit</button>
						
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
 let vend_id = '';
 let supply_date ='';
 let challan_no='';
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
			document.getElementById('pk_id').value=chalan_no;
			document.getElementById('vend_id').value=vend_id;
			document.getElementById('challan_date').value=challan_date;
		}
		
		var tp_num = document.getElementById('tp_num');
		tp_num.focus();
		tp_num.addEventListener('change', function(){			
			let url = 'status_query.php?fun=checkTpstatus&tp_num='+tp_num.value;
			fetch(url).then(data=>data.json()).then(data=>{
				if(data.length==0){
					alert('Challan not found');
					
					window.location.reload();
					return false;
				}
				console.log(data[0].SUPPLY_DATE.date);

				 document.getElementById('tp_status').value=data[0].STATUS_CHALLAN?data[0].STATUS_CHALLAN:'';
				 document.getElementById('tp_remark').value=data[0].REMARK_CHALLAN;
				 vend_id = data[0].VEND_CODE;
				 supply_date = data[0].SUPPLY_DATE.date;
				 challan_no = data[0].CHALLAN_NO;


			})
		document.getElementById('tp_status').focus();
		});

		function updateChallanStatus(){
			var tp_num = document.getElementById('tp_num');
			var tp_status = document.getElementById('tp_status');
			var tp_remark = document.getElementById('tp_remark');
			if(tp_num.value.length !=15 ){
				alert('Invalid tp_num');
				tp_num.focus();
				return false;
			}
			if(tp_status.value ==''){
				alert('Please select status');
				tp_status.focus();
				return false;
			}
			if(tp_remark.value ==''){
				alert('Please Enter Remark');
				tp_remark.focus();
				return false;
			}
			let url = 'status_query.php?fun=updateChallanStatus';
			// console.log(url);
			let body = JSON.stringify({
			tp_no: tp_num.value,
			challan_status: tp_status.value,
			remark: tp_remark.value,
			challan_date: supply_date,
			pk_id: challan_no,
			vend_id:vend_id

		 });
		//  console.log(body);
			fetch(url, {
     
		 // Adding method type
		 method: "POST",
			
		 // Adding body or contents to send
		 body,
			
		 // Adding headers to the request
		 headers: {
				 "Content-type": "application/json; charset=UTF-8"
		 }
 })
	
 // Converting to JSON
 .then(response => response.text())
	
 // Displaying results to console
 .then(data => {
	alert(data);
	window.location.reload();
 });
		}
	</script>
</body>
</html>

