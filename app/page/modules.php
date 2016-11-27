<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/components/Modules.php";
require "app/components/Users.php";
$modules = new App\Components\Modules($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$template->setGlobalVariable("TITLE", "List of modules – ");
		$modules->renderList();
		break;
	case "add":
		$template->setGlobalVariable("TITLE", "Add new module – ");
		$modules->renderAdd();
		break;
	case "edit":
		$template->setGlobalVariable("TITLE", "Edit module – ");
		$modules->renderEdit();
		break;
	case "delete":
		$template->setGlobalVariable("TITLE", "Delete module – ");
		$modules->renderDelete();
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_MODULES", "active");
$template->parseCurrentBlock("ADMIN_MENU");
