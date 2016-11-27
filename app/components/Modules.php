<?php

namespace App\Components;

use \App\Utils\OtherUtils, \App\Utils\FlashMessage;

/**
 * Class for managing of CRUD operations on modules
 */
class Modules {

	private $db;
	private $template;
	private $users;

	public function __construct($db, $template) {
		$this->db = $db;
		$this->template = $template;
		$this->users = new Users($db, $template);
	}

	public function renderList() {
		$result = $this->getAllModules();

		$this->template->loadTemplateFile("/modules/list.tpl", true, true);

		if (!$result) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "An error occurred when trying to load list of all modules.");
		} else {

			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$this->template->setCurrentBlock("MODULES_ROW");

				$this->template->setVariable("MODULE_NAME", htmlspecialchars($row["name"]));
				$this->template->setVariable("MODULE_CODE", htmlspecialchars($row["code"]));
				$this->template->setVariable("MODULE_CREDITS", htmlspecialchars($row["credits"]));
				$this->template->setVariable("MODULE_ID", htmlspecialchars($row["moduleId"]));

				$this->template->parseCurrentBlock("MODULES_ROW");
			}
		}
	}
	
	public function renderAdd() {
		$this->renderEdit(false);
	}

	public function renderEdit($editing = true) {
		if ($editing) {
			$id = $_GET["id"];
			$module = $this->getModuleById($id);
		} else {
			$id = 0;
		}

		$this->template->loadTemplateFile("/modules/edit.tpl", true, true);

		// is editing and not found by id
		if ($editing && !$module) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Module was not found.");
			OtherUtils::redirect("/modules", true, 303);
		} else {

			// submitting
			if (isset($_POST["name"]) && isset($_POST["code"]) && isset($_POST["credits"]) &&
				isset($_POST["purpose"]) && isset($_POST["owner"])) {

				$isError = false;

				// check name
				$name = trim($_POST["name"]);
				if (strlen($name) > 50) {
					$isError = true;
					$this->template->setVariable("ERROR_NAME", "has-error");
					$this->template->touchBlock("ERROR_NAME_LONG");
				}

				// check code
				$code = $_POST["code"];
				if (strlen($code) > 20) {
					$isError = true;
					$this->template->setVariable("ERROR_CODE", "has-error");
					$this->template->touchBlock("ERROR_CODE_LONG");
				}

				// check credits
				$credits = trim($_POST["credits"]);
				// check if credits is an integer
				if (!preg_match('/^\d+$/', $credits)) {
					$this->template->setVariable("ERROR_CREDITS", "has-error");
					$this->template->touchBlock("ERROR_CREDITS_NUMBER");
				} else if ( (int) $credits > 99) {
					$isError = true;
					$this->template->setVariable("ERROR_CREDITS", "has-error");
					$this->template->touchBlock("ERROR_CREDITS_LONG");
				} else  {
					$credits = (int) $credits;
				}

				// check owner
				$ownerId = $_POST["owner"];
				if ($ownerId != "0" && !$this->users->getUserById($ownerId)) {
					$isError = true;
					$this->template->setVariable("ERROR_OWNER", "has-error");
					$this->template->touchBlock("ERROR_OWNER_NOT_FOUND");
				}
				if ($ownerId == "0") $ownerId = NULL;

				// check prerequisite
				$prereqId = $_POST["prerequisite"];
				if ($prereqId != "0" && !$this->getModuleById($prereqId)) {
					$isError = true;
					$this->template->setVariable("ERROR_PREREQUISITE", "has-error");
					$this->template->touchBlock("ERROR_PREREQUISITE_NOT_FOUND");
				}
				if ($prereqId == "0") $prereqId = NULL;
				if ($prereqId === $id) {// null == 0 is true
					$isError = true;
					$this->template->setVariable("ERROR_PREREQUISITE", "has-error");
					$this->template->touchBlock("ERROR_PREREQUISITE_ITSELF");
				}

				// check purpose
				$purpose = trim($_POST["purpose"]);
				if (strlen($purpose) > 1500) {
					$isError = true;
					$this->template->setVariable("ERROR_PURPOSE", "has-error");
					$this->template->touchBlock("ERROR_PURPOSE_LONG");
				}

				// if and error occurred, fill inputs
				if ($isError) {
					$this->fillForm($name, $code, $credits, $purpose, $editing);
					$this->fillModuleOwnerSelect($ownerId);
					$this->fillPrerequisiteSelect($id, $prereqId);

				// else save
				} else {
					// update
					$msg = "";
					if ($editing) {
						if (!$this->updateModule($name, $code, $credits, $ownerId, $prereqId, $purpose)) {
							$isError = true;
							$msg = "updating";
						} else {
							$msg = "updated";
						}
					// insert
					} else {
						if (!$this->insertModule($name, $code, $credits, $ownerId, $prereqId, $purpose)) {
							$isError = true;
							$msg = "creating";
						} else {
							$msg = "created";
						}
					}
					if ($isError) {
						FlashMessage::add(FlashMessage::TYPE_ERROR, "An error occured when $msg module.");
					} else {
						$nameHtml = htmlspecialchars($name);
						FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Module <i>$nameHtml</i> was successfuly $msg.");
					}
					OtherUtils::redirect("/modules");
				}
			} else {
				if ($editing) {
					$this->fillForm($module["name"], $module["code"], $module["credits"], $module["purpose"], true);
					$this->fillModuleOwnerSelect($module["moduleOwner"]);
					$this->fillPrerequisiteSelect($id, $module["prerequisite"]);
				} else {
					$this->template->setVariable("VALUE_BUTTON", "Create module");
					$this->fillModuleOwnerSelect();
					$this->fillPrerequisiteSelect(0);
				}
			}

		}
	}

	public function renderDelete() {
		$id = $_GET["id"];
		$result = $this->getModuleById($id);
		if ($result) {
			// submitting
			if (isset($_POST["delete"])) {
				$this->deleteModule($id);
				$name = htmlspecialchars($result["name"]);
				FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Module <i>$name</i> was successfuly deleted.");
				OtherUtils::redirect("/modules");
			} else {
				$this->template->loadTemplateFile("/modules/delete.tpl", true, true);
				$this->template->setVariable("DELETE_MODULE_NAME", htmlspecialchars($result["name"]));
			}
		} else {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Module was not found.");
			OtherUtils::redirect("/modules", true, 303);
		}
	}

	private function insertModule($name, $code, $credits, $ownerId, $prereqId, $purpose) {
		$tableName = "module";

		$fieldsValues = array(
			"name"			=> $name,
			"code"			=> $code,
			"credits"		=> $credits,
			"moduleOwner"	=> $ownerId,
			"prerequisite"	=> $prereqId,
			"purpose"		=> $purpose,
			"editBy"		=> $_SESSION["user"]["id"]
		);

		return improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_INSERT);
	}

	private function updateModule($name, $code, $credits, $ownerId, $prereqId, $purpose) {
		$tableName = "module";

		$fieldsValues = array(
			"name"			=> $name,
			"code"			=> $code,
			"credits"		=> $credits,
			"moduleOwner"	=> $ownerId,
			"prerequisite"	=> $prereqId,
			"purpose"		=> $purpose,
			"editBy"		=> $_SESSION["user"]["id"]
		);

		$id = $_GET["id"];

		return improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_UPDATE, "moduleId = '$id'");
	}

	private function deleteModule($id) {
		$query = "DELETE FROM module WHERE moduleId = ?";

		$statement = $this->db->prepare($query);
		$params = [$id];

		$result = $this->db->execute($statement, $params);
	}

	private function fillForm($name, $code, $credits, $purpose, $editing) {

		$this->template->setVariable("VALUE_NAME", htmlspecialchars($name));
		$this->template->setVariable("VALUE_CODE", htmlspecialchars($code));
		$this->template->setVariable("VALUE_CREDITS", htmlspecialchars($credits));
		$this->template->setVariable("VALUE_PURPOSE", htmlspecialchars($purpose));

		$button = $editing ? "Update module" : "Create module";
		$this->template->setVariable("VALUE_BUTTON", $button);

	}

	private function fillModuleOwnerSelect($ownerId = 0) {
		$users = $this->users->getAllUsers();

		$this->template->setCurrentBlock("OWNER_OPTION");

		$this->template->setVariable("OWNER_ID", 0);
		$this->template->setVariable("OWNER_SELECTED", 0 == $ownerId ? " selected" : "");
		$this->template->setVariable("OWNER_NAME", "Nobody");

		$this->template->parseCurrentBlock("OWNER_OPTION");

		while ($users && $row = $users->fetchRow(DB_FETCHMODE_ASSOC)) {
			$this->template->setCurrentBlock("OWNER_OPTION");

			$this->template->setVariable("OWNER_ID", $row["userId"]);
			$this->template->setVariable("OWNER_SELECTED", $row["userId"] == $ownerId ? " selected" : "");
			$this->template->setVariable("OWNER_NAME", htmlspecialchars($row["name"]." ".$row["surname"]));

			$this->template->parseCurrentBlock("OWNER_OPTION");
		}
	}

	private function fillPrerequisiteSelect($moduleId, $prereqId = 0) {
		$prereqs = $this->getAllModulesButCurrent($moduleId);

		$this->template->setCurrentBlock("PREREQUISITE_OPTION");

		$this->template->setVariable("PREREQUISITE_ID", 0);
		$this->template->setVariable("PREREQUISITE_SELECTED", 0 == $prereqId ? " selected" : "");
		$this->template->setVariable("PREREQUISITE_CODE", "None");

		$this->template->parseCurrentBlock("PREREQUISITE_OPTION");

		while ($prereqs && $row = $prereqs->fetchRow(DB_FETCHMODE_ASSOC)) {
			$this->template->setCurrentBlock("PREREQUISITE_OPTION");

			$this->template->setVariable("PREREQUISITE_ID", $row["moduleId"]);
			$this->template->setVariable("PREREQUISITE_SELECTED", $row["moduleId"] == $prereqId ? " selected" : "");
			$this->template->setVariable("PREREQUISITE_CODE", htmlspecialchars($row["code"]." - ".$row["name"]));

			$this->template->parseCurrentBlock("PREREQUISITE_OPTION");
		}
	}

	public function getAllModules() {
		$query = "SELECT * FROM module";

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

	public function getAllModulesButCurrent($currentModuleId) {
		$query = "SELECT * FROM module WHERE moduleId <> ?";

		$statement = $this->db->prepare($query);
		$params = [$currentModuleId];
		$result = $this->db->execute($statement, $params);

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

	public function getModuleById($id) {
		$query = "SELECT * FROM module WHERE moduleId = ?";

		$statement = $this->db->prepare($query);
		$params = [$id];
		$result = $this->db->execute($statement, $params);

		if (\DB::isError($result)) {
			FlashMessage::add(FlashMessage::TYPE_DEBUGGING, $result->getUserinfo());
			return false;
		}

		$numRows = $result->numRows();

		if ($numRows == 0) {
			return false;
		} else {
			return $result->fetchRow(DB_FETCHMODE_ASSOC);
		}
	}

}
