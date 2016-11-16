<?php

//$template->loadTemplateFile("module/list.tpl", true, true);
$currMod = '';
if (isset($_SESSION['moduleId'])) {
	$currMod = $_SESSION['moduleId'];
}
// module title and link for new syllabus		
$moduleStmt  = getModulesByUserStmt($_SESSION["user"]['id']);
if ($result = $db->ExecuteSelectStmt($moduleStmt)) {
	$currMod = '';
	if (isset($_SESSION['moduleId'])) {
		$currMod = $_SESSION['moduleId'];
	}
	while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
		$template->setCurrentBlock("MODULE_LIST");
		$template->setVariable("MODULE_ID", $row['moduleId']);
		$template->setVariable("MODULE_NUMBER", stripslashes($row['code']));
		$template->setVariable("MODULE_DESCRIPTION", stripslashes($row['name']));
		$template->setVariable("SELECTED", ($currMod == $row['moduleId'] ? "selected" : ""));
		$template->parseCurrentBlock("MODULE_LIST");
	}
}
