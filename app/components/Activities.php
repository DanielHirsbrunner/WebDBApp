<?php

namespace App\Components;

use \App\Utils\OtherUtils, \App\Utils\FlashMessage;

/**
 * Class for managing of CRUD operations on teaching and learning activities
 */
class Activities {

	private $db;
	private $template;

	public function __construct($db, $template) {
		$this->db = $db;
		$this->template = $template;
	}

	public function renderList() {

		$query = "SELECT teachLearnActivityId, description FROM teachlearnactivity";

		$statement = $this->db->prepare($query);
		$result = $this->db->execute($statement);

		$this->template->loadTemplateFile("/activities/list.tpl", true, true);

		while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$this->template->setCurrentBlock("ACTIVITY_ROW");

			$this->template->setVariable("ACTIVITY_DESC", htmlspecialchars($row["description"]));
			$this->template->setVariable("ACTIVITY_ID", $row["teachLearnActivityId"]);

			$this->template->parseCurrentBlock("ACTIVITY_ROW");
		}
	}
	
	public function renderAdd() {
		$this->renderEdit(false);
	}

	public function renderEdit($editing = true) {
		if ($editing) {
			$id = $_GET["id"];
			$activity = $this->getActivityById($id);
		}

		$this->template->loadTemplateFile("/activities/edit.tpl", true, true);

		// is editing and not found by id
		if ($editing && !$activity) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Activity was not found.");
			OtherUtils::redirect("/activities", true, 303);
		} else {

			// submitting
			if (isset($_POST["description"])) {

				$this->template->setCurrentBlock("ACTIVITY_EDIT");

				$isError = false;

				// check description
				$description = trim($_POST["description"]);
				if (strlen($description) > 100) {
					$isError = true;
					$this->template->setVariable("ERROR_DESC", "has-error");
					$this->template->touchBlock("ERROR_DESC_LONG");
				}

				// if and error occurred, fill inputs
				if ($isError) {
					$this->fillForm($description, $editing);

					$this->template->parseCurrentBlock("ACTIVITY_EDIT");

				// else save
				} else {
					// update
					$msg = "";
					if ($editing) {
						$this->updateActivity($description);
						$msg = "updated";
					// insert
					} else {
						$this->insertActivity($description);
						$msg = "created";
					}

					$descriptionHtml = htmlspecialchars($description);
					FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Activity <i>$descriptionHtml</i> was successfuly $msg.");
					OtherUtils::redirect("/activities");
				}
			} else {
				if ($editing) {
					$this->fillForm($activity["description"], true);
				} else {
					$this->template->setCurrentBlock("ACTIVITY_EDIT");
					$this->template->setVariable("VALUE_BUTTON", "Create Activity");
					$this->template->parseCurrentBlock("ACTIVITY_EDIT");
				}
			}

		}
	}

	public function renderDelete() {
		$id = $_GET["id"];
		$result = $this->getActivityById($id);
		if ($result) {
			// submitting
			if (isset($_POST["delete"])) {
				$this->deleteActivity($id);
				$desc = htmlspecialchars($result["description"]);
				FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Activity <i>$desc</i> was successfuly deleted.");
				OtherUtils::redirect("/activities");
			} else {
				$this->template->loadTemplateFile("/activities/delete.tpl", true, true);
				$this->template->setVariable("DELETE_ACTIVITY_DESC", htmlspecialchars($result["description"]));
			}
		} else {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Activity was not found.");
			OtherUtils::redirect("/activities", true, 303);
		}
	}

	private function insertActivity($description) {
		$tableName = "teachlearnactivity";

		$fieldsValues = array(
			"description"	=> $description
		);

		improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_INSERT);
	}

	private function updateActivity($description) {
		$tableName = "teachlearnactivity";

		$fieldsValues = array(
			"description"	=> $description
		);

		$id = $_GET["id"];

		improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_UPDATE, "teachLearnActivityId = '$id'");
	}

	private function deleteActivity($id) {
		$query = "DELETE FROM teachlearnactivity WHERE teachLearnActivityId = ?";

		$statement = $this->db->prepare($query);
		$params = [$id];

		$result = $this->db->execute($statement, $params);
	}

	private function fillForm($description, $editing) {
		$this->template->setCurrentBlock("ACTIVITY_EDIT");

		$this->template->setVariable("VALUE_DESC", htmlspecialchars($description));

		$button = $editing ? "Update Activity" : "Create Activity";
		$this->template->setVariable("VALUE_BUTTON", $button);

		$this->template->parseCurrentBlock("ACTIVITY_EDIT");
	}

	public function getActivityById($id) {
		$query = "SELECT * FROM teachlearnactivity WHERE teachLearnActivityId = ?";

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
