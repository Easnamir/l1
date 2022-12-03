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
	<title> Update challan status </title>
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
					<h3>Update challan status</h3>
					</div>
						<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom " style="margin-bottom: 3px!important;">
					<!-- <div class="w3-col l2 w3-padding-small"> </div> -->
                       
						
                   
                   <div class="w3-col l2 w3-padding-small">
						<label> Start Date </label>
						<input class="w3-input w3-border" type="date" name="startdate" id="startdate">
					</div>
					<div class="w3-col l2 w3-padding-small">
						<label> End Date </label>
						<input class="w3-input w3-border" type="date" name="enddate" id="enddate">
					</div>

					 <div class="w3-col l2 w3-padding-small">
							<label>Department<span class="w3-text-red">*</span></label>
              <select name="Department" class="w3-select" id="Department">
								<option value="('DSIIDC','DCCWS','DTTDC','DSCSC')">Corporation</option>
								
								<
							</select>
						</div>
						<div class="w3-container w3-center w3-col l2 w3-padding-small w3-margin-top">
						<button class="w3-button w3-round w3-red tohide" name="submit" type="Submit" id="submit" onclick="challan_Status()">Submit</button>
						
					</div>

					</div>
					<div class="w3-col l12 w3-border w3-border-black" style="min-height:200px;max-height: 200px;   overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
					<thead>
						<tr><th>SNo</th><th>Department</th><th>Vend Name</th><th>TP No</th><th>Challan No </th><th>Challan Date</th><th>Status</th><th>Remark </th><th>action </th></tr>	
					</thead>
						<tbody id="challan_Status"  >
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


 var enddate = document.getElementById('enddate');
		enddate.value= getTodaysDate();

		var startdate =document.getElementById('startdate');
	startdate.value = getFirstDay();
	 
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
			xhttp.send(formData);
		}
	</script>
</body>
</html>

