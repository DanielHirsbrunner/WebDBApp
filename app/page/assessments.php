<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/components/Assessments.php";
$modules = new App\Components\Assessments($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$template->setGlobalVariable("TITLE", "List of assessment types – ");
		$modules->renderList();
		break;
	case "add":
		$template->setGlobalVariable("TITLE", "Add new assessment types – ");
		$modules->renderAdd();
		break;
	case "edit":
		$template->setGlobalVariable("TITLE", "Edit assessment types – ");
		$modules->renderEdit();
		break;
	case "delete":
		$template->setGlobalVariable("TITLE", "Delete assessment types – ");
		$modules->renderDelete();
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_ASSESSMENTS", "active");
$template->parseCurrentBlock("ADMIN_MENU");
