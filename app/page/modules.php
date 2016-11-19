<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/components/Modules.php";
$modules = new App\Components\Modules($db->GetConnection(), $template);

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

$template->setVariable("MENU_MODULES", "active");
