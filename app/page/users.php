<?php

if ($_SESSION["user"]["isAdmin"] != 1) {
	App\Utils::redirect("/");
}

$action = "";
if (isset($_GET["action"])) {
	$tempAction = $_GET["action"];
	$whitelist = ["add", "edit", "delete", "modules"];
	if (in_array($tempAction, $whitelist)) {
		$action = $tempAction;
	} else {
		// TODO uknown action, redirect to list
	}
} else {
	$action = "list";
}

require "/../components/Users.php";
$users = new App\Components\Users($db, $template);

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
