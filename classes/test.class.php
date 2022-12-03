<?php 

 class Test extends Dbh{

 	public function getUser(){
 		$sql = "Select * from from POPS_USER_DETAILS";
 		$stmt = $this->connect()->sqlsrv_query($sql);
 		while($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
 			echo $row['USER_ID']." ".$row['FULL_NAME'];
 		}
 	}
 }


 ?>