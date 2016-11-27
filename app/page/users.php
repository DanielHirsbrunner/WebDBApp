<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete", "modules"]);

require "app/components/Users.php";
$users = new App\Components\Users($db->GetConnection(), $template);

switch ($action) {
	case "list":
		$template->setGlobalVariable("TITLE", "List of users – ");
		$users->renderList();
		break;
	case "add":
		$template->setGlobalVariable("TITLE", "Add new user – ");
		$users->renderAdd();
		break;
	case "edit":
		$template->setGlobalVariable("TITLE", "Edit user – ");
		$users->renderEdit();
		break;
	case "delete":
		$template->setGlobalVariable("TITLE", "Delete user – ");
		$users->renderDelete();
		break;
	case "modules":
		$template->setGlobalVariable("TITLE", "Manage user's modules – ");

		require "app/components/UserModules.php";
		require "app/components/Modules.php";

		$modules = new App\Components\Modules($db->GetConnection(), $template);
		$userModules = new App\Components\UserModules($db->GetConnection(), $template, $users, $modules);

		if (isset($_GET["action2"])) {
			if ($_GET["action2"] == "add") {
				$userModules->addModule();
			} else if ($_GET["action2"] == "remove") { // - it is the only other option (see .htaccess)
				$userModules->removeModule();
			}
		} else {
			$userModules->renderModules();
		}
		break;
}

$template->setCurrentBlock("ADMIN_MENU");
$template->setVariable("MENU_USERS", "active");
$template->parseCurrentBlock("ADMIN_MENU");
