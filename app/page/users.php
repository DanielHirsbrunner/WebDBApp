<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete", "modules"]);

require "app/components/Users.php";
$users = new App\Components\Users($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$users->renderList();
		break;
	case "add":
		$users->renderAdd();
		break;
	case "edit":
		$users->renderEdit();
		break;
	case "delete":
		$users->renderDelete();
		break;
}
