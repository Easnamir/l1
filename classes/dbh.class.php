<?php 
	class Dbh{
		private $serverName = 'WIN-G3ITE0R2U0B';
		//private $serverName = 'AMIR-PC\SQLEXPRESS';
		private $username = 'sa';
		private $password = 'loop@1234';
		public $database;
		function __construct($database){
			$this->database=$database;
		}
		
		public function connect(){
			$connectinfo = array("Database" => $this->database, "UID"=>$this->username, "PWD" => $this->password);
			// var_dump($connectinfo);
			// exit;
			$conn = sqlsrv_connect( $this->serverName, $connectinfo);
			if($conn)
			return $conn;
			else 
				die(print_r( sqlsrv_errors(), true));
				// die('License expired! Contact admin');
		}
	}
 ?>