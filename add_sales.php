<?php 
session_start();
extract($_POST);

include 'includes/autoload.inc.php';
include 'includes/connect.php';
	if($conn){
  $sql = "IF EXISTS (SELECT * from POPS_SALE_MAN where  POPS_SALE_MAN_PK='$pk')
            BEGIN
            UPDATE POPS_SALE_MAN SET
            [SALE_MAN_NAME]='$Name'
            ,[PHONE_NO]='$phone'
           ,[EMAIL]='$email'
           ,[STATUS]='$status'
                       
           WHERE POPS_SALE_MAN_PK='$pk'
           END
           ELSE
           BEGIN 
           INSERT INTO [dbo].[POPS_SALE_MAN]
           ([SALE_MAN_NAME]
            ,[PHONE_NO]
            ,[EMAIL]
            ,[STATUS])

   VALUES('$Name','$phone','$email','$status') 
   END";
// exit;
   $stmt = sqlsrv_query($conn, $sql);

   if($stmt == false){
    echo '<script type="text/javascript">alert("Something Went Wrong!! Please try Again.")</script>';
    header('Location: Sales_man_creation.php?error=not-added');
  }
  else{
    
    header('Location: Sales_man_creation.php');
  }
  
  }
  



 ?>