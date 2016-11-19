<?php

namespace App;

session_start();

// PEAR IT
require_once("lib/Sigma.php");

// Utils functions
require_once("app/utils/FlashMessage.php");

// get base path
$_SESSION["basePath"] = str_replace("/404.php", "", $_SERVER["PHP_SELF"]);

// prepare templates
$template = new \HTML_Template_Sigma("template/");
$template->loadTemplateFile("/404.tpl", true, true);

// parse header
if (isset($_SESSION["user"])) {
	$template->setCurrentBlock("HEADER");
	$template->setVariable("USER_NAME", $_SESSION["user"]["fullName"]);
	$template->parseCurrentBlock("HEADER");
} else {
	$template->setVariable("UNLOGGED_PAGE", " unlogged-page");
}

//show flash messages
Utils\FlashMessage::show($template);

// apply base path
$template->setGlobalVariable("BASE_PATH", $_SESSION["basePath"]);

// show and finish
$template->show();
