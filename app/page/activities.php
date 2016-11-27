<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/components/Activities.php";
$activities = new App\Components\Activities($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$template->setGlobalVariable("TITLE", "List of activities – ");
		$activities->renderList();
		break;
	case "add":
		$template->setGlobalVariable("TITLE", "Add new activity – ");
		$activities->renderAdd();
		break;
	case "edit":
		$template->setGlobalVariable("TITLE", "Edit activity – ");
		$activities->renderEdit();
		break;
	case "delete":
		$template->setGlobalVariable("TITLE", "Delete activity – ");
		$activities->renderDelete();
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_ACTIVITIES", "active");
$template->parseCurrentBlock("ADMIN_MENU");
