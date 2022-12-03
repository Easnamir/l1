<?php 
session_start();
extract($_POST);
include 'includes/autoload.inc.php';
include 'includes/connect.php';
	if($conn){
 $sql = "IF EXISTS (SELECT * from POPS_VEND_DETAILS where VEND_CODE='$vanderId' or VEND_DETAILS_PK='$pk')
            BEGIN
            UPDATE POPS_VEND_DETAILS SET
            [VEND_NAME]='$shopName'
            ,[VEND_ADDRESS]='$address'
           ,[PIN_CODE]='$pinCode'
           ,[ExciseNO]='$exciseNo'
           ,[DEPARTMENT]='$Department'
           ,[TIN]='$tinno'

           ,[PAN_NO]='$panno'
          ,[GST_NO]='$gstno'
          ,[FSSAI]='$FSSAI'
           ,[UPDATED_DATE]=getdate()
               
           WHERE VEND_CODE='$vanderId'  or VEND_DETAILS_PK='$pk'
           END
           ELSE
           BEGIN INSERT INTO [dbo].[POPS_VEND_DETAILS]
           ([VEND_CODE]
           ,[VEND_NAME]
           ,[COMPANY_NAME]
           ,[VEND_ADDRESS]
           ,[PIN_CODE]
           ,[ExciseNO]
           ,[CREATED_BY]
           ,[UPDATED_BY]
           ,[DEPARTMENT]
          
           ,[TIN]
           ,[PAN_NO]
           ,[GST_NO]
           ,[FSSAI]
           )

   VALUES('$vanderId','$shopName','$companyName','$address','$pinCode','$exciseNo','SYSTEM','SYSTEM','$Department','$tinno','$panno','$gstno','$FSSA') 
   END";
// exit;
   $stmt = sqlsrv_query($conn, $sql);

   if($stmt === false){
    echo '<script type="text/javascript">alert("Something Went Wrong!! Please try Again.")</script>';
    header('Location: shop_creation.php?error=not-added');
  }
  else{
    
    header('Location: shop_creation.php');
  }
  
  }
  



 ?>