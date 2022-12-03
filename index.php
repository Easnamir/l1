<?php
session_start();
include 'includes/session_company.php';
include 'includes/autoload.inc.php';
include 'includes/connect.php';
// var_dump($database);
// exit;
if(!isset($_SESSION['COMPANY_id'])){
	
	header("Location: logout.php");
}
else{
	// echo $_SESSION['zone'];
	$COMPANY_id= $_SESSION['COMPANY_id'];
}


$sql="delete a from POPS_PANDING_PO_CREATE a
join POPS_PRICE_MASTER b on a.BRAND_CODE=b.BRAND_CODE
where CONVERT (DATE,(SUBSTRING(PO_NO,7,2))+(SUBSTRING(PO_NO,5,2)) +(SUBSTRING(PO_NO,3,2)) )<= GETDATE()-8 and LIQUOR_TYPE_CD=172

 delete a from POPS_PANDING_PO_CREATE a
join POPS_PRICE_MASTER b on a.BRAND_CODE=b.BRAND_CODE
where CONVERT (DATE,(SUBSTRING(PO_NO,7,2))+(SUBSTRING(PO_NO,5,2)) +(SUBSTRING(PO_NO,3,2)) )<= GETDATE()-4 and LIQUOR_TYPE_CD=171";

$stmt1 = sqlsrv_query($conn,$sql);
if($stmt1 != false){
	echo "";
}


?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Company Admin</title>
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css" rel="stylesheet">
		label{
			display: block;
			width: 100%;
		}
		
		input{
			display: inline-block !important;
			padding: 0;
			
		}
		.w3-table th{
			border: 1px solid #ccc;
		}
		.w3-modal-content{
			width: 60% !important;
		}
		.w3-modal{
			padding-top: 10px !important;
		}
		.w3-modal input{
			width: 100% !important;
		}
		/*#product_table, #report-div{
			max-height: 300px !important;
			overflow: auto;
		}*/
		table tr th, table tr td{
			font-size: 10px;
			padding: 0;
			margin: 0;
			text-align: center;
			border: 1px solid #ccc;
		}
		.w3-table td, .w3-table th, .w3-table-all td, .w3-table-all th {
			text-align: center;
			padding: 2px;
		}
		
		/*select#shop, select#sel_report{
			height: 35px !important;
		}*/

		input, button, .w3-select{
			height: 25px;
		}
		.w3-button-report{
			padding: 0 5px;
		}
		#report_div{
			display: none;
			position: relative;
		}
		th{
			/*background-color: #9f221b !important;*/
			color: white;
			padding: 2px 10px;
			position: sticky;
			top: 0;
			vertical-align: middle;
		}
	</style>
</head>
<body>

<div class="">
	
<?php
	include 'includes/header_company.php';
	?>

	<div class="w3-col l2">
		<?php
			include 'includes/left-side-menu.php';
		?>

	</div>
	<div class="w3-col l10 ">
			
				
           <div class='w3-col l12'>
           
           	
				</div>
					
	
</div>
<?php
	include 'includes/footer.php';
?>
</div>
</body>
</html>