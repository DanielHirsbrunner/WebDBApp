<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/components/Assessments.php";
$assessments = new App\Components\Assessments($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$template->setGlobalVariable("TITLE", "List of assessment types – ");
		$assessments->renderList();
		break;
	case "add":
		$template->setGlobalVariable("TITLE", "Add new assessment types – ");
		$assessments->renderAdd();
		break;
	case "edit":
		$template->setGlobalVariable("TITLE", "Edit assessment types – ");
		$assessments->renderEdit();
		break;
	case "delete":
		$template->setGlobalVariable("TITLE", "Delete assessment types – ");
		$assessments->renderDelete();
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_ASSESSMENTS", "active");
$template->parseCurrentBlock("ADMIN_MENU");
