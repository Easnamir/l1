<?php 
extract($_POST);
include 'includes/autoload.inc.php';
// var_dump($_POST);
session_start();
// exit;
$obj = new Dbh($security_code);
$conn = $obj->connect();

$_SESSION['database']=$security_code;
function audit_log($username,$privilege,$zone,$shop_id,$shop_name){
	$ip = $_SERVER['REMOTE_ADDR'];
	// $browser = urlencode($_SERVER['HTTP_USER_AGENT']);
	$browser='';
if((strpos($_SERVER['HTTP_USER_AGENT'],'Chrome')!==false)){
    $browser = 'Chrome';
}
else if((strpos($_SERVER['HTTP_USER_AGENT'],'MSIE')!==false) || (strpos($_SERVER['HTTP_USER_AGENT'],'Trident')!==false)){
    $browser = 'Internet Explorer';
}
else if((strpos($_SERVER['HTTP_USER_AGENT'],'Safari')!==false)){
    $browser ='Safari';
}
else if((strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')!==false)){
    $browser = 'Firefox';
}
else{
    $browser = urlencode($_SERVER['HTTP_USER_AGENT']);
}
	
	$sqla = "INSERT INTO POPS_AUDIT_TRAIL([USERNAME]           
           ,[IP_ADDRESS]
           ,[LOGIN_TIME]
           ,[BROWSER_DETAILS]
           ,[USER_PRIVILEGE]
           ,[OTHER_DETAILS]
           ,[SHOP_ID]
           ,[SHOP_NAME]
           ,[ZONE])
	VALUES('$username','$ip',CURRENT_TIMESTAMP,'$browser','$privilege','','$shop_id','$shop_name','$zone')";
	 return $stmta = sqlsrv_query($conn,$sqla);
	// return $sqla;
}
$sql="SELECT * FROM POPS_USER_DETAILS AS A JOIN  POPS_COMPANY_DETAILS cd
on a.SHOP_KEY_FK=cd.POPS_COMPANY_DETAILS_PK 
WHERE A.USER_ID='$username' AND A.PASSWORD='$password' AND cd.COMPANY_CODE='$company_code' and (a.PRIVILEGE='COMPANY_ADMIN' OR a.PRIVILEGE='REPORT')";

// exit;
	
	$stmt = sqlsrv_query($conn, $sql);
	if($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
		
		// $shop_id = $row['SHOP_DETAILS_PK'];		
		$_SESSION["COMPANY_NAME"] = $row['COMPANY_NAME'];
		$_SESSION["COMPANY_OWNER"] = $row['COMPANY_OWNER'];
		$_SESSION['ADDRESS'] = $row['COMPANY_REGISTERED_ADDRESS'];
		$_SESSION['GST_NUMBER'] = $row['GST_NUMBER'];
		$_SESSION['LICENCE_NUMBER'] = $row['LICENCE_NUMBER'];
		$_SESSION['COMPANY_id'] = $row['POPS_COMPANY_DETAILS_PK'];
		$_SESSION["privilege"] = $row['PRIVILEGE'];
		$_SESSION['TIN'] = $row['TIN'];
		$_SESSION['PIN_CODE'] = $row['PIN'];
		$_SESSION['PAN'] = $row['PAN'];
		$_SESSION['CIN'] = $row['CIN'];
		$_SESSION['EMAIL'] = $row['EMAIL'];
		$_SESSION['PHONE'] = $row['PHONE'];
		$_SESSION['CINV'] = $row['CINV'];
		$_SESSION['timestamp'] = time();
		$_SESSION["username"] = $username;
		$login_time = date('Y-m-d H:i:s');
		// $status =audit_log($username,$row['PRIVILEGE'],'Çompany',$row['POPS_COMPANY_DETAILS_PK'],$row['COMPANY_NAME']);
	?>
<script type="text/javascript">
	
	window.location.href='./';
</script>

<?php
	}
	else{
		header("Location: login.php?err=wrong_uname");
	}

