<?php

// is there an attempt to log in?
if (isset($_POST["username"]) && isset($_POST["password"])) {
	$username = $_POST["username"];
	$password = $_POST["password"];

	$query = "SELECT userId, userName, password, name, surname, email, qualification, isAdmin
				FROM user 
				WHERE userName = ?";

	$statement = $db->prepare($query);
	$params = [$username];

	$result = $db->execute($statement, $params);
	if (\DB::isError($result)) {
		return false;
	}

	$numRows = $result->numRows();

	if ($numRows == 0) {
		// TODO error
	} else {
		$row = $result->fetchRow(DB_FETCHMODE_ASSOC);

		$options = [
			'cost' => 11,
		];

		// successful login
		if (password_verify($password, $row["password"])) {
			$_SESSION["user"] = [];
			$_SESSION["user"]["id"] = $row["userId"];
			$_SESSION["user"]["fullName"] = $row["name"]." ".$row["surname"];
			$_SESSION["user"]["username"] = $username;
			$_SESSION["user"]["isAdmin"] = $row["isAdmin"];

			$continue = isset($_GET["continue"]) ? $_GET["continue"] : "/";

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
	}

// not an attempt to login
} else {
	$template->loadTemplateFile("login.tpl", true, true);
	$template->setVariable("USERNAME_VALUE", "");
}
