<?php 
session_start();
include 'includes/autoload.inc.php';
include 'includes/connect.php';
$current_date = date("Y-m-d H:i:s");
$cid = $_SESSION['CINV'];
$invoice=10001;

if(isset($_POST['item_data'])){
    extract($_POST);
    $data_array=json_decode(stripslashes($item_data));   
     $sqli="select isnull(INVOICE_NUMBER,0)+1 INVOICE_NUMBER from POPS_PROFORMA_INVOICE where pid in ( SELECT max(pid) from POPS_PROFORMA_INVOICE)

     group by INVOICE_NUMBER";
    $stmti = sqlsrv_query($conn,$sqli);
    while($rowi=sqlsrv_fetch_array($stmti,SQLSRV_FETCH_ASSOC)){
         $invoice = $rowi['INVOICE_NUMBER'];
    //    exit;
    }
    // $customer_name
    // echo $brac_num = substr_count($customer_name,'(');
    // exit;
     $customer_num = explode(')',end(explode('(',$customer_name)))[0];
     $item_arr = json_decode($item_data);
    //  var_dump($item_arr);
    //  exit;
    $count=0;
    foreach($item_arr as $row){
        $brand_name = $row->BRAND_NAME;
        $brand_code = $row->BRAND_CODE;
        $size = $row->SIZE_VALUE;
        $pack = $row->PACK_SIZE;
        $wsp = $row->WSP;
        $qty = $row->QUANTITY;

        $custom = $row->CUSTOM_DUTY;
        $excise = $row->EXCISE_PRICE;
        $vat = $row->SALE_TAX;
        $mrp = $row->MRP;


     $sql = "INSERT INTO [POPS_PROFORMA_INVOICE]
     ([VEND_NAME]
     ,[PROFORMA_DATE]
     ,[INVOICE_NUMBER]
     ,[CREATED_BY]
     ,[CREATED_DATE]
     ,[BRAND_NAME]
     ,[BRAND_CODE]
     ,[SIZE_VALUE]
     ,[MRP]
     ,[WSP]
     ,[EXCISE_DUTY]
     ,[CUSTOM_DUTY]
     ,[VAT]
     ,[QUANTITY]
     ,[VEND_CODE]
     ,[DISCOUNT]
     ,[IS_DUTY_FREE])
     VALUES('$customer_name','$proforma_date','$invoice','Admin',getdate(),'$brand_name','$brand_code','$size','$mrp','$wsp','$excise','$custom','$vat','$qty','$customer_num','$discount','$duty_free')";
    //  exit;

     $stmt=sqlsrv_query($conn,$sql);
     if($stmt !=false)
     $count++;
     
}
if(count($item_arr) == $count){
    echo "All data Added Successfully";
}
else{
    echo "Something Went wrong!!";
}
}
sqlsrv_close( $conn );
// echo $_SESSION['COMPANY_id'];

 ?>