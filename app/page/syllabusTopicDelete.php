<?php
	$template->loadTemplateFile("syllabus.tpl", true, true);
	$content = '<form method="post">';
	$syllabusTopicId = 0;
	if (isset($_GET["id"])) {
		$syllabusTopicId = $_GET["id"];
	}
	$syllabusId = 0;
	if (isset($_SESSION["syllabusId"])) {
		$syllabusId = $_SESSION["syllabusId"];
	}
	$moduleId = 0;
	if (isset($_SESSION["moduleId"])) {
		$moduleId = $_SESSION["moduleId"];
	}
	
	if (isset($_POST['delete'])) {
		$stmt = 'DELETE FROM syllabusTopic WHERE syllabusTopicId =' . $syllabusTopicId . '';
		$db->ExecuteSelectStmt($stmt);
	}
	if(isset($_POST['cancel']) || isset($_POST['delete'])) {
		app\Utils::redirect('/syllabusWizard/'.$syllabusId);
		die();
	}
	
	$content .= '<h3>Delete Topic</h3>';
	$content .= '<p>Are you sure you want to delete the following Topic?</p>';
	$topicStmt  = getSingleTopicStmt($syllabusTopicId);
	if ($result = $db->ExecuteSelectStmt($topicStmt)) {
		if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$content .= '<p><b>Nr: ' . $row['topicNr'] . ' - ' . $row['description'] . '</b></p>';
		}
	}
	$content .= '<div id="divSubmitButtons">';
	$content .= '<input type="submit" name="cancel" value="Cancel" class="btnBack btn btn-default" />';
	$content .= '<input type="submit" name="delete" value="Delete" class="btnSave btn btn-primary"/>';
	$content .= '</div></form>';
	
	$template->setVariable("SYLLABUS_CONTENT", $content);
	
	include 'app/moduleBar.inc';
?>