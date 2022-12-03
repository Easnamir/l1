<?php 
include 'includes/session_company.php';
include 'includes/autoload.inc.php';
include 'includes/connect.php';
require 'vendor/autoload.php';
// use PhpOffice\PhpSpreadsheet\Spreadsheet;
// // use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
// use PhpOffice\PhpSpreadsheet\IOFactory;
// use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
$msg = "";
$class="";
$schdeules=[];
$arr_size=count($schdeules);
$class="";
$slno =1;
$vendid = $_SESSION['CINV'];
if(date('m-d')<'04-02'){
			 $finyear = (date('y')-1).'-'.date('y');
		}
		else{
			$finyear = date('y').'-'.(date('y')+1);
		}

  $sql = "SELECT DN_NO,S_NO FROM POPS_DN_DETAILS WHERE S_NO=(SELECT MAX(S_NO) from POPS_DN_DETAILS where DN_NO LIKE 'DN/$finyear/%')";
// exit;
	$stmt = sqlsrv_query($conn, $sql);
	if($stmt){
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
	 $slno = $row['DN_NO'];
		$inv = explode('/',$slno);
		// var_dump($inv);
		// exit;
		if($finyear==$inv[1]){
			$slno=$row['S_NO']+1;
		}
		else{
			$slno=1;
		}
	}
}

?>
<!DOCTYPE html>
<html>
<head>
	<title>Debit Note</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style rel="stylesheet" >
		input{
			height: 18px;
		}
		select{
			height: 18px;
			padding: 0;
			font-size: 11px;
			line-height: 20px;
		}
		table tr th{
			padding: 0px !important;
		
			color: white;
		}
		#product_table table {
    width: 100% !important;
}


	</style>
</head>
<body>
	<?php
	include 'includes/header_company.php';
	?>
			<div class="w3-container ">

			<div class="w3-col l1">&nbsp;</div>
			<div class="container w3-col l10">
				<div class="w3-col l7">
				<h3 class="w3-left">Debit Note</h3>
				</div>
				
				<div class="<?php echo $class; ?>">
		         <?php echo $msg; ?></div>
				<form name="adduser" action="add_dn.php" method="POST">
					<div class="w3-col l12 w3-border w3-border-grey w3-margin-bottom " style="margin-bottom: 3px!important; padding-bottom:10px">
						<input type="hidden" name="pk" value="<?php echo $shop['DN_DETAILS_PK']; ?>">
						<input class="w3-input w3-border formVal" type="hidden" id="slno" name="slno" required="" maxlength="14" value="<?php echo $slno ?>">
                        <div class="w3-col l3 w3-padding-small">
							<label>Debit Note No</label>
                     <input class="w3-input w3-border formVal" id="invoice_id" type="text" name="invoice_id" required="" value="<?php echo "DN/".$finyear."/".$slno ?>">

                                 </div>

                       <div class="w3-col l3 w3-padding-small">
							<label> For Month </label>
							<input class="w3-input w3-border formVal" id="month" max="<?php echo date('Y-m-d');?>" type="date" name="month" value="<?php echo date('Y-m-d');?>">							
						</div>

						<div class="w3-col l3 w3-padding-small">
							<label> Date</label>
							<input class="w3-input w3-border formVal" id="date" max="<?php echo date('Y-m-d');?>" type="date" name="date" value="<?php echo date('Y-m-d');?>">							
						</div>

                           <div class="w3-col l3 w3-padding-small">

							<label>Department<span class="w3-text-red">*</span></label>
              <input class="w3-input w3-border" id="department" placeholder="Department" readonly name="department" value="">


						</div>
    
						<div class="w3-row">
                            <!-- <div class="w3-col l3 w3-padding-small"> -->
							<!-- <label>Vend ID<span class="w3-text-red">*</span></label> -->
							<input class="w3-input w3-border" placeholder="Enter Vend ID" autocomplete="off"  type="hidden" name="vanderId" id="vanderId" required="" value='' >
							
						<!-- </div> -->

						<div class="w3-col l3 w3-padding-small">
							<label>Name<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border" list="vend_code" placeholder="Enter Shop Name" autocomplete="off" type="text" name="shopName" required="" id="vendName" value="" >
							<datalist id="vend_code">
							<?php
							$sqlv = "select distinct  isnull(DEPARTMENT_NAME,VEND_CODE) VEND_CODE,isnull(DEPARTMENT_NAME,VEND_NAME) VEND_NAME,isnull(b.CURRENT_ADDRESS,a.VEND_ADDRESS)VEND_ADDRESS,isnull(b.DEPARTMENT_NAME,a.DEPARTMENT)DEPARTMENT,DEPARTMENT_NAME from POPS_VEND_DETAILS a left join POPS_DEP_DETAILS b 
							on a.DEPARTMENT=b.DEPARTMENT order by DEPARTMENT_NAME desc";
							$stmtv = sqlsrv_query($conn,$sqlv);
							while ($row = sqlsrv_fetch_array($stmtv, SQLSRV_FETCH_ASSOC)){
								echo "<option value='".$row['VEND_NAME']."'>";
							}

							?>

							</datalist>
						</div>
						
						
						<div class="w3-col l6 w3-padding-small">
							<label>Address<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"placeholder="Enter Shop Address" autocomplete="off" type="text" name="address" required="" value="" id="vendAddress" >
						</div>
						<div class="w3-col l3 w3-padding-small">
							<label>Value<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter amount" autocomplete="on"type="number" name="value" required="" value="<?php echo $shop['TIN']; ?>">
						</div>
					</div>

						<div class="w3-row">
						<div class="w3-col l5 w3-padding-small">
							<label>Reference<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Reference" autocomplete="off"type="text" name="Reference" required="" >
						</div>
						
						<div class="w3-col l7 w3-padding-small">
							<label>Narration<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Narration" autocomplete="on"type="text" name="Narration" required="" >
						</div>
					</div>
					<div class="w3-row">
						
					</div>
						<div class="w3-container w3-center ">
						<button class="w3-button w3-round w3-red"  type="submit">Submit</button>
						<button class="w3-button w3-round w3-red" type="button" onclick="location.href='shop_creation.php'">Cancel</button>
						<!-- <button class="w3-button w3-round w3-red" type="button" onclick="location.href='shop_list.php'">Shop List</button> -->
						<!-- <button class="w3-button w3-round w3-red" type="reset">Reset</button> -->
					</div>
						
						
					</div>
							
				</form>

		</div>
		
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">

 var vend=document.getElementById('vendName');

 vend.addEventListener('change',function(){
	// this.value=this.value.replace(/[^a-zA-Z0-9&_\- ]/g, '').toUpperCase();
	let url = 'cndn_query.php?vanderId='+encodeURIComponent(this.value);
	console.log(url);
	fetch(url).then(data=>data.json()).then(data=>{
		console.log(data);
		if(data.length>0){
			document.getElementById('vendName').value=data[0].VEND_NAME
			document.getElementById('vanderId').value=data[0].VEND_CODE
			document.getElementById('vendAddress').value=data[0].VEND_ADDRESS
			document.getElementById('department').value=data[0].DEPARTMENT

		}	
	})
 })

 vend.addEventListener('dblclick',function(){this.value=''})


 

	</script>
</body>



</html>