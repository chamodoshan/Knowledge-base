<?php
/**
* 
*/
class connection
{
	public $servername = "localhost";
	public $username = "root";
	public $password = "";
	public $dbname = "data set";

	// Create connection
	function check(){
		$conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
		if ($conn->connect_error) {
    		die("Connection failed: " . $conn->connect_error);
		} 
		return $conn;
	}
}

?>
