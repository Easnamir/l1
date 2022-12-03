<?php 
class Shop extends Dbhh{
	public function addShop($licensecode, $username, $password,&$arr){
		extract($arr);
		$date = date('Y-m-d H:i:s');
		$sql = "INSERT INTO POPS_SHOP_DETAILS
				   ([SHOP_CODE]
				   ,[SHOP_NAME]
				   ,[COMPANY_NAME]
				   ,[SHOP_ADDRESS]
				   ,[PIN_CODE]
				   ,[TIN_NO]
				   ,[LST_NO]
				   ,[CST_NO]
				   ,[ST_NO]
				   ,[DSN_NO]
				   ,[PHONE_NO]
				   ,[ExciseNO]
				   ,[CREATED_BY]
				   ,[CREATED_DATE]
				   ,[UPDATED_BY]
				   ,[UPDATED_DATE]
				   ,[LICENCE_DTLS_FK]
				   ,[USERNAME]
				   ,[PASSWORD]
				   ,[EMAIL_ID])
				   VALUES(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";
		$stmt = $this->connect()->prepare($sql);
		$exec = $stmt->execute([$vanderId,$shopName,'LOOPINTechies',$address,$pinCode,$tinNo,'',$cstNo,$gstNo,$dnsNo,$phone,$exciseNo,'System',$date,'',$date,$licensecode,$username,$password,$emailid]);
		exit;
		//return $exec;

	}

	public function updateShop(){

	}
	public function adminLogin($username, $password){

	}
}