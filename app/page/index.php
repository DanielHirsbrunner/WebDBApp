<?php

$template->loadTemplateFile("index.tpl", true, true);

$template->setCurrentBlock("MODULE_LIST");

$template->setVariable("MODULE_ID", "1");
$template->setVariable("MODULE_NUMBER", "ITS12345");
$template->setVariable("MODULE_ABBR", "DAD");
$template->setVariable("SELECTED", "selected");

$template->parseCurrentBlock("MODULE_LIST");

////////////////////////

$template->setCurrentBlock("MODULE_LIST");

$template->setVariable("MODULE_ID", "2");
$template->setVariable("MODULE_NUMBER", "ITS12346");
$template->setVariable("MODULE_ABBR", "WDA");
$template->setVariable("SELECTED", "");

$template->parseCurrentBlock("MODULE_LIST");
