<?php 
include 'includes/session_company.php';
include 'includes/autoload.inc.php';
include 'includes/connect.php';
require 'vendor/autoload.php';
use PhpOffice\PhpSpreadsheet\Spreadsheet;
// use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
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

  $sql = "SELECT CN_NO,S_NO FROM POPS_CN_DETAILS WHERE S_NO=(SELECT MAX(S_NO) from POPS_CN_DETAILS where CN_NO LIKE 'CN/$finyear/%')";
// exit;
	$stmt = sqlsrv_query($conn, $sql);
	if($stmt){
	while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
	 $slno = $row['CN_NO'];
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

if(isset($_POST['submit'])) {
	// var_dump($_FILES);
	// exit;
$filename = $_FILES['cn_file']['name'];

		$target_dir = "excels/";
		$file_break = explode('.',$filename);
		$extension =  end($file_break);
		
		if(!($extension=='xls' || $extension=='xlsx' || $extension=='csv')){
			?>
			<script type="text/javascript">
				alert("Please Select properly formated Excel only");
			</script>
			<?php
		}
		else{
			move_uploaded_file($_FILES["cn_file"]["tmp_name"], $target_dir . $filename);
			$inputFileName = "./excels/$filename";
			
			// var_dump($inputFileName);
			$inputFileType = \PhpOffice\PhpSpreadsheet\IOFactory::identify($inputFileName);

			/**  Create a new Reader of the type that has been identified  **/
			$reader = \PhpOffice\PhpSpreadsheet\IOFactory::createReader($inputFileType);

			/**  Load $inputFileName to a Spreadsheet Object  **/
			$spreadsheet = $reader->load($inputFileName);
			$schdeules = $spreadsheet->getActiveSheet()->toArray();
			$arr_size=count($schdeules);
			$row_num = count($schdeules[0]);
			if((($row_num != 8 && $row_num != 7) || $schdeules[0][0]==' ')){

				?>
				<script type="text/javascript">
					alert("Please check excel format. Its not according to prescribed format!!");
					window.location.href='cn_creation.php';
					// return false;
				</script>
				<?php

			}
			$inserted=0;
								
								for($i=1;$i<=$arr_size;$i++){
									if($schdeules[$i][0]==''){
										break;
									}
								$cndate_arr = explode('/',$schdeules[$i][0]);
							
								// var_dump($cndate_arr);
								
							   $cndate = $cndate_arr[2].'-'.str_pad($cndate_arr[1],2,0,STR_PAD_LEFT).'-'.str_pad($cndate_arr[0],2,0,STR_PAD_LEFT);
								//  echo $cndate;
								$cnmonth_arr = explode('/',$schdeules[$i][4]);
								// var_dump($cnmonth_arr);
								// echo "<br>";
								$cnmonth = $cnmonth_arr[2].'-'.str_pad($cnmonth_arr[0],2,0,STR_PAD_LEFT).'-'.str_pad($cnmonth_arr[1],2,0,STR_PAD_LEFT);
								// echo $cnmonth ;
								// exit;
								$vanderId = $schdeules[$i][1];
								$Reference = $schdeules[$i][3];
								$value = $schdeules[$i][5];
								$Narration = $schdeules[$i][6];

								$sqlv = "select VEND_ADDRESS,VEND_NAME,DEPARTMENT from POPS_VEND_DETAILS where VEND_CODE='$vanderId'";
								$stmtv = sqlsrv_query($conn,$sqlv);
								while ($row = sqlsrv_fetch_array($stmtv, SQLSRV_FETCH_ASSOC)){
									$shopName = $row['VEND_NAME'];
									$address = $row['VEND_ADDRESS'];
									$Department = $row['DEPARTMENT'];
								}
								 $invoice_id = "CN/$finyear/$slno";
								// echo "<br>";
								


								$sql = "INSERT INTO [dbo].[POPS_CN_DETAILS]
           ([VEND_CODE]
      ,[VEND_NAME]
      ,[VEND_ADDRESS]
      ,[CREATED_BY]
      ,[UPDATED_BY]
      ,[DEPARTMENT]
      ,[CN_NO]
      ,[S_NO]
      ,[MONTH]
      ,[CN_DATE]
      ,[Reference]
      ,[value]
      ,[Narration])

   VALUES('$vanderId','$shopName','$address','SYSTEM','SYSTEM','$Department','$invoice_id','$slno','$cnmonth','$cndate','$Reference','$value','$Narration') ";
// exit;
   $stmt = sqlsrv_query($conn, $sql);
	 if($stmt !== false){
		$slno++;
		$inserted++;
	 }
	}							
			// =count($schdeules);
			if($arr_size-1 == $inserted)	{
				?>
	<script>
		alert("CN Updated");
		window.location.href='cn_creation.php';
	</script>
				<?php
			}
			else{
				?>
		<script>
			console.log(<?= $inserted; ?>)
		</script>
				<?php
			}
	}

			
}
// exit;
?>
<!DOCTYPE html>
<html>
<head>
	<title>Credit Note</title>
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
				<h3 class="w3-left">Credit Note</h3>
				</div>
				<div class="w3-col l5" style="padding: 5px">
					
					<form action="" method="POST" enctype="multipart/form-data">
						<input type="file" name="cn_file" id="cn_file" style="height: 30px; " required=''>
						<button type="submit" name="submit" class="w3-button w3-round w3-red " >Upload</button>
					</form>
				</div>
				<div class="<?php echo $class; ?>">
		         <?php echo $msg; ?></div>
				<form name="adduser" action="add_cn.php" method="POST">
					<div class="w3-col l12 w3-border w3-border-grey w3-margin-bottom " style="margin-bottom: 3px!important; padding-bottom:10px">
						<input type="hidden" name="pk" value="<?php echo $shop['CN_DETAILS_PK']; ?>">
						<input class="w3-input w3-border formVal" type="hidden" id="slno" name="slno" required="" maxlength="14" value="<?php echo $slno ?>">
                        <div class="w3-col l3 w3-padding-small">
							<label>Credit Note No</label>
                     <input class="w3-input w3-border formVal" id="invoice_id" type="text" name="invoice_id" required="" value="<?php echo "CN/".$finyear."/".$slno ?>">

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
							<input class="w3-input w3-border"  placeholder="Enter Reference" autocomplete="off"type="text" name="Reference" required="" value="<?php echo $shop['PIN_CODE']; ?>">
						</div>
						
						<div class="w3-col l7 w3-padding-small">
							<label>Narration<span class="w3-text-red">*</span></label>
							<input class="w3-input w3-border"  placeholder="Enter Narration" autocomplete="on"type="text" name="Narration" required="" value="<?php echo $shop['PAN_NO']; ?>" >
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
	// console.log(url);
	fetch(url).then(data=>data.json()).then(data=>{
		console.log(data);
		if(data.length>0){
			document.getElementById('vendName').value=data[0].VEND_NAME
			document.getElementById('vendAddress').value=data[0].VEND_ADDRESS
			document.getElementById('department').value=data[0].DEPARTMENT
			document.getElementById('vanderId').value=data[0].VEND_CODE

		}	
	})
 })

 vend.addEventListener('dblclick',function(){this.value=''})


 

	</script>
</body>



</html>