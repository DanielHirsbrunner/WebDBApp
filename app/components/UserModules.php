<?php

namespace App\Components;

use \App\Utils\OtherUtils, \App\Utils\FlashMessage;

/**
 * Class for managing of user-module rights
 */
class UserModules {

	private $db;
	private $template;
	private $users;
	private $modules;

	public function __construct($db, $template, $users, $modules) {
		$this->db = $db;
		$this->template = $template;
		$this->users = $users;
		$this->modules = $modules;
	}

	public function renderModules() {
		$id = $_GET["id"];
		$user = $this->users->getUserById($id);

		if (!$user) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "User was not found.");
			OtherUtils::redirect("/users", true, 303);
		}

		$this->template->loadTemplateFile("/users/modules.tpl", true, true);

		//
		// non-asigned modules
		// 
		$modules = $this->getUsersModules(false, $id);
		$numRows = $modules->numRows();

		$this->template->setCurrentBlock("RESULTS_SELECT");

		if ($numRows == 0) {
			$this->template->touchBlock("ALL_MODULES_ASSIGNED");
		} else {
			$this->template->setVariable("FORM_ACTION_USER_ID", $id);
			while ($row = $modules->fetchRow(DB_FETCHMODE_ASSOC)) {
				$this->template->setCurrentBlock("SELECT_OPTION");

				$this->template->setVariable("MODULE_ID", $row["moduleId"]);
				$this->template->setVariable("NAME", htmlspecialchars($row["name"]));
				$this->template->setVariable("CODE", htmlspecialchars($row["code"]));

				$this->template->parseCurrentBlock("SELECT_OPTION");
			}
		}
		$this->template->parseCurrentBlock("RESULTS_SELECT");

		//
		// asigned modules
		// 
		$modules = $this->getUsersModules(true, $id);
		$numRows = $modules->numRows();

		if ($numRows == 0) {
			$this->template->touchBlock("NO_MODULES_ASSIGNED");
		} else {
			while ($row = $modules->fetchRow(DB_FETCHMODE_ASSOC)) {
				$this->template->setCurrentBlock("MODULES_ROW");

				$this->template->setVariable("MODULE_ID", $row["moduleId"]);
				$this->template->setVariable("NAME", htmlspecialchars($row["name"]));
				$this->template->setVariable("CODE", htmlspecialchars($row["code"]));
				$this->template->setVariable("USER_ID", $id);

				$this->template->parseCurrentBlock("MODULES_ROW");
			}
		}

	}

	private function getUsersModules($alreadyAssigned, $id) {
		$not = $alreadyAssigned ? "" : "NOT";

		$query = "SELECT `moduleId`, `name`, `code`
				  FROM `module`
				  WHERE `moduleId` $not IN (
					SELECT `moduleId` 
					FROM `moduleright` 
					WHERE (`userId` = ?) 
					GROUP BY `moduleId`)
				  ORDER BY moduleId";

		$statement = $this->db->prepare($query);
		$params = [$id];

		$result = $this->db->execute($statement, $params);

		if (\DB::isError($result)) {
			FlashMessage::add(FlashMessage::TYPE_DEBUGGING, $result->getUserinfo());
			return false;
		}

		return $result;
	}

	public function addModule() {
		$id = $_GET["id"];
		$user = $this->users->getUserById($id);

		if (!$user) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "User was not found.");
		} else {
			if (isset($_POST["module"])) {
				$moduleId = $_POST["module"];
				$module = $this->modules->getModuleById($moduleId);

				if (!$module) {
					FlashMessage::add(FlashMessage::TYPE_ERROR, "Module was not found.");
				} else {
					if (!$this->insertModuleRight($id, $_POST["module"])) {
						FlashMessage::add(FlashMessage::TYPE_ERROR, "An error occured when assigning module to user.");
					} else {
						$name = htmlspecialchars($module["name"]);
						FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Module <i>$name</i> was assigned.");
					}
				}
			} else {
				FlashMessage::add(FlashMessage::TYPE_ERROR, "Wrong request. Try again.");
			}
		}

		OtherUtils::redirect("/users/modules/".$id, true, 303);
	}

	private function insertModuleRight($userId, $moduleId) {
		$fieldsValues = array(
			"moduleId"		=> $moduleId,
			"userId"		=> $userId
		);

		$tableName = "moduleright";

		return improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_INSERT);
	}

	public function removeModule() {
		$id = $_GET["id"];
		$user = $this->users->getUserById($id);

		if (!$user) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "User was not found.");
		} else {
			$moduleId = $_GET["id2"];
			$module = $this->modules->getModuleById($moduleId);

			if (!$module) {
				FlashMessage::add(FlashMessage::TYPE_ERROR, "Module was not found.");
			} else {
				$this->removeModuleRight($id, $moduleId);
				$name = htmlspecialchars($module["name"]);
				FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Module <i>$name</i> was unassigned.");
			}
		}

		OtherUtils::redirect("/users/modules/".$id, true, 303);
	}

	private function removeModuleRight($userId, $moduleId) {

		$query = "DELETE FROM moduleright WHERE userId = ? AND moduleId = ?";

		$statement = $this->db->prepare($query);
		$params = [$userId, $moduleId];

		$result = $this->db->execute($statement, $params);
	}

}
