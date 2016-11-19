<?php

namespace App\Components;

use \App\Utils\OtherUtils, \App\Utils\FlashMessage;

/**
 * Class for managing of CRUD operations on assessment types
 */
class Assessments {

	private $db;
	private $template;

	public function __construct($db, $template) {
		$this->db = $db;
		$this->template = $template;
	}

	public function renderList() {

		$query = "SELECT assessmentTypeId, description FROM assessmenttype";

		$statement = $this->db->prepare($query);
		$result = $this->db->execute($statement);

		$this->template->loadTemplateFile("/assessments/list.tpl", true, true);

		while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$this->template->setCurrentBlock("ASSESSMENTS_ROW");

			$this->template->setVariable("ASSESSMENT_DESC", $row["description"]);
			$this->template->setVariable("ASSESSMENT_ID", $row["assessmentTypeId"]);

			$this->template->parseCurrentBlock("ASSESSMENTS_ROW");
		}
	}
	
	public function renderAdd() {
		$this->renderEdit(false);
	}

	public function renderEdit($editing = true) {
		if ($editing) {
			$id = $_GET["id"];
			$assessment = $this->getAssessmentTypeById($id);
		}

		$this->template->loadTemplateFile("/assessments/edit.tpl", true, true);

		// is editing and not found by id
		if ($editing && !$assessment) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Assessment type was not found.");
			OtherUtils::redirect("/assessments", true, 303);
		} else {

			// submitting
			if (isset($_POST["description"])) {

				$this->template->setCurrentBlock("ASSESSMENT_TYPES_EDIT");

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

					$this->template->parseCurrentBlock("ASSESSMENT_TYPES_EDIT");

				// else save
				} else {
					// update
					$msg = "";
					if ($editing) {
						$this->updateAssessment($description);
						$msg = "updated";
					// insert
					} else {
						$this->insertAssessment($description);
						$msg = "created";
					}

					FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Assessment type was successfuly $msg.");
					OtherUtils::redirect("/assessments");
				}
			} else {
				if ($editing) {
					$this->fillForm($assessment["description"], true);
				} else {
					$this->template->setCurrentBlock("ASSESSMENT_TYPES_EDIT");
					$this->template->setVariable("VALUE_BUTTON", "Create assessment type");
					$this->template->parseCurrentBlock("ASSESSMENT_TYPES_EDIT");
				}
			}

		}
	}

	public function renderDelete() {
		$id = $_GET["id"];
		$result = $this->getAssessmentTypeById($id);
		if ($result) {
			// submitting
			if (isset($_POST["delete"])) {
				$this->deleteAssessment($id);
				$desc = $result["description"];
				FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Assessment type <i>$desc</i> was successfuly deleted.");
				OtherUtils::redirect("/assessments");
			} else {
				$this->template->loadTemplateFile("/assessments/delete.tpl", true, true);
				$this->template->setVariable("DELETE_ASSESSMENT_DESC", $result["description"]);
			}
		} else {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Assessment type was not found.");
			OtherUtils::redirect("/assessments", true, 303);
		}
	}

	private function insertAssessment($description) {
		$tableName = "assessmenttype";

		$fieldsValues = array(
			"description"	=> $description
		);

		$this->db->autoExecute($tableName, $fieldsValues, DB_AUTOQUERY_INSERT);
	}

	private function updateAssessment($description) {
		$tableName = "assessmenttype";

		$fieldsValues = array(
			"description"	=> $description
		);

		$id = $_GET["id"];

		$this->db->autoExecute($tableName, $fieldsValues, DB_AUTOQUERY_UPDATE, "assessmentTypeId = '$id'");
	}

	private function deleteAssessment($id) {
		$query = "DELETE FROM assessmenttype WHERE assessmentTypeId = ?";

		$statement = $this->db->prepare($query);
		$params = [$id];

		$result = $this->db->execute($statement, $params);
	}

	private function fillForm($description, $editing) {
		$this->template->setCurrentBlock("ASSESSMENT_TYPES_EDIT");

		$this->template->setVariable("VALUE_DESC", htmlspecialchars($description));

		$button = $editing ? "Update assessment type" : "Create assessment type";
		$this->template->setVariable("VALUE_BUTTON", $button);

		$this->template->parseCurrentBlock("ASSESSMENT_TYPES_EDIT");
	}

	public function getAssessmentTypeById($id) {
		$query = "SELECT * FROM assessmenttype WHERE assessmentTypeId = ?";

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
