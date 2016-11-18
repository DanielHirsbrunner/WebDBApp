<?php

namespace App\Components;

use \App\Utils\OtherUtils, \App\Utils\FlashMessage;

/**
 * Class for managing of CRUD operations on modules
 */
class Modules {

	private $db;
	private $template;

	public function __construct($db, $template) {
		$this->db = $db;
		$this->template = $template;
	}

	public function renderList() {

		$query = "SELECT moduleId, name, code, credits, moduleOwner, purpose, editBy, editTS FROM module";

		$statement = $this->db->prepare($query);
		$result = $this->db->execute($statement);

		$this->template->loadTemplateFile("/modules/list.tpl", true, true);
		$this->template->setCurrentBlock("RESULTS_TABLE");

		while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$this->template->setCurrentBlock("MODULES_ROW");

			$this->template->setVariable("NAME", $row["name"]);
			$this->template->setVariable("CODE", $row["code"]);
			$this->template->setVariable("CREDITS", $row["credits"]);
			$this->template->setVariable("MODULE_ID", $row["moduleId"]);

			$this->template->parseCurrentBlock("MODULES_ROW");
		}
		$this->template->parseCurrentBlock("RESULTS_TABLE");
	}
	
	public function renderAdd() {
		$this->renderEdit(false);
	}

	public function renderEdit($editing = true) {
		if ($editing) {
			$id = $_GET["id"];
			$module = $this->getModuleById($id);
		}

		$this->template->loadTemplateFile("/modules/edit.tpl", true, true);

		// is editing and not found by id
		if ($editing && !$module) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Module was not found.");
			OtherUtils::redirect("/modules", true, 303);
		} else {

			// submitting
			if (isset($_POST["name"]) && isset($_POST["code"]) && isset($_POST["credits"]) && isset($_POST["purpose"])) {

				$this->template->setCurrentBlock("MODULES_EDIT");

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

					$this->template->parseCurrentBlock("MODULES_EDIT");

				// else save
				} else {
					// update
					$msg = "";
					if ($editing) {
						$this->updateModule($name, $code, $credits, $purpose);
						$msg = "updated";
					// insert
					} else {
						$this->insertModule($name, $code, $credits, $purpose);
						$msg = "created";
					}

					FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Module was successfuly $msg.");
					OtherUtils::redirect("/modules");
				}
			} else {
				if ($editing) {
					$this->fillForm($module["name"], $module["code"], $module["credits"], $module["purpose"], true);
				} else {
					$this->template->setCurrentBlock("MODULES_EDIT");
					$this->template->setVariable("VALUE_BUTTON", "Create module");
					$this->template->parseCurrentBlock("MODULES_EDIT");
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
				FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Module was successfuly deleted.");
				OtherUtils::redirect("/modules");
			} else {
				$this->template->loadTemplateFile("/modules/delete.tpl", true, true);
				$this->template->setVariable("DELETE_MODULE_NAME", $result["name"]);
			}
		} else {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Module was not found.");
			OtherUtils::redirect("/modules", true, 303);
		}
	}

	private function insertModule($name, $code, $credits, $purpose) {
		$tableName = "module";

		$fieldsValues = array(
			"name"		=> $name,
			"code"		=> $code,
			"credits"	=> $credits,
			"purpose"	=> $purpose,
			"editBy"	=> $_SESSION["user"]["id"]
		);

		var_dump($this->db->autoExecute($tableName, $fieldsValues, DB_AUTOQUERY_INSERT));
	}

	private function updateModule($name, $code, $credits, $purpose) {
		$tableName = "module";

		$fieldsValues = array(
			"name"		=> $name,
			"code"		=> $code,
			"credits"	=> $credits,
			"purpose"	=> $purpose,
			"editBy"	=> $_SESSION["user"]["id"]
		);

		$id = $_GET["id"];

		$this->db->autoExecute($tableName, $fieldsValues, DB_AUTOQUERY_UPDATE, "moduleId = '$id'");
	}

	private function deleteModule($id) {
		$query = "DELETE FROM module WHERE moduleId = ?";

		$statement = $this->db->prepare($query);
		$params = [$id];

		$result = $this->db->execute($statement, $params);
	}

	private function fillForm($name, $code, $credits, $purpose, $editing) {
		$this->template->setCurrentBlock("MODULES_EDIT");

		$this->template->setVariable("VALUE_NAME", htmlspecialchars($name));
		$this->template->setVariable("VALUE_CODE", htmlspecialchars($code));
		$this->template->setVariable("VALUE_CREDITS", htmlspecialchars($credits));
		$this->template->setVariable("VALUE_PURPOSE", htmlspecialchars($purpose));

		$button = $editing ? "Update module" : "Create module";
		$this->template->setVariable("VALUE_BUTTON", $button);

		$this->template->parseCurrentBlock("MODULES_EDIT");
	}

	public function getModuleById($id) {
		$query = "SELECT * FROM module WHERE moduleId = ?";

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
			return $result->fetchRow(DB_FETCHMODE_ASSOC);
		}
	}

}
