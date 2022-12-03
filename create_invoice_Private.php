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
              
              
          
		  $insert = 0;
		  $invalidfile = false;
		  $length= count($lines);
              for ($i = 0; $i < count ($lines); $i++){
				$exprow=[];
              $exprow = explode (",", $lines[$i]);			
			$PO_NO = $exprow[1];
			$VEND_CODE = $exprow[2];
			$BRAND_CODE = preg_replace("/[^a-zA-Z0-9&\-]/", '', $exprow[3]);
			$CASE = $exprow[4];
			$BOTTLE = $exprow[5];
			$TP_NO = $exprow[10];

			$sql = "INSERT INTO [dbo].[POPS_PO_CREATE]
			([PO_NO]
			,[VEND_CODE]
			,[BRAND_CODE]
			,[CASE]
			,[BOTTLE]
			,[TP_NO]
			,[CREATED_BY]
			,[UPDATED_BY])
	  VALUES
			('$PO_NO','$VEND_CODE','$BRAND_CODE','$CASE','$BOTTLE','$TP_NO','$USER','$USER')";
			$stmt = sqlsrv_query($conn,$sql);
			if($stmt != false){
				$insert++;
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
	<title>PO Creation</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style>
		select{
			width: 80%;
		}
		input,select, button{
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
				<div class="w3-col l3">
					<h3>Generation Invoice</h3>
					</div>
					<div class="w3-col l3 w3-padding">
					<select id='vend_type' onchange="window.location.href='create_invoice.php'" >
						<option value="Corporation" >Corporation</option>
						<option value="Private" selected>Private</option>
					</select>
					</div>
					<div class="w3-col l2 w3-padding">
						<button class="w3-button w3-round w3-red "  onclick="window.location.href='Po.php'">GoTo Generation PO  </button>
						
					</div>
		</div>
               



			<div class="w3-col l12" style="max-height: 200px;  overflow:auto">
					<div class="w3-border w3-border-grey">
					<table class="w3-table" border="1">
					<thead>
						<tr><th>SNo</th><th>Inv_No</th><th>Inv_Date</th><th>PO_N0</th><th>PO_Date</th><th>TP_NO</th><th>Vend Code</th><th>Vend Name</th><th>Case</th><th>Total</th><th style="text-align: left !important; padding-left: 10px !important; width: 100px;">  Action </th></tr>	
					</thead>
						<tbody id="show_item"  >
								</tbody>
				</table>
				</div>
				</div>
				
			</div>
		</div>
	</div>
</div>
	</div>
	<?php include 'includes/footer.php'; ?>
	<script type="text/javascript">



		const Show_item_list = () =>{
			var url = 'update-brand.php?list_item=Show_item_list';
			 // console.log(url)
			fetch(url).then(data=>data.text()).then(data=>{
				document.getElementById('show_item').innerHTML=data;

				console.log(data);
			})
		}
		Show_item_list();
		
		
		

		const GenerateInvoice = (tp)=>{
			// console.log(tp);
			var url = 'update-brand.php?fun=GenerateInvoice&tp_num='+tp;
			// console.log(url);
			
			fetch(url).then(data=>data.text()).then(data=>{
				// document.getElementById('item_body').innerHTML=data;
				alert(data);
				window.location.reload();
			})
		}

		const printInvoice = (tp)=>{
			// console.log(invoice);
			var url = 'print_tp_invoice.php?fun=printInvoice&tp_num='+tp;
			console.log(url);
			window.open(url,'_blank');
			//var url = 'update-brand.php?fun=printInvoice1&invoice='+tp;
			fetch(url).then(data=>data.text()).then(data=>{
				// document.getElementById('item_body').innerHTML=data;
				// alert(data);
				window.location.reload();
			});
		}




		
		
  vend_type=document.getElementById('vend_type').value;
         console.log(vend_type);




	</script>
</body>
</html>

