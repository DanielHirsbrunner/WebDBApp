<?php

App\Utils\OtherUtils::checkAdmin();

$action = App\Utils\OtherUtils::getAdminPageAction(["add", "edit", "delete"]);

require "app/components/Assessments.php";
$modules = new App\Components\Assessments($db->GetConnection(), $template);

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
