<?php 
session_start();

  // var_dump($_POST);
  // exit;
 extract($_POST);
 $target_dir = "documents/";
 $count= count($_FILES);

 $obj = new Dbh($database);;
 $conn = $obj->connect();

 // $sql = "UPDATE POPS_CUSTOMER_DETAILS SET CUSTOMER_STATUS='$customer_status', "
  echo $sql = "update POPS_CUSTOMER_DETAILS set CUSTOMER_STATUS='$customer_status', BUSINESS_ADDRESS='$address', OWNER='$Owner_name', PAN_NO='$pan_number', EMAIL='$email',TIN_NO='$tin',LICENCE_CODE='$licence',DESCRIPTION='$customer_type',CONTACT_NO='$phone' where CUSTOMER_DETAILS_PK='$customer_pk'";
// exit;
  $stmt = sqlsrv_query($conn,$sql);
  if(!$stmt){
  	header('Location: HCR_Customer_Creation.php?status=not-updated');
  }
  else{
  	header('Location: HCR_Customer_Creation.php?status=updated');
  }
sqlsrv_close( $conn );
 ?>