<?php

namespace App\Components;

use \App\Utils\OtherUtils, \App\Utils\FlashMessage;

/**
 * Class for managing of CRUD operations on MQF skills
 */
class MQF {

	private $db;
	private $template;

	public function __construct($db, $template) {
		$this->db = $db;
		$this->template = $template;
	}

	public function renderList() {

		$query = "SELECT mqfSkillId, description FROM mqfSkill";

		$statement = $this->db->prepare($query);
		$result = $this->db->execute($statement);

		$this->template->loadTemplateFile("/mqf/list.tpl", true, true);

		while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$this->template->setCurrentBlock("MQF_ROW");

			$this->template->setVariable("MQF_DESC", htmlspecialchars($row["description"]));
			$this->template->setVariable("MQF_ID", $row["mqfSkillId"]);

			$this->template->parseCurrentBlock("MQF_ROW");
		}
	}
	
	public function renderAdd() {
		$this->renderEdit(false);
	}

	public function renderEdit($editing = true) {
		if ($editing) {
			$id = $_GET["id"];
			$mqf = $this->getMQFSkillById($id);
		}

		$this->template->loadTemplateFile("/mqf/edit.tpl", true, true);

		// is editing and not found by id
		if ($editing && !$mqf) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "MQF skill was not found.");
			OtherUtils::redirect("/mqf", true, 303);
		} else {

			// submitting
			if (isset($_POST["description"])) {

				$this->template->setCurrentBlock("MQF_SKILL_EDIT");

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

					$this->template->parseCurrentBlock("MQF_SKILL_EDIT");

				// else save
				} else {
					// update
					$msg = "";
					if ($editing) {
						if (!$this->updateMQF($description)) {
							$isError = true;
							$msg = "updating";
						} else {
							$msg = "updated";
						}
					// insert
					} else {
						if (!$this->insertMQF($description)) {
							$isError = true;
							$msg = "creating";
						} else {
							$msg = "created";
						}
					}
					if ($isError) {
						FlashMessage::add(FlashMessage::TYPE_ERROR, "An error occured when $msg MQF skill.");
					} else {
						$descriptionHtml = htmlspecialchars($description);
						FlashMessage::add(FlashMessage::TYPE_SUCCESS, "MQF skill <i>$descriptionHtml</i> was successfuly $msg.");
					}
					OtherUtils::redirect("/mqf");
				}
			} else {
				if ($editing) {
					$this->fillForm($mqf["description"], true);
				} else {
					$this->template->setCurrentBlock("MQF_SKILL_EDIT");
					$this->template->setVariable("VALUE_BUTTON", "Create MQF skill");
					$this->template->parseCurrentBlock("MQF_SKILL_EDIT");
				}
			}

		}
	}

	public function renderDelete() {
		$id = $_GET["id"];
		$result = $this->getMQFSkillById($id);
		if ($result) {
			// submitting
			if (isset($_POST["delete"])) {
				$result2 = $this->deleteMQF($id);

				$desc = htmlspecialchars($result["description"]);
				$errorMsg = "MQF skil <i>$desc</i> cannot be deleted. Check is there isn't any syllabus item referencing this record.";
				$successMsg = "MQF skil <i>$desc</i> was successfuly deleted.";

				OtherUtils::handleDeleteResult($_POST["delete"],  $result2, $errorMsg, $successMsg, "/mqf");
			} else {
				$this->template->loadTemplateFile("/mqf/delete.tpl", true, true);
				$this->template->setVariable("DELETE_MQF_DESC", htmlspecialchars($result["description"]));
			}
		} else {
			if (isset($_POST["delete"]) && $_POST["delete"] == "ajax") {
				http_response_code(400);
				echo "MQF skill was not found.";
				exit;
			} else {
				FlashMessage::add(FlashMessage::TYPE_ERROR, "MQF skill was not found.");
				OtherUtils::redirect("/mqf");
			}
		}
	}

	private function insertMQF($description) {
		$tableName = "mqfSkill";

		$fieldsValues = array(
			"description"	=> $description
		);

		return OtherUtils::improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_INSERT);
	}

	private function updateMQF($description) {
		$tableName = "mqfSkill";

		$fieldsValues = array(
			"description"	=> $description
		);

		$id = $_GET["id"];

		return OtherUtils::improvedAutoExecute($this->db, $tableName, $fieldsValues, DB_AUTOQUERY_UPDATE, "mqfSkillId = '$id'");
	}

	private function deleteMQF($id) {
		$query = "DELETE FROM mqfSkill WHERE mqfSkillId = ?";

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

	private function fillForm($description, $editing) {
		$this->template->setCurrentBlock("MQF_SKILL_EDIT");

		$this->template->setVariable("VALUE_DESC", htmlspecialchars($description));

		$button = $editing ? "Update MQF skill" : "Create MQF skill";
		$this->template->setVariable("VALUE_BUTTON", $button);

		$this->template->parseCurrentBlock("MQF_SKILL_EDIT");
	}

	public function getMQFSkillById($id) {
		$query = "SELECT * FROM mqfSkill WHERE mqfSkillId = ?";

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
