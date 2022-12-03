<?php 
include 'includes/session.php';
 ?>
<!DOCTYPE html>
<html>
<head>

	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Home</title>
	
	<link rel="icon" type="image/png" sizes="32x32" href="/favicon-32x32.png">
	
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
	<link rel="stylesheet" href="css/w3.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<style type="text/css">
		label{
			display: inline-block !important;
			margin-right: 10px;
			
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
		#product_table{
			max-height: 300px !important;
			overflow: auto;
		}
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
		select{
			width: 60%;
		}
		input,select, button{
			height: 25px;
		}
		.w3-button-report{
			padding: 5px;
		}
		#report_div{
			display: none;
			position: relative;
		}
		th{
			background-color: #9f221b !important;
			color: white;
			padding: 2px 10px;
			position: sticky;
			top: 0;
			vertical-align: middle;
		}
	</style>
</head>
<body>
	<?php
		include 'includes/header.php'
	?>
	<div class="body-content w3-white">

			
<!-- <h3 class="w3-card w3-teal w3-padding w3-padding-16">Welcome >> Dashboard</h3> -->
<div class="w3-center w3-teal" id="last_batch"></div>
</div>
<?php
	include 'includes/footer.php';
?>
<script type="text/javascript" src="js/issue.js"></script>

</body>
</html>