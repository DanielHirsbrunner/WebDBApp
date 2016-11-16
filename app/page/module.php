<?php

$template->loadTemplateFile("module/list.tpl", true, true);

if (isset($_SESSION['syllabus'])) {
	unset($_SESSION['syllabus']);
}
if (isset($_SESSION['wizStep'])) {
	unset($_SESSION['wizStep']);
}
if (isset($_SESSION['syllabusId'])) {
	unset($_SESSION['syllabusId']);
}
		
if (isset($_GET["id"])) {
	$_SESSION['moduleId'] = $_GET["id"];
	// module title and link for new syllabus
	$moduleStmt  = getSyllabusesByModuletmt($_SESSION['moduleId']);
	if ($result = $db->ExecuteSelectStmt($moduleStmt)) {
		while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$template->setCurrentBlock("SYLLABUS_LIST");
			$template->setVariable("SYLLABUS_VERSION", $row['versionNr'] .'.' . $row['revisionNr']);
			$template->setVariable("SYLLABUS_ID", $row['syllabusId']);
			$template->setVariable("SYLLABUS_EDITBY",  htmlspecialchars(stripslashes($row['editBy'])));
			$template->setVariable("SYLLABUS_EDITTS",  htmlspecialchars(stripslashes($row['editTS'])));
			$template->parseCurrentBlock("SYLLABUS_LIST");
		}
	}
} else {
	echo 'Plaease Select one modul on the side';
}

include 'app/moduleBar.inc';