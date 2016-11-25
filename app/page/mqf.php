<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/utils/autoExecute.inc";
require "app/components/MQF.php";
$modules = new App\Components\MQF($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$template->setGlobalVariable("TITLE", "List of MQF skills – ");
		$modules->renderList();
		break;
	case "add":
		$template->setGlobalVariable("TITLE", "Add new MQF skill – ");
		$modules->renderAdd();
		break;
	case "edit":
		$template->setGlobalVariable("TITLE", "Edit MQF skill – ");
		$modules->renderEdit();
		break;
	case "delete":
		$template->setGlobalVariable("TITLE", "Delete MQF skill – ");
		$modules->renderDelete();
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_MQF", "active");
$template->parseCurrentBlock("ADMIN_MENU");
