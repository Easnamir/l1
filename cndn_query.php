<?php 
session_start();
// extract($_POST);
include 'includes/autoload.inc.php';
include 'includes/connect.php';

extract($_REQUEST);
// echo $vanderId;
  $sql = "select distinct isnull(DEPARTMENT_NAME,VEND_CODE) VEND_CODE,isnull(DEPARTMENT_NAME,VEND_NAME) VEND_NAME,isnull(b.CURRENT_ADDRESS,a.VEND_ADDRESS)VEND_ADDRESS,isnull(b.DEPARTMENT_NAME,a.DEPARTMENT)DEPARTMENT from POPS_VEND_DETAILS a left join POPS_DEP_DETAILS b 
on a.DEPARTMENT=b.DEPARTMENT where isnull(DEPARTMENT_NAME,VEND_NAME) = '$vanderId'";
// exit;
$stmt = sqlsrv_query($conn, $sql);
$data = [];
while ( $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC)){
  $data[]=$row;
}
if($vanderId){
echo json_encode($data);
}

if (isset($_GET['printcn']) )
  
  {
   $to_sno=$_GET['to_sno'];
  $from_sno=$_GET['from_sno'];



  $sql = "select * from POPS_CN_DETAILS
where S_NO between  '$from_sno' and '$to_sno'
order by s_no ";

$stmt1 = sqlsrv_query($conn,$sql);
  $i=0;

  while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
    // $datacn[] = $row;
$button = "<button   id='".$row['CN_DETAILS_PK']."' onclick='printcninvoice(this.id)' class='w3-button w3-red'>Print </button>";
echo "<tr><td  class='mid-text'>". ++$i. "</td><td>".$row['CN_NO']."</td><td>".$row['CN_DATE']->format('d-m-Y')."</td><td class='mid-text' >".$row['VEND_CODE']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['Reference']."</td><td class='mid-text' >".$row['MONTH']->format('F,Y')."</td><td class='mid-text' >".($row['value'])."</td><td class='mid-text' >".($row['Narration'])."</td><td class='mid-text'>".$button."</td></tr>";

  }

  // print_r($data);

}
if (isset($_GET['printdn']) )
  
  {
   $to_sno=$_GET['to_sno'];
  $from_sno=$_GET['from_sno'];



  $sql = "select * from POPS_DN_DETAILS
where S_NO between  '$from_sno' and '$to_sno'
order by s_no ";

$stmt1 = sqlsrv_query($conn,$sql);
  $i=0;

  while($row = sqlsrv_fetch_array($stmt1, SQLSRV_FETCH_ASSOC)){
    // $datacn[] = $row;
$button = "<button   id='".$row['DN_DETAILS_PK']."' onclick='printdninvoice(this.id)' class='w3-button w3-red'>Print </button>";
echo "<tr><td  class='mid-text'>". ++$i. "</td><td>".$row['DN_NO']."</td><td>".$row['DN_DATE']->format('d-m-Y')."</td><td class='mid-text' >".$row['VEND_CODE']."</td><td class='mid-text' >".$row['VEND_NAME']."</td><td class='mid-text' >".$row['Reference']."</td><td class='mid-text' >".$row['MONTH']->format('F,Y')."</td><td class='mid-text' >".($row['value'])."</td><td class='mid-text' >".($row['Narration'])."</td><td class='mid-text'>".$button."</td></tr>";

  }

  // print_r($data);

}
?>