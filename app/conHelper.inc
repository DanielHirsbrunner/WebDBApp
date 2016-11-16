<?php
namespace App;
include_once('app/connection.inc');
// DB Class changed to conHelper to adapt to pear DB 
class conHelper
{
	static $instance = '';
	private $conn = '';
	
	// Singelton Instance
	public static function Instance() {
		// check if thers already an instance object
		if (conHelper::$instance == '') {
			conHelper::$instance = new conHelper();
			conHelper::$instance->InitConnection();
		}
		return conHelper::$instance;
	}
	
	public function GetConnection() {
		return $this->conn;
	}

	public function ExecuteSelectStmt($query) {
		$result = $this->conn->query($query);
		if (\DB::isError($result)) {
			echo '<p>There was en error in the Database query: </p><p>'.$result->getMessage().'</p></body></html>';
			return 0;
		}
		return $result;
	}
	
	// needed for variables where the user can enter text to prevent sql injection and escape special characters
	public function cleanInput($input){
	  $input = trim(rtrim(ltrim($input))); // Remove spaces
	  $input = htmlspecialchars($input); // Escape special Characters
	  //$input = mysqli_real_escape_string($this->conn, $input); // Make sure that no sql injection can happen
	  return $input;
	}
	
	private function InitConnection() {
		// Connect with Pear, like learnt in the lesson:
		$dsn = 'mysql://'. USERNAME . ':' . PASSWORD . '@' . SERVER . '/' . DATABASE;
		// Open a connection to the DBMS
		$this->conn = \DB::connect($dsn);
		if (\DB::isError($this->conn)) {
			echo '<p>'.$this->conn->getMessage().'</p></body></html>';
			exit;
		}
	}
}

?>