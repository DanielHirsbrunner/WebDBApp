<?php

namespace App;

session_start();

define("DEBUGGING", true);

// DB
require_once("lib/DB.php");
require_once("app/db/connection.inc");
require_once("app/db/ConnHelper.inc");
require_once("app/db/queryHelper.inc");

// PEAR IT
require_once("lib/Sigma.php");

// Utils functions
require_once("lib/password.php");
require_once("app/utils/OtherUtils.php");
require_once("app/utils/FormGenerator.inc");
require_once("app/utils/FlashMessage.php");

if (DEBUGGING) {
	error_reporting(-1);
	error_reporting(E_ALL);
}

// get base path
$_SESSION["basePath"] = str_replace("/index.php", "", $_SERVER["PHP_SELF"]);

// Init connection
$db = DB\ConnHelper::Instance();

// prepare templates
$template = new \HTML_Template_Sigma("template/");

// get page
$page = isset($_GET["page"]) ? $_GET["page"] : "";

// is not logged in
if ($page != "login" && !isset($_SESSION["user"])) {
	$continue = $_SERVER['REQUEST_URI'];
	if ($_SESSION["basePath"] != "") {
		$continue = preg_split("@".$_SESSION["basePath"]."@", $continue)[1];
	}
	Utils\FlashMessage::add(Utils\FlashMessage::TYPE_AUTH, "You have to log in to access this page.");
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

// load correct page
if ($page != "") {
	$whitelist = ["module", "syllabusWizard", "syllabusDelete", "syllabusPrint", "syllabusTopic","syllabusTopicDelete",
				  "login", "logout", "users", "modules", "assessments", "mqf", "activities", "deliveries"];
	if (in_array($page, $whitelist)) { // already done in .htaccess, so once it is here, it should be always true
		include "app/page/".$page.".php";
	}
} else {
	include "app/page/index.php";
}

// parse header
if (isset($_SESSION["user"])) {
	$template->setCurrentBlock("HEADER");
	$template->setVariable("USER_NAME", $_SESSION["user"]["fullName"]);
	$template->parseCurrentBlock("HEADER");
}

//show flash messages
Utils\FlashMessage::show($template);

// apply base path
$template->setGlobalVariable("BASE_PATH", $_SESSION["basePath"]);

// show and finish
$template->show();
