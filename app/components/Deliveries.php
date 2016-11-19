<?php

namespace App\Components;

use \App\Utils\OtherUtils, \App\Utils\FlashMessage;

/**
 * Class for managing of CRUD operations on mode of delivery
 */
class Deliveries {

	private $db;
	private $template;

	public function __construct($db, $template) {
		$this->db = $db;
		$this->template = $template;
	}

	public function renderList() {

		$query = "SELECT modeOfDeliveryId, description FROM modeofdelivery";

		$statement = $this->db->prepare($query);
		$result = $this->db->execute($statement);

		$this->template->loadTemplateFile("/deliveries/list.tpl", true, true);

		while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$this->template->setCurrentBlock("DELIVERY_ROW");

			$this->template->setVariable("DELIVERY_DESC", $row["description"]);
			$this->template->setVariable("DELIVERY_ID", $row["modeOfDeliveryId"]);

			$this->template->parseCurrentBlock("DELIVERY_ROW");
		}
	}
	
	public function renderAdd() {
		$this->renderEdit(false);
	}

	public function renderEdit($editing = true) {
		if ($editing) {
			$id = $_GET["id"];
			$delivery = $this->getModeOfDeliveryById($id);
		}

		$this->template->loadTemplateFile("/deliveries/edit.tpl", true, true);

		// is editing and not found by id
		if ($editing && !$delivery) {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Mode of delivery was not found.");
			OtherUtils::redirect("/deliveries", true, 303);
		} else {

			// submitting
			if (isset($_POST["description"])) {

				$this->template->setCurrentBlock("DELIVERY_EDIT");

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

					$this->template->parseCurrentBlock("DELIVERY_EDIT");

				// else save
				} else {
					// update
					$msg = "";
					if ($editing) {
						$this->updateModeOfDelivery($description);
						$msg = "updated";
					// insert
					} else {
						$this->insertModeOfDelivery($description);
						$msg = "created";
					}

					FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Mode of delivery was successfuly $msg.");
					OtherUtils::redirect("/deliveries");
				}
			} else {
				if ($editing) {
					$this->fillForm($delivery["description"], true);
				} else {
					$this->template->setCurrentBlock("DELIVERY_EDIT");
					$this->template->setVariable("VALUE_BUTTON", "Create mode of delivery");
					$this->template->parseCurrentBlock("DELIVERY_EDIT");
				}
			}

		}
	}

	public function renderDelete() {
		$id = $_GET["id"];
		$result = $this->getModeOfDeliveryById($id);
		if ($result) {
			// submitting
			if (isset($_POST["delete"])) {
				$this->deleteModeOfDelivery($id);
				$desc = $result["description"];
				FlashMessage::add(FlashMessage::TYPE_SUCCESS, "Mode of delivery <i>$desc</i> was successfuly deleted.");
				OtherUtils::redirect("/deliveries");
			} else {
				$this->template->loadTemplateFile("/deliveries/delete.tpl", true, true);
				$this->template->setVariable("DELETE_DELIVERY_DESC", $result["description"]);
			}
		} else {
			FlashMessage::add(FlashMessage::TYPE_ERROR, "Mode of delivery was not found.");
			OtherUtils::redirect("/deliveries", true, 303);
		}
	}

	private function insertModeOfDelivery($description) {
		$tableName = "modeofdelivery";

		$fieldsValues = array(
			"description"	=> $description
		);

		$this->db->autoExecute($tableName, $fieldsValues, DB_AUTOQUERY_INSERT);
	}

	private function updateModeOfDelivery($description) {
		$tableName = "modeofdelivery";

		$fieldsValues = array(
			"description"	=> $description
		);

		$id = $_GET["id"];

		$this->db->autoExecute($tableName, $fieldsValues, DB_AUTOQUERY_UPDATE, "modeOfDeliveryId = '$id'");
	}

	private function deleteModeOfDelivery($id) {
		$query = "DELETE FROM modeofdelivery WHERE modeOfDeliveryId = ?";

		$statement = $this->db->prepare($query);
		$params = [$id];

		$result = $this->db->execute($statement, $params);
	}

	private function fillForm($description, $editing) {
		$this->template->setCurrentBlock("DELIVERY_EDIT");

		$this->template->setVariable("VALUE_DESC", htmlspecialchars($description));

		$button = $editing ? "Update mode of delivery" : "Create mode of delivery";
		$this->template->setVariable("VALUE_BUTTON", $button);

		$this->template->parseCurrentBlock("DELIVERY_EDIT");
	}

	public function getModeOfDeliveryById($id) {
		$query = "SELECT * FROM modeofdelivery WHERE modeOfDeliveryId = ?";

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
