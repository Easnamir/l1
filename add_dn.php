<?php 
session_start();
extract($_POST);
// var_dump($_POST);
// exit;
include 'includes/autoload.inc.php';
include 'includes/connect.php';
	if($conn){
$sql = "INSERT INTO [dbo].[POPS_DN_DETAILS]
           ([VEND_CODE]
      ,[VEND_NAME]
      ,[VEND_ADDRESS]
      ,[CREATED_BY]
      ,[UPDATED_BY]
      ,[DEPARTMENT]
      ,[DN_NO]
      ,[S_NO]
      ,[MONTH]
      ,[DN_DATE]
      ,[Reference]
      ,[value]
      ,[Narration])

   VALUES('$vanderId','$shopName','$address','SYSTEM','SYSTEM','$department','$invoice_id','$slno','$month','$date','$Reference','$value','$Narration') ";

   $stmt = sqlsrv_query($conn, $sql);

   if($stmt === false){
    echo '<script type="text/javascript">alert("Something Went Wrong!! Please try Again.")</script>';
    header('Location: dn_creation.php?error=not-added');
  }
  else{
     echo '<script type="text/javascript">alert("data updated ")</script>';
    header('Location: dn_creation.php');
  }
  
  }
  



 ?>