<?php

namespace App;

session_start();

// DB
require_once("lib/DB.php");
require_once("app/conHelper.inc");

// PEAR IT
require_once("lib/Sigma.php");

// Utils functions
require_once("lib/password.php");
require_once("app/utils/OtherUtils.php");
require_once("app/queryHelper.inc");
require_once('app/formGenerator.inc');

//error_reporting(-1);
//error_reporting(E_ALL);

// echo $_SERVER["DOCUMENT_ROOT"];
// get base path
// $basePath = ""; //if not is present
$basePath = "/".preg_split("@/@", $_SERVER['REQUEST_URI'])[1]; // if one folder is present
$_SESSION["basePath"] = $basePath;

// Init connection
$db = conHelper::Instance();

// prepare templates
$template = new \HTML_Template_Sigma("template/");

// get page
$page = isset($_GET["page"]) ? $_GET["page"] : "";

// is not logged in
if ($page != "login" && !isset($_SESSION["user"])) {
	$continue = preg_split("@".$basePath."@", $_SERVER['REQUEST_URI'])[1];
	Utils\OtherUtils::redirect("/login?continue=".$continue);
}

// is logged in and trying to go to login page -> redirect to index
if ($page == "login" && isset($_SESSION["user"])) {
	if ($_SESSION["user"]["isAdmin"]) {
		Utils\OtherUtils::redirect("/users");
	} else {
		Utils\OtherUtils::redirect("/");
	}
}

$_404 = false;
// load correct page
if ($page != "") {
	$whitelist = ["module", "syllabusWizard", "syllabusDelete", "syllabusPrint", "syllabusTopic","syllabusTopicDelete", "login", "logout", "users"];
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
