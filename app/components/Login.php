<?php

namespace App\Components;

/**
 * Class for performing login
 */
class Login {

	private $db;

	public function __construct($db) {
		$this->db = $db;
	}

	public function run($username, $password) {
		$query = "SELECT userId, userName, password, name, surname, email, qualification, isAdmin
				FROM user 
				WHERE userName = ?";

		$statement = $this->db->prepare($query);
		$params = [$username];

		$result = $this->db->execute($statement, $params);
		if (\DB::isError($result)) {
			return false;
		}

		$numRows = $result->numRows();

		if ($numRows == 0) {
			return false;
		} else {
			$row = $result->fetchRow(DB_FETCHMODE_ASSOC);

			// successful login
			if (password_verify($password, $row["password"])) {
				$_SESSION["user"] = [];
				$_SESSION["user"]["id"] = $row["userId"];
				$_SESSION["user"]["fullName"] = $row["name"]." ".$row["surname"];
				$_SESSION["user"]["username"] = $username;
				$_SESSION["user"]["isAdmin"] = $row["isAdmin"];

				return true;

			// wrong login
			} else {
				return false;
			}
		}
	}

}
