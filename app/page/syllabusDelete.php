<?php
	$template->loadTemplateFile("syllabus.tpl", true, true);
	$content = '<form method="post">';
	$currMod = '';
	if (isset($_SESSION['moduleId'])) {
		$currMod = $_SESSION['moduleId'];
	}
	
	// Initial Part
	$syllabusId = 0;
	if (isset($_GET["id"])) {
		$syllabusId = $_GET["id"];
	}
	$moduleId = 0;
	if (isset($_SESSION['moduleId'])) {
		$moduleId = $_SESSION['moduleId'];
	}
	
	if (isset($_POST['delete'])) {
		$stmt = 'call deleteSyllabus(' . $syllabusId . ')';
		$db->ExecuteSelectStmt($stmt);
	}
	if(isset($_POST['chancel']) || isset($_POST['delete'])) {
		echo 'redirect to module page';
		app\Utils::redirect('/module/'.$moduleId);
		die();
	}
	
	$content = '<H3>Delete Syllabus</H3>';
	$content .= '<p>Are you sure you want to delete the following syllabus?</p>';
	$stmt  = getSyllabusInitStmt($syllabusId);
	if ($result = $db->ExecuteSelectStmt($stmt)) {
		if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$content .= '<p><b>Module: ' . $row['moduleName'] . ' Syllabus Version Nr ' . $row['versionNr'] . '.' . $row['revisionNr'] . '</b></p>';
		}
	}
	$content .= '<form  method="post"><input type="submit" name="chancel" value="Chancel" />';
	$content .= '<input type="submit" name="delete" value="Delete" /></form>';
	
	$template->setVariable("SYLLABUS_CONTENT", $content);
	
	include 'app/moduleBar.inc';
?>
