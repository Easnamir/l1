<?php 

	class Dbhh{
		private $host="EC2AMAZ-FILEU48\SQLEXPRESS";
		private $user="sa";
		private $password="tcs@1234";
		private $dbname="ipos-demo";

		protected function connect(){
		    
		    $dsn = "sqlsrv:Server=".$this->host.";Database=".$this->dbname;
			$pdo = new PDO($dsn,$this->user,$this->password);
			//$pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
			$pdo->setAttribute( PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
			return $pdo;
		}	
	}
 ?>