<?php
	session_start();

	if(time() - $_SESSION['timestamp'] > 46000) { //subtract new timestamp from the old one
    //echo"<script>alert('15 Minutes over!');</script>";
    //unset($_SESSION['username'], $_SESSION['password'], $_SESSION['timestamp']);
    header("Location: logout.php"); //redirect to index.php
    exit;
} else {
    $_SESSION['timestamp'] = time(); //set new timestamp
}

	if(!isset($_SESSION["COMPANY_id"])){
		header("Location: logout.php");
		exit;
	}
		$COMPANY_NAME= $_SESSION["COMPANY_NAME"];
		$COMPANY_OWNER= $_SESSION["COMPANY_OWNER"];
		$ADDRESS = $_SESSION['ADDRESS'];
		$PIN=$_SESSION['PIN_CODE'];
		$GST_NUMBER=$_SESSION['GST_NUMBER'];
		$LICENCE_NUMBER = $_SESSION['LICENCE_NUMBER'];
		$COMPANY_id = $_SESSION['COMPANY_id'];
		$privilege=$_SESSION["privilege"];
		$database = $_SESSION['database'];
?>