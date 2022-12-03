<?php 
session_start();
	session_unset();
	session_destroy();
	clearstatcache();
	//echo $_SESSION['username'];
	header("Location: login.php");
 ?>