<?php

namespace App;

// PEAR IT
require_once("HTML/Template/IT.php");

// prepare templates
$template = new \HTML_Template_IT("template/");
$template->loadTemplateFile("edit.tpl", true, true);

	$template->setVariable("VERSION", "1");

$template->show();

?>
