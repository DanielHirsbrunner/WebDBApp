<?php

namespace App\Components;

use \App\Utils\OtherUtils, \App\Utils\FlashMessage;

/**
 * Class for managing of CRUD operations on user
 */
class Users {

	private $db;
	private $template;

	public function __construct($db, $template) {
		$this->db = $db;
		$this->template = $template;
	}

	public function renderList() {

		$query = "SELECT userId, userName, password, name, surname, email, qualification, isAdmin FROM user";

		$statement = $this->db->prepare($query);
		$result = $this->db->execute($statement);

		$this->template->loadTemplateFile("/users/list.tpl", true, true);
		$this->template->setCurrentBlock("RESULTS_TABLE");

		while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$this->template->setCurrentBlock("USERS_LIST");

			$this->template->setVariable("USERNAME", $row["userName"]);
			$this->template->setVariable("FULLNAME", $row["name"]." ".$row["surname"]);
			$this->template->setVariable("EMAIL", $row["email"]);
			$this->template->setVariable("ADMIN", $row["isAdmin"] ? "Yes" : "No");
			$this->template->setVariable("USER_ID", $row["userId"]);

			$this->template->parseCurrentBlock("USERS_LIST");
		}
		$this->template->parseCurrentBlock("RESULTS_TABLE");
	}
	
	public function renderAdd() {
		$this->renderEdit(false);
	}

	public function renderEdit($editing = true) {
		if ($editing) {
			$id = $_GET["id"];
			$user = $this->getUserById($id);
		}

		$this->template->loadTemplateFile("/users/edit.tpl", true, true);

		// is editing and not found by id
		if ($editing && !$user) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "User was not found.");
			OtherUtils::redirect("/users", true, 303);
		} else {

			// submitting
			if (isset($_POST["username"]) && isset($_POST["password"]) && isset($_POST["password2"]) &&
				isset($_POST["name"]) && isset($_POST["surname"]) && isset($_POST["qualification"])) {

				$this->template->setCurrentBlock("USERS_EDIT");

				$isError = false;

				// check username
				$username = trim($_POST["username"]);
				if (strlen($username) > 50) {
					$isError = true;
					$this->template->setVariable("ERROR_USERNAME", "has-error");
					$this->template->touchBlock("ERROR_USERNAME_LONG");
				}
				// TODO check duplicity
				// check password
				$password = $_POST["password"];
				if (strlen($password) > 100) {
					$isError = true;
					$this->template->setVariable("ERROR_PASSWORD", "has-error");
					$this->template->touchBlock("ERROR_PASSWORD_LONG");
				}
				$password2 = $_POST["password2"];
				if ($password != $password2) {
					$isError = true;
					$this->template->setVariable("ERROR_PASSWORD2", "has-error");
					$this->template->touchBlock("ERROR_PASSWORD_NOMATCH");
				}
				// check name
				$name = trim($_POST["name"]);
				if (strlen($name) > 50) {
					$isError = true;
					$this->template->setVariable("ERROR_NAME", "has-error");
					$this->template->touchBlock("ERROR_NAME_LONG");
				}
				// check surname
				$surname = trim($_POST["surname"]);
				if (strlen($surname) > 50) {
					$isError = true;
					$this->template->setVariable("ERROR_SURNAME", "has-error");
					$this->template->touchBlock("ERROR_SURNAME_LONG");
				}
				// check email
				$email = trim($_POST["email"]);
				if (strlen($email) > 50) {
					$isError = true;
					$this->template->setVariable("ERROR_EMAIL", "has-error");
					$this->template->touchBlock("ERROR_EMAIL_LONG");
				}
				// check qualification
				$qualification = trim($_POST["qualification"]);
				if (strlen($qualification) > 250) {
					$isError = true;
					$this->template->setVariable("ERROR_QUALIFICATION", "has-error");
					$this->template->touchBlock("ERROR_QUALIFICATION_LONG");
				}
				// get admin
				$admin = isset($_POST["admin"]);
				// if and error occurred, fill inputs
				if ($isError) {
					$usernameVal = htmlspecialchars($username);
					$this->template->setVariable("VALUE_USERNAME", $usernameVal);
					$nameVal = htmlspecialchars($name);
					$this->template->setVariable("VALUE_NAME", $nameVal);
					$surnameVal = htmlspecialchars($surname);
					$this->template->setVariable("VALUE_SURNAME", $surnameVal);
					$emailVal = htmlspecialchars($email);
					$this->template->setVariable("VALUE_EMAIL", $emailVal);
					$qualificationVal = htmlspecialchars($qualification);
					$this->template->setVariable("VALUE_QUALIFICATION", $qualificationVal);
					$this->template->setVariable("VALUE_ADMIN", $admin ? "checked" : "");

					if ($editing) {
						$this->template->setVariable("VALUE_BUTTON", "Update user");
					} else {
						$this->template->setVariable("VALUE_BUTTON", "Create user");
					}

				// else save
				} else {
					// update
					$msg = "";
					if ($editing) {
						$this->updateUser($username, $password, $name, $surname, $email, $qualification, $admin);
						$msg = "updated";
					// insert
					} else {
						$this->insertUser($username, $password, $name, $surname, $email, $qualification, $admin);
						$msg = "created";
					}

					FlashMessage::add(FlashMessage::TYPE_SUCCESS, "User was successfuly $msg.");
					OtherUtils::redirect("/users");
				}

				$this->template->parseCurrentBlock("USERS_EDIT");
			} else {
				if ($editing) {
					$this->fillForm($user);
				} else {
					$this->template->setCurrentBlock("USERS_EDIT");
					$this->template->setVariable("PASSWORD_REQUIRED", "required");
					$this->template->setVariable("VALUE_BUTTON", "Create user");
					$this->template->parseCurrentBlock("USERS_EDIT");
				}
			}

		}
	}

	public function renderDelete() {
		$id = $_GET["id"];
		$result = $this->getUserById($id);
		if ($result) {
			// submitting
			if (isset($_POST["delete"])) {
				$this->deleteUser($id);
				FlashMessage::add(FlashMessage::TYPE_SUCCESS, "User was successfuly deleted.");
				OtherUtils::redirect("/users");
			} else {
				$this->template->loadTemplateFile("/users/delete.tpl", true, true);
				$this->template->setVariable("DELETE_USER_NAME", $result["name"]." ".$result["surname"]);
				$this->template->touchBlock("USER_DELETE");
			}
		} else {
			$this->template->loadTemplateFile("/users/delete.tpl", false, true);
			$this->template->touchBlock("USER_NOT_FOUND");
		}
	}

	private function insertUser($username, $password, $name, $surname, $email, $qualification, $admin) {
		$table_name = "user";
		$hashed = OtherUtils::hashPassword($password);

		$fieldsValues = array(
			"userName"		=> $username,
			"password"		=> $hashed,
			"name"			=> $name,
			"surname"		=> $surname,
			"email"			=> $email,
			"qualification"	=> $qualification,
			"isAdmin"		=> $admin
		);

		$this->db->autoExecute($table_name, $fieldsValues, DB_AUTOQUERY_INSERT);
	}

	private function updateUser($username, $password, $name, $surname, $email, $qualification, $admin) {
		$fieldsValues = array(
			"userName"		=> $username,
			"name"			=> $name,
			"surname"		=> $surname,
			"email"			=> $email,
			"qualification"	=> $qualification,
			"isAdmin"		=> $admin
		);
		// change also password
		if (strlen($password) > 0) {
			$fieldsValues['password'] = OtherUtils::hashPassword($password);
		}

		$table_name = "user";
		$id = $_GET["id"];

		$this->db->autoExecute($table_name, $fieldsValues, DB_AUTOQUERY_UPDATE, "userId = '$id'");
	}

	private function deleteUser($id) {
		$query = "DELETE FROM user WHERE userId = ?";

		$statement = $this->db->prepare($query);
		$params = [$id];

		$result = $this->db->execute($statement, $params);
	}

	private function fillForm($user) {
		$this->template->setCurrentBlock("USERS_EDIT");

		$this->template->setVariable("VALUE_USERNAME", htmlspecialchars( $user["userName"] ));
		$this->template->setVariable("VALUE_NAME", htmlspecialchars( $user["name"] ));
		$this->template->setVariable("VALUE_SURNAME", htmlspecialchars( $user["surname"] ));
		$this->template->setVariable("VALUE_EMAIL", htmlspecialchars( $user["email"] ));
		$this->template->setVariable("VALUE_QUALIFICATION", htmlspecialchars( $user["qualification"] ));
		$this->template->setVariable("VALUE_ADMIN", $user["isAdmin"] ? "checked" : "");

		$this->template->setVariable("VALUE_BUTTON", "Update user");
		$this->template->parseCurrentBlock("USERS_EDIT");
	}

	private function getUserById($id) {
		$query = "SELECT * FROM user WHERE userId = ?";

		$statement = $this->db->prepare($query);
		$params = [$id];

		$result = $this->db->execute($statement, $params);
		if (\DB::isError($result)) {
			return false;
		}

		$numRows = $result->numRows();

		if ($numRows == 0) {
			return false;
		} else {
			$row = $result->fetchRow(DB_FETCHMODE_ASSOC);

			$row["password"] = "";

			return $row;
		}
	}

}
