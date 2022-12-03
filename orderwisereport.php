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
	<title>Order Register report</title>
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
					<h3>Order Register report</h3>
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
								<option value="All">ALL</option>
								<option value="('DSIIDC','DCCWS','DTTDC','DSCSC')">Corporation</option>
								<option value="Private ">Private</option>
								<option value="('CLUB','HOTEL','RESTAURANT')">HCR</option>
								<
							</select>
						</div>
						<div class="w3-container w3-center w3-col l2 w3-padding-small w3-margin-top">
						<button class="w3-button w3-round w3-red tohide" name="submit" type="Submit" id="submit" onclick="reprintinvoice()">Submit</button>
						
					</div>

					</div>
					<div class="w3-col l12 w3-border w3-border-black" style="min-height:200px;max-height: 200px;   overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
					<thead>
						<tr><th>SNo</th><th>Department</th><th>PO No</th><th>TP No</th><th>Brand Code</th><th>Brand Name</th><th>Size</th><th>Case</th><th>wsp</th><th>excise</th></tr>	
					</thead>
						<tbody id="order_register"  >
								</tbody>
				</table>
				</div>
				</div>


                     



				
			</div>
		</div>
		<div class="w3-center w3-margin-top">
		     <!-- <a href="registersummary.php" class="w3-red">SUMMARY</a> -->
		      <a href="order_summary_report.php" class="w3-red">SUMMARY </a>
		     <!-- <a href="registerdetails.php" class="w3-red">DETAILS</a> -->
		     <a href="order_summary_report_123.php" class="w3-red">DETAILS</a>

						
					</div>
						

	</div>
</div>
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">


 var enddate = document.getElementById('enddate');
		enddate.value= getTodaysDate();

		var startdate =document.getElementById('startdate');
	startdate.value = getFirstDay();
	 
const reprintinvoice = () =>{
 var Department=document.getElementById('Department');
	if(Department.value==''){
		alert("Please select Department ");
		return false;
	}
	else{
			var url = 'reportdata.php?register=register&&startdate='+startdate.value+'&enddate='+enddate.value+'&Department='+Department.value;
			 // console.log(url);
			fetch(url).then(data=>data.text()).then(data=>{

				// console.log(data);
				document.getElementById('order_register').innerHTML=data;
				
				
			});

}
		}



	</script>
</body>
</html>

