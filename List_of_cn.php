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
	<title> Reprint </title>
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
					<h3>List of Credit Note</h3>
					</div>
						<div class="w3-col l12 w3-border w3-border-black w3-margin-bottom " style="margin-bottom: 3px!important;">
					<!-- <div class="w3-col l2 w3-padding-small"> </div> -->
                       
					
                                        <div class="w3-col l2 w3-padding-small">
						<label> From CN No </label>
						<input class="w3-input w3-border" type="number" name="from_sno" id="from_sno" placeholder="Enter Invoice No " value="0">
					</div>
					<div class="w3-col l2 w3-padding-small">
						<label> To  CN No</label>
						<input class="w3-input w3-border" type="number" name="to_sno " id="to_sno" placeholder="Enter Invoice No" value="0">
					</div>






						<div class="w3-container w3-center w3-col l2 w3-padding-small w3-margin-top">
						<button class="w3-button w3-round w3-red tohide" name="submit" type="Submit" id="submit" onclick="printcn()">Submit</button>
						
					</div>

					</div>
					<div class="w3-col l12" style="min-height:200px; max-height: 200px;  overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table w3-bordered w3-striped w3-card-4" border="1">
					<thead>
						<tr><th>SNo</th><th>CN No</th><th>CN DATE</th><th>Excise Code</th><th>Party Name</th><th>Refrence</th><th>Month</th><th>Total Value </th><th>Narration</th><th>Action</th></tr> 
					</thead>
						<tbody id="printcn_data"  >
								</tbody>
				</table>
				</div>
				
				</div>
				<br>
				
				</div>
		</div>
	</div>
</div>
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">
const printcn = () =>{
 var to_sno=document.getElementById('to_sno');
 var from_sno=document.getElementById('from_sno');
	
	 if(to_sno.value=='' || from_sno.value==''){
		alert("Please select invoice no ");
		return false;
	}
	 if(to_sno.value==0 || from_sno.value==0){
		alert("Please select  valid invoice no ");
		return false;
	}
	
	 if(from_sno.value > to_sno.value){
		alert("Please select  valid invoice no ");
		to_sno.value=0;
		return false;
	}

			var url = 'cndn_query.php?printcn=printcn&to_sno='+to_sno.value+'&from_sno='+from_sno.value;
			 // console.log(url);
			fetch(url).then(data=>data.text()).then(data=>{

				// console.log(data);
				document.getElementById('printcn_data').innerHTML=data;
				
				
			});

}

const printcninvoice= (id)=>{
         // console.log(id);
         var url = 'print_cn_invoice.php?fun=printInvoicecn&id='+id;
          window.open(url);   
      }
	

	</script>
</body>
</html>

