<?php

namespace App;

session_start();

// DB
require_once("app/dbConnect.php");
// PEAR IT
require_once("lib/Sigma.php");
// Utils functions
include_once("app/Utils.php");

// get base path
$basePath = "/".preg_split("@/@", $_SERVER['REQUEST_URI'])[1];
$_SESSION["basePath"] = $basePath;

// prepare templates
$template = new \HTML_Template_Sigma("template/");

// get page
$page = isset($_GET["page"]) ? $_GET["page"] : "";

// is not logged in
if ($page != "login" && !isset($_SESSION["user"])) {
	$continue = preg_split("@".$basePath."@", $_SERVER['REQUEST_URI'])[1];
	Utils::redirect("/login?continue=".$continue);
}

// is logged in and trying to go to login page -> redirect to index
if ($page == "login" && isset($_SESSION["user"])) {
	if ($_SESSION["user"]["isAdmin"]) {
		Utils::redirect("/users");
	} else {
		Utils::redirect("/");
	}
}

$_404 = false;
// load correct page
if ($page != "") {
	$whitelist = ["module", "edit", "delete", "login", "logout", "users"];
	if (in_array($page, $whitelist)) {
		include "app/page/".$page.".php";
	} else {
		$_404 = true;
	}
} else {
	include "app/page/index.php";
}

// parse header
if ($page != "login" && isset($_SESSION["user"])) {
	$template->setCurrentBlock("HEADER");
	$template->setVariable("USER_NAME", $_SESSION["user"]["fullName"]);
	$template->parseCurrentBlock("HEADER");
}

// apply base path
$template->setGlobalVariable("BASE_PATH", $basePath);

// show and finish
$template->show();
