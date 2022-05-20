<?php
//This File creates a 'connection object' to our database

$database = "nate9weber_osrsgimtracker";			//name of database

$serverName = "localhost";				//most default to localhost

$username = "nate9weber_osrsgimtracker";						//username for the database - NOT the account

$password = "EpicToast#02";						//password for the database - Not the account

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
