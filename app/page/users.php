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
	case "modules":

		require "app/components/UserModules.php";
		require "app/components/Modules.php";

		$modules = new App\Components\Modules($db->GetConnection(), $template);
		$userModules = new App\Components\UserModules($db->GetConnection(), $template, $users, $modules);

		if (isset($_GET["action2"])) {
			if ($_GET["action2"] == "add") {
				$userModules->addModule();
			} else { // ($_GET["action2"] == "remove") - it is the only other option (see .htaccess)
				$userModules->removeModule();
			}
		} else {
			$userModules->renderModules();
		}
		break;
}
