<?php
	include 'includes/session_company.php';
	$COMPANY_id = $_SESSION['COMPANY_id'];
	
	include 'includes/autoload.inc.php';
include 'includes/connect.php';
	$USER = $_SESSION['username'];

if(isset($_POST['submit'])){
		// var_dump( $_FILES);
		$filename = $_FILES['po_list']['name'];
		// echo $filename;
		$target_dir = "excels/";
		$file_break = explode('.',$filename);
		$extension =  end($file_break);
		$sqlbc = "select distinct BRAND_CODE from POPS_PRICE_MASTER";
		$stmtbc = sqlsrv_query($conn,$sqlbc);
		$bc_array = [];
		while($rowbc = sqlsrv_fetch_array($stmtbc,SQLSRV_FETCH_ASSOC)){
			$bc_array[] = $rowbc['BRAND_CODE'];
		}

		// var_dump($bc_array);
		// exit;
		$brand_found = false;



if(!($extension=='txt')){
			?>
			<script type="text/javascript">
				alert("Please Select properly formated txt only");
			</script>
			<?php
		}
		else{

			move_uploaded_file($_FILES["po_list"]["tmp_name"], $target_dir . $filename);
			$inputFileName = "./excels/$filename";

			$txt_file = $inputFileName;

              $lines = file($txt_file);
       for ($i = 0; $i < count ($lines); $i++){
				$exprow=[];
              $exprow = explode (",", $lines[$i]);
							$BRAND_CODE = preg_replace("/[^a-zA-Z0-9&\-. ]/", '', $exprow[3]);
							if(in_array($BRAND_CODE,$bc_array)){
								$brand_found= true;
								break;
							}	
			 }
            
			 if(!$brand_found){
				?>
					<script>
						alert('No brand found! Please check if PO belongs to this company.');
						window.location.href='pending_po.php';
					</script>
				<?php
				exit;
			 }
          
		  $insert = 0;
		  $invalidfile = false;
		  $length= count($lines);
              for ($i = 0; $i < count ($lines); $i++){
				$exprow=[];
              $exprow = explode (",", $lines[$i]);			
			$PO_NO = $exprow[1];
			$VEND_CODE = $exprow[2];
			$BRAND_CODE = preg_replace("/[^a-zA-Z0-9&\-. ]/", '', $exprow[3]);
			$CASE = $exprow[4];
			$BOTTLE = $exprow[5];
			$TP_NO = $exprow[10];
			$duty = $exprow[11];

			if($PO_NO==$TP_NO){
			$sql = "INSERT INTO [dbo].[POPS_PANDING_PO_CREATE]
			([PO_NO]
			,[VEND_CODE]
			,[BRAND_CODE]
			,[CASE]
			,[BOTTLE]
			,[CREATED_BY]
			,[UPDATED_BY]
			,[DUTY_FREE]
			)
	  VALUES
			('$PO_NO','$VEND_CODE','$BRAND_CODE','$CASE','$BOTTLE','$USER','$USER','$duty')";

			
			$stmt = sqlsrv_query($conn,$sql);
			if($stmt != false){
				$insert++;
			}
		}
	}
	// exit;
	 if($insert == $length){
		?>
		<script type="text/javascript">
					alert("All data inserted successfully!");
					
					// return false;
				</script>
		<?php
	}
	else{
		?>
		<script type="text/javascript">	
		alert("Only <?= $insert; ?> lines inserted successfully!");	
		</script>
		<?php
	}

	}
	unset($_POST);
	}
	
  ?>
<!DOCTYPE html>
<html>
<head>
	<title>PO Pending</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style rel="stylesheet" >
		
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
			<div class="w3-col w3-margin-bottom">
				<div class="w3-col l8">
					<h3> Pending Po Loading</h3>
					</div>
					<div class="w3-col l4 " style="padding-top: 10px; margin-bottom: 2px">
			<form class="w3-form" action="" method="POST"  enctype="multipart/form-data">
				<input type="file"  class="w3-file w3-margin-left" name="po_list" required="" id="po_list">
				<button type="Submit" class="w3-button w3-red w3-padding-small w3-right" name="submit" value="submit">Upload</button>					
			</form>
			</div>
					<div class="w3-col l12 w3-border " style="max-height: 400px; min-height: 200px; overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table w3-bordered w3-striped w3-border w3-hoverable" border="1">
					<thead>
						<tr><th>SNo</th><th>PO Number</th><th>Department</th><th>Vend Code</th><th>Vend Name</th><th>Case</th><th>Bottles</th><th>Bill Amount</th><th>Revenue</th></tr>	
					</thead>
						<tbody id="item_body"  >
						</tbody>
				</table>
				</div>
				</div>			
			</div>
			<div class="w3-center w3-margin-top tohide ">
				<br>
		     <a href="pending_list.php" class="w3-red w3-margin-top">Excel</a>
		     
					</div>

		</div>
	</div>
</div>
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">


const Show_po_list = () =>{
			var url = 'update-brand.php?list__panding_po=list__panding_po';
			//  console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('item_body').innerHTML=data;
			})
		}
		

		// Show_po_list();

		setTimeout(Show_po_list, 500);
		
		

		const Check_po_list=()=>{
			var input = document.getElementsByName('tp_no[]');
			var all = document.getElementById('all_check');
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
		}
		function checkAll(obj){
			// console.log(obj.checked);
			var input = document.getElementsByName('tp_no[]');
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
		}
		function pandingprocessPO(){
			var input = document.getElementsByName('tp_no[]');
			var checkedAny = false;
			var tp_array = [];
			input.forEach(item=>{
				if(item.checked==true){
					checkedAny= true;
					tp_array.push(item.value);
				}
			})
			if(!checkedAny){
				alert('Please select any PO');
				return false;
			}
			console.log(tp_array.length);
			var tp_list = JSON.stringify(tp_array);
			var url = 'update-brand.php?fun=pandingprocessPO&tp_num=' + tp_list;
			
			const xhttp = new XMLHttpRequest();
			xhttp.onload = function() {
				var data = this.responseText;
					if(data.includes("PO processed successfully")) {
					alert(data);
					window.location.reload();
				}
				else{
					   
				// console.log(data);
					   alert(data);
				}
				}
			xhttp.open("POST", url, true);
			xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xhttp.send(tp_list);

		}

		

	</script>
</body>
</html>

