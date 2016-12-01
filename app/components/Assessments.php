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

		$query = "SELECT assessmentTypeId, description, isWrittenTest FROM assessmenttype";

		$statement = $this->db->prepare($query);
		$result = $this->db->execute($statement);

		$this->template->loadTemplateFile("/assessments/list.tpl", true, true);

		while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$this->template->setCurrentBlock("ASSESSMENTS_ROW");

			$this->template->setVariable("ASSESSMENT_DESC", htmlspecialchars($row["description"]));
			$this->template->setVariable("ASSESSMENT_ISWRITTENTEST", $row["isWrittenTest"] ? "Yes" : "No");
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
				$isWrittenTest = isset($_POST["isWrittenTest"]);
				if (strlen($description) > 100) {
					$isError = true;
					$this->template->setVariable("ERROR_DESC", "has-error");
					$this->template->touchBlock("ERROR_DESC_LONG");
				}

				// if and error occurred, fill inputs
				if ($isError) {
					$this->fillForm($description, $isWrittenTest, $editing);

					$this->template->parseCurrentBlock("ASSESSMENT_TYPES_EDIT");

				// else save
				} else {
					// update
					$msg = "";
					if ($editing) {
						if (!$this->updateAssessment($description, $isWrittenTest)) {
							$isError = true;
							$msg = "updating";
						} else {
							$msg = "updated";
						}
					// insert
					} else {
						if (!$this->insertAssessment($description, $isWrittenTest)) {
							$isError = true;
							$msg = "creating";
						} else {
							$msg = "created";
						}
					}
					if ($isError) {
						FlashMessage::add(FlashMessage::TYPE_ERROR, "An error occured when $msg assessment.");
					} else {
						$descriptionHtml = htmlspecialchars($description);
						FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Assessment type <i>$descriptionHtml</i> was successfuly $msg.");
					}
					OtherUtils::redirect("/assessments");
				}
			} else {
				if ($editing) {
					$this->fillForm($assessment["description"],$assessment["isWrittenTest"], true);
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
				$result2 = $this->deleteAssessment($id);

				$desc = htmlspecialchars($result["description"]);
				$errorMsg = "Assessment type <i>$desc</i> cannot be deleted. Check is there isn't any sylabus item referencing this record.";
				$successMsg = "Assessment type <i>$desc</i> was successfuly deleted.";

				OtherUtils::handleDeleteResult($_POST["delete"],  $result2, $errorMsg, $successMsg, "/assessments");
			} else {
				$this->template->loadTemplateFile("/assessments/delete.tpl", true, true);
				$this->template->setVariable("DELETE_ASSESSMENT_DESC", htmlspecialchars($result["description"]));
			}
		} else {
			if (isset($_POST["delete"]) && $_POST["delete"] == "ajax") {
				http_response_code(400);
				echo "Assessment type was not found.";
				exit;
			} else {
				FlashMessage::add(FlashMessage::TYPE_ERROR, "Assessment type was not found.");
				OtherUtils::redirect("/assessments");
			}
		}
	}

	private function insertAssessment($description, $isWrittenTest) {
		$tableName = "assessmenttype";

		$fieldsValues = array(
			"description"	=> $description,
			"isWrittenTest" => $isWrittenTest
		);

		return OtherUtils::improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_INSERT);
	}

	private function updateAssessment($description, $isWrittenTest) {
		$tableName = "assessmenttype";

		$fieldsValues = array(
			"description"	=> $description,
			"isWrittenTest" => $isWrittenTest
		);

		$id = $_GET["id"];

		return OtherUtils::improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_UPDATE, "assessmentTypeId = '$id'");
	}

	private function deleteAssessment($id) {
		$query = "DELETE FROM assessmenttype WHERE assessmentTypeId = ?";

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

	private function fillForm($description, $isWrittenTest, $editing) {
		$this->template->setCurrentBlock("ASSESSMENT_TYPES_EDIT");

		$this->template->setVariable("VALUE_DESC", htmlspecialchars($description));
		$this->template->setVariable("VALUE_ISWRITTENTEST",  $isWrittenTest ? "checked" : "");

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
