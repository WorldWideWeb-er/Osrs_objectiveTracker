<?php
//This File creates a 'connection object' to our database

$database = "osrsgimdatabase";			//name of database

$serverName = "localhost";				//most default to localhost

$username = "root";						//username for the database - NOT the account

$password = "";						//password for the database - Not the account

try {
	$conn = new PDO("mysql:host=$serverName; dbname=$database", $username, $password);
		//set the PDO error mode to exceptions
	$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	//echo "Connected Successfully";
}

catch(PDOException $e){
	echo "Connection Failed;" . $e->getMessage();		//$e.getMessage()
}

?>
