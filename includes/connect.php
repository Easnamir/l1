<?php 
session_start();
$database = $_SESSION['database'];
// var_dump($databse);
// exit;
$obj = new Dbh($database);
$conn = $obj->connect();
?>