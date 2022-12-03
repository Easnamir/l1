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
	<title>Tally</title>
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
					<h3>Tally</h3>
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

					 
						<div class="w3-container w3-center w3-col l2 w3-padding-small w3-margin-top">
						<button class="w3-button w3-round w3-red tohide" name="submit" type="Submit" id="submit" onclick="tallyreport()">Submit</button>
						
					</div>

					</div>
					<div class="w3-col l12 w3-border w3-border-black" style="min-height:200px;max-height: 200px;   overflow:auto">
					<div class="w3-border w3-border-grey" id="table_div">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1" style="width: 4000px">
					<thead>
						<tr>
	               <th>SNo.</th>	
					<th>Invoice Number</th>
					<th>Invoice Date</th>
					<th>Order Number</th>	
					<th>Order Date</th>	
					<th>Permit Number</th>	
					<th>Permit Date</th>	
					<th>EXCISE CODE</th>	
					<th>Party Name</th>	
					<th>TIN	</th>
					<th>PAN	</th>
					<th>Group</th>	
					<th>Godown Name</th>	
					<th>Item Name</th>	
					<th>Item Group</th>	
					<th>Item Category</th>	
					<th>Item Unit</th>	
					<th>Total Quantity</th>	
					<th>Rate</th>	
					<th>Bill Value</th>	
					<th>Excise Sale</th>	
					<th>Sales-Custom Duty</th>	
					<th>Vat@25%	</th>
					<th>TCS @1%</th>	
					<th>Net Amount</th>	
					<th>Narration</th>	
					<th>Voucher Type Name</th>	
					<th>Sales Ledger</th>	
					<th>Ward No </th>
					<th>Ward Name</th>	
					<th>Consginee Address</th></tr>	
					</thead>
						<tbody id="tallyreport"  >
								</tbody>
				</table>
				</div>
				</div>


                     



				
			</div>
		</div>
		<div class="w3-center w3-margin-top">
			<form name="tally_data" action="download_tally_data.php" method="POST">
				<input type="hidden" name="table_rows" id="table_rows" value="">
				<button type="button" class="w3-red" onclick="getTallyfile()"> SUMMARY</button>
			</form>
		     <!-- <a href="salereportsummary.php" class="w3-red">SUMMARY</a> -->
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
	 
const tallyreport = () =>{
 
	
			var url = 'tallydata.php?tallyreport=tallyreport&startdate='+startdate.value+'&enddate='+enddate.value;
			//  console.log(url);
			fetch(url).then(data=>data.text()).then(data=>{

				// console.log(data);
				document.getElementById('tallyreport').innerHTML=data;
				
				
			});

}
		function getTallyfile(){
			var data=document.getElementById('table_div').innerHTML;
			document.getElementById('table_rows').value=data;
			document.getElementsByName('tally_data')[0].submit();
		}


	</script>
</body>
</html>

