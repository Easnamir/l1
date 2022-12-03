<?php 
	session_start();
	  
	
  $user = $_SESSION["username"];
  $COMPANY_id = $_SESSION['COMPANY_id'];
	extract($_POST);

	include 'includes/autoload.inc.php';
include 'includes/connect.php';
	if($conn){
   
   $sql="INSERT INTO [dbo].[POPS_CUSTOMER_DETAILS]
           (CUSTOMER_CODE
            ,[LICENCE_CODE]
           ,[BUSINESS_NAME]
           ,[CONTACT_NO]
           ,[BUSINESS_ADDRESS]
           ,[PAN_NO]
           ,[PIN_NO]
           ,[OWNER]
           ,[TIN_NO]
           ,[DESCRIPTION]
           ,[EMAIL]
           ,[CREATED_BY]
           ,[UPDATED_BY]
           ,[CUSTOMER_STATUS]
           ,[COMPANY_DETAILS_FK]
           ,[COMPANY_NAME])
              VALUES(

              '$Customer_Code'
              ,'$licencecode'
                      ,'$Business_Name'
                      ,'$phone'
                      ,'$address'
                      ,'$pan_number'
                      ,'$pin'
                      ,'$Owner_name'
                      ,'$tinno'
                      ,'$description'
                      ,'$email'
                      ,'$user'
                      ,'$user'
                      ,'0'
                      ,'$COMPANY_id'
                      ,'$companyname'
              )";
// exit;
	
     	$stmt = sqlsrv_query($conn, $sql);
     	if($stmt === false){
      header('Location: HCR_Customer_Creation.php?status=not-added');
     }
     else{
      header('Location:HCR_Customer_Creation.php?status=sup-added');
     }
	}
  
 ?>