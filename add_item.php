<?php 
	session_start();
	$user = $_SESSION["username"];
	// $_SESSION['SHOP_KEY'];
	
extract($_POST);

// var_dump($_POST);
// exit;
  
	$last_login = "";
	$status='';
	$tnx_key="";
	$emp_id="";
	$supervisor_id="";
	include 'includes/autoload.inc.php';
  include 'includes/connect.php';
  $status_cd = 301;
  $date_current = date('Y-m-d H:i:s');
  $last_month = date('Y-m-d H:i:s',strtotime('-1 month'));
  $company_id = $_SESSION['COMPANY_id'];
  $company_name = $_SESSION['COMPANY_NAME'];


   if(isset($submit)){
	if($conn){
  $sql="IF EXISTS (SELECT * from POPS_PRICE_MASTER where COMPANY_DETAILS_FK='$company_id' and BRAND_CODE='$brand_id')
            BEGIN
            UPDATE POPS_PRICE_MASTER SET
            [BRAND_NAME]='$brand_name'
           ,[SIZE_VALUE]='$size_value'
           ,[PACK_SIZE]='$pack_size'
           ,[WSP]='$ws_price'
           ,[EXCISE_PRICE]='$excise_price'
           ,[RETAIL_PROFIT]='$retail_profit'
           ,[SALE_TAX]='$sale_tax'
           ,[MRP]='$retail_price'
           ,[COST]='$cost_price'

           ,[LIQUOR_TYPE_CD]='$category'
           ,[UPDATED_BY]='$user'
           ,[UPDATED_DATE]='$date_current'
           ,[CUSTOM_DUTY]='$custom_duty' 
           ,[SUPP_NAME]='$supp_name'         
           WHERE COMPANY_DETAILS_FK='$company_id' and BRAND_CODE='$brand_id'
           END
           ELSE
           BEGIN
           INSERT INTO [POPS_PRICE_MASTER]
           ([BRAND_CODE]
           ,[GTIN_NO]
           ,[CATEGORY_CD]
           ,[BRAND_NAME]
           ,[SIZE_VALUE]
           ,[PACK_SIZE]
           ,[WSP]
           ,[CUSTOM_DUTY]
           ,[EXCISE_PRICE]
           ,[RETAIL_PROFIT]
           ,[SALE_TAX]
           ,[MRP]
           ,[COST]
           ,[TCS]
           ,[LIQUOR_TYPE_CD]
           ,[CREATED_BY]  
           ,[CREATED_DATE]        
           ,[UPDATED_BY]
           ,[UPDATED_DATE]
           ,[COMPANY_DETAILS_FK]
           ,[SUPP_NAME]
           ,[STATUS_CD]
           )
     VALUES('$brand_id','$gtin_no','$sub_category','$brand_name','$size_value','$pack_size','$ws_price','$custom_duty','$excise_price',
     '$retail_profit','$sale_tax','$retail_price','$cost_price','$tcs','$category','SYSTEM','$date_current','$user','$date_current','$company_id','$supp_name','1')
     END";    
    // exit;
     	$stmt = sqlsrv_query($conn, $sql);
     	if($stmt === false){
      header('Location: item_configuration.php?status=not-added');
     }
     else{
      header('Location: item_configuration.php?status=sup-added');
     }
	}
}

if(isset($_GET['fun']) && $_GET['fun'] == 'getBrandDetails'){
  $brand_id = $_GET['brand_id'];
  // echo $brand_id;
  // EXIT;
  $sql = "SELECT * FROM POPS_PRICE_MASTER WHERE BRAND_CODE = '$brand_id' and COMPANY_DETAILS_FK=$company_id";
  $stmt = sqlsrv_query($conn,$sql);
  // $row = sqlsrv_fetch_array($stmt);
  while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
 echo json_encode($row);
}
}

 ?>