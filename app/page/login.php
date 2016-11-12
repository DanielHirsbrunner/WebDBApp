<?php

// is there an attempt to log in?
if (isset($_POST["username"]) && isset($_POST["password"])) {
	$username = $_POST["username"];
	$password = $_POST["password"];

	// login successful
	if ($username == "a" && $password == "b") {
		$_SESSION["user"] = [];
		$_SESSION["user"]["id"] = 1;
		$_SESSION["user"]["username"] = $username;
		$_SESSION["user"]["admin"] = true;

		$continue = "/";
		if (isset($_GET["continue"])) {
			$continue = $_GET["continue"];
		}

		header("Location: ".$continue, true, 303);
		die();

	// wrong login
	} else {
		$usernameValue = htmlspecialchars($username);
		$template->loadTemplateFile("login.tpl", true, true);
		$template->touchBlock("BAD_CREDENTIALS");
		$template->setVariable("USERNAME_VALUE", $usernameValue);
		$template->setVariable("ERROR_CLASS", " has-error");
	}
// not an attempt to login
} else {
	$template->loadTemplateFile("login.tpl", true, true);
	$template->setVariable("USERNAME_VALUE", "");
}
