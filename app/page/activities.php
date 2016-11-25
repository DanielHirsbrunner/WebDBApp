<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/utils/autoExecute.inc";
require "app/components/Activities.php";
$modules = new App\Components\Activities($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$template->setGlobalVariable("TITLE", "List of activities – ");
		$modules->renderList();
		break;
	case "add":
		$template->setGlobalVariable("TITLE", "Add new activity – ");
		$modules->renderAdd();
		break;
	case "edit":
		$template->setGlobalVariable("TITLE", "Edit activity – ");
		$modules->renderEdit();
		break;
	case "delete":
		$template->setGlobalVariable("TITLE", "Delete activity – ");
		$modules->renderDelete();
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_ACTIVITIES", "active");
$template->parseCurrentBlock("ADMIN_MENU");
