<?php

// module title and link for new syllabus		
$moduleStmt  = getModulesByUserStmt($_SESSION["user"]['id']);
if ($result = $db->ExecuteSelectStmt($moduleStmt)) {
	$currMod = '';
	if (isset($_SESSION['moduleId']) && $page != "") {
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

if ($_SESSION["user"]["isAdmin"]) {
	$template->setCurrentBlock("ADMIN_MENU");
	$template->setVariable("MENU_SYLLABUSES", "active");
	$template->parseCurrentBlock("ADMIN_MENU");
}