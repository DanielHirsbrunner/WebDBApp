<?php

namespace App\DB;

// DB Class changed to ConnHelper to adapt to pear DB 
class ConnHelper
{
	static $instance = '';
	private $conn = '';
	
	// Singelton Instance
	public static function Instance() {
		// check if thers already an instance object
		if (ConnHelper::$instance == '') {
			ConnHelper::$instance = new ConnHelper();
			ConnHelper::$instance->InitConnection();
		}
		return ConnHelper::$instance;
	}
	
	public function GetConnection() {
		return $this->conn;
	}

	public function ExecuteSelectStmt($query) {
		$result = $this->conn->query($query);
		if (\DB::isError($result)) {
			\App\Utils\FlashMessage::add(\App\Utils\FlashMessage::TYPE_DEBUGGING, $result->getUserinfo());
			return 0;
		}
		return $result;
	}

	public function ExecuteInsertStmt($table_name, $fields_values) {
		$res = $this->conn->autoExecute($table_name, $fields_values, DB_AUTOQUERY_INSERT);

		if (\DB::isError($res)) {
			\App\Utils\FlashMessage::add(\App\Utils\FlashMessage::TYPE_DEBUGGING, $res->getUserinfo());
		}
	}

	public function ExecuteUpdateStmt($table_name, $fields_values, $where_condition) {
		$res = $this->conn->autoExecute($table_name, $fields_values, DB_AUTOQUERY_UPDATE, $where_condition);

		if (\DB::isError($res)) {
			\App\Utils\FlashMessage::add(\App\Utils\FlashMessage::TYPE_DEBUGGING, $res->getUserinfo());
		}
	}

	public function ExecuteDeleteStmt($table_name, $where_condition) {
		$res = $this->conn->query('DELETE FROM ' . $table_name . ' WHERE ' . $where_condition);

		if (\DB::isError($res)) {
			\App\Utils\FlashMessage::add(\App\Utils\FlashMessage::TYPE_DEBUGGING, $res->getUserinfo());
		}
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