<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/components/Deliveries.php";
$deliveries = new App\Components\Deliveries($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$template->setGlobalVariable("TITLE", "List of modes of delivery – ");
		$deliveries->renderList();
		break;
	case "add":
		$template->setGlobalVariable("TITLE", "Add new mode of delivery – ");
		$deliveries->renderAdd();
		break;
	case "edit":
		$template->setGlobalVariable("TITLE", "Edit mode of delivery – ");
		$deliveries->renderEdit();
		break;
	case "delete":
		$template->setGlobalVariable("TITLE", "Delete mode of delivery – ");
		$deliveries->renderDelete();
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_DELIVERY", "active");
$template->parseCurrentBlock("ADMIN_MENU");
