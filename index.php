<?php

namespace App;

session_start();

// DB
require_once("app/dbConnect.php");
// PEAR IT
require_once("HTML/Template/IT.php");

// prepare templates
$template = new \HTML_Template_IT("template/");

// is not logged in
if ( (isset($_GET["page"]) && $_GET["page"] != "login" && !isset($_SESSION["user"])) || (!isset($_GET["page"]) && !isset($_SESSION["user"])) ) {
	$continue = $_SERVER['REQUEST_URI'];
	header("Location: /login?continue=".$continue, true, 303);

	die();
}

// load correct page
if (isset($_GET["page"])) {
	$page = $_GET["page"];
	$whitelist = ["module", "edit", "delete", "login", "logout"];
	if (in_array($page, $whitelist)) {
		include "app/page/".$page.".php";
	}
} else {
	include "app/page/index.php";
}

// show and finish
$template->show();

?>
