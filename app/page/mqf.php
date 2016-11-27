<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/components/MQF.php";
$mqf = new App\Components\MQF($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$template->setGlobalVariable("TITLE", "List of MQF skills – ");
		$mqf->renderList();
		break;
	case "add":
		$template->setGlobalVariable("TITLE", "Add new MQF skill – ");
		$mqf->renderAdd();
		break;
	case "edit":
		$template->setGlobalVariable("TITLE", "Edit MQF skill – ");
		$mqf->renderEdit();
		break;
	case "delete":
		$template->setGlobalVariable("TITLE", "Delete MQF skill – ");
		$mqf->renderDelete();
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_MQF", "active");
$template->parseCurrentBlock("ADMIN_MENU");
