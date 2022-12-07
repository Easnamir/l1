<?php 
session_start();

$user = $_SESSION["username"];
$COMPANY_id = $_SESSION['COMPANY_id'];
$CINV = $_SESSION['CINV'];
include 'includes/autoload.inc.php';
include 'includes/connect.php';
if(date('m')<4){
	$finyear = (date('y')-1).''.date('y');
}
else{
   $finyear = date('y').''.(date('y')+1);
}
if(isset($_GET['fun']) && $_GET['fun'] == 'checkTpstatus'){
  extract($_GET);
   $sql = "SELECT distinct A.TP_NO,STATUS,A.CHALLAN_NO,A.VEND_CODE,a.SUPPLY_DATE,B.REMARK_CHALLAN,b.STATUS_CHALLAN FROM POPS_DISPATCH_ITEMS A left join POPS_CHALLAN_STATUS B on A.TP_NO=B.TP_NO where A.TP_NO = '$tp_num' and A.CHALLAN_NO is not null";
   $stmt = sqlsrv_query($conn,$sql);
   $data = [];
   while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
    $data[] = $row;
   }
   echo json_encode($data);
}

if(isset($_GET['fun']) && $_GET['fun'] == 'checkTpstatusTP'){
   extract($_GET);
   $sql = "SELECT distinct A.TP_NO,STATUS,A.CHALLAN_NO,A.VEND_CODE,a.SUPPLY_DATE,B.MANUAL_STATUS FROM POPS_DISPATCH_ITEMS A left join POPS_TP_STATUS_DETAILS B on A.TP_NO=B.TP_NUMBER where A.TP_NO = '$tp_num'";
    $stmt = sqlsrv_query($conn,$sql);
    $data = [];
    while ($row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
     $data[] = $row;
    }
    echo json_encode($data);
 }
// 

if(isset($_GET['fun']) && $_GET['fun'] == 'updateChallanStatus'){
   $content = trim(file_get_contents("php://input"));

  $decoded = json_decode($content, true);
  extract($decoded);

  
   $sql ="IF EXISTS (SELECT * FROM POPS_CHALLAN_STATUS WHERE TP_NO='$tp_no')
  BEGIN
  UPDATE POPS_CHALLAN_STATUS SET STATUS_CHALLAN='$challan_status',REMARK_CHALLAN='$remark',UPDATED_BY='$user',UPDATED_DATE=GETDATE() WHERE TP_NO='$tp_no'
  End
  ELSE
  BEGIN
  INSERT INTO [dbo].[POPS_CHALLAN_STATUS]
     ([VEND_CODE]
     ,[CHALLAN_DATE]
     ,[TP_NO]
     ,[CREATED_BY]
     ,[CREATED_DATE]
     ,[CHALLAN_NO]
     ,[STATUS_CHALLAN]
     ,[REMARK_CHALLAN])
  VALUES('$vend_id','$challan_date','$tp_no','$user',getdate(),'$pk_id','$challan_status','$remark')
  END";
//   exit;
$stmt = sqlsrv_query($conn,$sql);
if($stmt !== false){
 echo "Status updated Successfully";
}
else{
 echo "Something Failed!";
}

}

if(isset($_GET['fun']) && $_GET['fun'] == 'updateTPStatus'){
   $content = trim(file_get_contents("php://input"));

  $decoded = json_decode($content, true);
  extract($decoded);

//   exit;
   $sql = "UPDATE [dbo].[POPS_TP_STATUS_DETAILS] set UPDATE_BY='$user',UPDATED_DATE=getdate(),MANUAL_STATUS='$challan_status' where TP_NUMBER='$tp_no'";
//   exit;
  $stmt = sqlsrv_query($conn,$sql);
  if($stmt!=false){
     echo "Status Updated Successfully";
  }
  else{
     echo "Something went wrong. Please try again";
  }

}