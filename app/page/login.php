<?php

// is there an attempt to log in?
if (isset($_POST["username"]) && isset($_POST["password"])) {
	$username = $_POST["username"];
	$password = $_POST["password"];

	require "app/components/Login.php";
	$login = new App\Components\Login($db->GetConnection());
	$result = $login->run($username, $password);

	if ($result) {

		if (isset($_GET["continue"])) {
			App\Utils::redirect($_GET["continue"]);
		} else if ($_SESSION["user"]["isAdmin"]) {
			App\Utils::redirect("/users");
		} else {
			App\Utils::redirect("/");
		}

	} else {
		$template->loadTemplateFile("login.tpl", true, true);

		$template->touchBlock("BAD_CREDENTIALS");

		$usernameValue = htmlspecialchars($username);
		$template->setVariable("USERNAME_VALUE", $usernameValue);

		$template->setVariable("ERROR_CLASS", " has-error");
	}

// not an attempt to login
} else {
	$template->loadTemplateFile("login.tpl", true, true);
	$template->setVariable("USERNAME_VALUE", "");
}
