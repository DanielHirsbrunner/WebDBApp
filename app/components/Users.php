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
		$result = $this->getAllUsers();

		$this->template->loadTemplateFile("/users/list.tpl", true, true);

		if (!$result) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "An error occurred when trying to load list of all users.");
		} else {

			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$this->template->setCurrentBlock("USERS_ROW");

				$this->template->setVariable("USERNAME", htmlspecialchars($row["userName"]));
				$this->template->setVariable("FULLNAME", htmlspecialchars($row["name"]." ".$row["surname"]));
				$this->template->setVariable("EMAIL", htmlspecialchars($row["email"]));
				$this->template->setVariable("ADMIN", $row["isAdmin"] ? "Yes" : "No");
				$this->template->setVariable("USER_ID", $row["userId"]);

				$this->template->parseCurrentBlock("USERS_ROW");
			}
		}
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
				if ($this->isUsernameDuplicate($username)) {
					$isError = true;
					$this->template->setVariable("ERROR_USERNAME", "has-error");
					$this->template->touchBlock("ERROR_USERNAME_DUPLICATE");
				}
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
					$this->fillForm($username, $name, $surname, $email, $qualification, $admin, $editing);

					$this->template->parseCurrentBlock("USERS_EDIT");

				// else save
				} else {
					// update
					$msg = "";
					if ($editing) {
						if (!$this->updateUser($username, $password, $name, $surname, $email, $qualification, $admin)) {
							$isError = true;
							$msg = "updating";
						} else {
							$msg = "updated";
						}
					// insert
					} else {
						if (!$this->insertUser($username, $password, $name, $surname, $email, $qualification, $admin)) {
							$isError = true;
							$msg = "creating";
						} else {
							$msg = "created";
						}
					}
					if ($isError) {
						FlashMessage::add(FlashMessage::TYPE_ERROR, "An error occured when $msg user.");
					} else {
						$fullNameHtml = htmlspecialchars($name." ".$surname);
						FlashMessage::add(FlashMessage::TYPE_SUCCESS, "User <i>$fullNameHtml</i> was successfuly $msg.");
					}
					OtherUtils::redirect("/users");
				}
			} else {
				if ($editing) {
					$this->fillForm($user["userName"], $user["name"], $user["surname"],
									$user["email"], $user["qualification"], $user["isAdmin"], true);
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
			// user deleting himself
			if ($id == $_SESSION["user"]["id"]) {
				FlashMessage::add(FlashMessage::TYPE_ERROR, "You cannot delete your own account.");
				OtherUtils::redirect("/users");
			}
			// submitting
			if (isset($_POST["delete"])) {
				$result2 = $this->deleteUser($id);
				if ($_POST["delete"] == "ajax" && !$result2) {
					http_response_code(400);
				} else {
					$fullName = htmlspecialchars($result["name"]." ".$result["surname"]);
					if (!$result2) {
						FlashMessage::add(FlashMessage::TYPE_ERROR, "User <i>$fullName</i> cannot be deleted. Check is there isn't any sylabus item referencing this record.");
					} else {
						FlashMessage::add(FlashMessage::TYPE_SUCCESS, "User <i>$fullName</i> was successfuly deleted.");
					}
					OtherUtils::redirect("/users");
				}
			} else {
				$this->template->loadTemplateFile("/users/delete.tpl", true, true);
				$this->template->setVariable("DELETE_USER_NAME", htmlspecialchars($result["name"]." ".$result["surname"]));
			}
		} else {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "User was not found.");
			OtherUtils::redirect("/users", true, 303);
		}
	}

	private function insertUser($username, $password, $name, $surname, $email, $qualification, $admin) {
		$tableName = "user";
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

		return improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_INSERT);
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

		$tableName = "user";
		$id = $_GET["id"];

		return improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_UPDATE, "userId = '$id'");
	}

	private function deleteUser($id) {
		$query = "DELETE FROM user WHERE userId = ?";

		$statement = $this->db->prepare($query);
		$params = [$id];

		$result = $this->db->execute($statement, $params);

		if (\DB::isError($result)) {
			FlashMessage::add(FlashMessage::TYPE_DEBUGGING, $result->getUserinfo());
			return false;
		} else {
			return true;
		}
	}

	private function fillForm($username, $name, $surname, $email, $qualification, $isAdmin, $editing) {
		$this->template->setCurrentBlock("USERS_EDIT");

		$this->template->setVariable("VALUE_USERNAME", htmlspecialchars($username));
		$this->template->setVariable("VALUE_NAME", htmlspecialchars($name));
		$this->template->setVariable("VALUE_SURNAME", htmlspecialchars($surname));
		$this->template->setVariable("VALUE_EMAIL", htmlspecialchars($email));
		$this->template->setVariable("VALUE_QUALIFICATION", htmlspecialchars($qualification));
		$this->template->setVariable("VALUE_ADMIN", $isAdmin ? "checked" : "");

		$button = $editing ? "Update user" : "Create user";
		$this->template->setVariable("VALUE_BUTTON", $button);
		$this->template->parseCurrentBlock("USERS_EDIT");
	}

	public function getAllUsers() {
		$query = "SELECT * FROM user";

		$statement = $this->db->prepare($query);
		$result = $this->db->execute($statement);

		if (\DB::isError($result)) {
			FlashMessage::add(FlashMessage::TYPE_DEBUGGING, $result->getUserinfo());
			return false;
		}

		$numRows = $result->numRows();

		if ($numRows == 0) {
			return false;
		} else {
			return $result;
		}
	}

	public function getUserById($userId) {
		$query = "SELECT * FROM user WHERE userId = ?";

		$statement = $this->db->prepare($query);
		$params = [$userId];

		$result = $this->db->execute($statement, $params);
		if (\DB::isError($result)) {
			FlashMessage::add(FlashMessage::TYPE_DEBUGGING, $result->getUserinfo());
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

	private function isUsernameDuplicate($username) {
		$query = "SELECT * FROM user WHERE userId <> ? AND userName = ?";

		$statement = $this->db->prepare($query);

		$userId = isset($_GET["id"]) ? $_GET["id"] : "0";
		$params = [$userId, $username];

		$result = $this->db->execute($statement, $params);
		if (\DB::isError($result)) {
			FlashMessage::add(FlashMessage::TYPE_DEBUGGING, $result->getUserinfo());
			return false;
		}

		$numRows = $result->numRows();

		if ($numRows == 0) {
			return false;
		} else {
			return true;
		}
	}

}
