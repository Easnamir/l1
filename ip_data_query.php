
<?php
session_start();
include 'includes/autoload.inc.php';
include 'includes/connect.php';
$user = $_SESSION["username"];
$COMPANY_id = $_SESSION['COMPANY_id'];
$CINV = $_SESSION['CINV'];

if(date('m')<4){
	$finyear = (date('y')-1).''.date('y');
}
else{
   $finyear = date('y').''.(date('y')+1);
}


// var_dump($_REQUEST);
// exit;
if(isset($_GET['fun']) && $_GET['fun']=='getBrandSize'){
  // var_dump($_GET);
  $data=[];
  $sql = "select * from POPS_PRICE_MASTER";
  $stmt = sqlsrv_query($conn,$sql);
  while($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
    $data[]=$row;
  }
  echo json_encode($data);
}
if(isset($_GET['fun']) && $_GET['fun']=='submitIpData'){
  extract($_REQUEST);
  // var_dump();
  $ipData = json_decode($ip_data);
  foreach($ipData as $val){
    // var_dump($val);
    $status = $val->STATUS_CD;
    $supp_name = $val->SUPP_NAME;
    $catagory = $val->CATEGORY_CD;
    $type = $val->LIQUOR_TYPE_CD;


    $BRAND_NAME = $val->BRAND_NAME;
    $BRAND_CODE = $val->BRAND_CODE;
    $SIZE_VALUE = $val->SIZE_VALUE;
    $PACK_SIZE = $val->PACK_SIZE;
    $MRP = $val->MRP;
    $CASE_QTY = $val->QUANTITY;
    $BOTTLE_QTY = $val->QUANTITY*$val->PACK_SIZE;
    // $type = $val->LIQUOR_TYPE_CD;
     $sql = "INSERT INTO [dbo].[POPS_IP_RECEIVE]
    ([IP_NO]
    ,[STATUS_CD]
    ,[IP_DATE]
    ,[RECEIVE_DATE]
    ,[CREATED_BY]
    ,[CREATED_DATE]
    ,[UPDATED_BY]
    ,[UPDATED_DATE]
    ,[S_NO]
    ,[INVOICE_NO]
    ,[SUPP_NAME]
    ,[CATEGORY_CD]
    ,[BRAND_NAME]
    ,[BRAND_CODE]
    ,[SIZE_VALUE]
    ,[PACK_SIZE]
    ,[MRP]
    ,[LIQUOR_TYPE_CD]
    ,[CASE_QTY]
    ,[BOTTLE_QTY])
VALUES('$ip_num','$status','$ip_date','$receive_date','$user',GETDATE(),'$user',GETDATE(),'$slno','$invoice_id','$supp_name','$catagory','$BRAND_NAME','$BRAND_CODE','$SIZE_VALUE','$PACK_SIZE','$MRP','$type','$CASE_QTY','$BOTTLE_QTY')";
$stmt = sqlsrv_query($conn,$sql);
if($stmt == false){
  echo "Something went wrong";
  exit;
}

  }
  echo "All data submitted succesfully!";

}
?>
