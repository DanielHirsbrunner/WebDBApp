<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/components/Activities.php";
$modules = new App\Components\Activities($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$modules->renderList();
		break;
	case "add":
		$modules->renderAdd();
		break;
	case "edit":
		$modules->renderEdit();
		break;
	case "delete":
		$modules->renderDelete();
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_ACTIVITIES", "active");
$template->parseCurrentBlock("ADMIN_MENU");
