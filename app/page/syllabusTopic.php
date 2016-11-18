<?php
	$template->loadTemplateFile("syllabus.tpl", true, true);
	$content = '<H3>Topic Details</H3>';
		$content .= '<form method="post">';
	
	// Initial Part
	$topicId = 0;
	if (isset($_GET["id"])) {
		$topicId = $_GET["id"];
	}
	$syllabusId = 0;
	if (isset($_SESSION["syllabusId"])) {
		$syllabusId = $_SESSION["syllabusId"];
	}
	
	if (isset($_POST['save'])) {
		$topicNr = trim($_POST['txttopicNr']);
		$description = addslashes(trim($_POST['txtdescription']));
		$guidedLearnLecture = trim($_POST['txtguidedLearnLecture']);
		$guidedLearnTutorial = trim($_POST['txtguidedLearnTutorial']);
		$guidedLearnPractical = trim($_POST['txtguidedLearnPractical']);
		$guidedLearnOther = trim($_POST['txtguidedLearnOther']);
		
		$indepLearnLecture = trim($_POST['txtindepLearnLecture']);
		$indepLearnTutorial = trim($_POST['txtindepLearnTutorial']);
		$indepLearnPractical = trim($_POST['txtindepLearnPractical']);
		$indepLearnOther = trim($_POST['txtindepLearnOther']);
		
		if ($topicId > 0) {
			$stmt  = "UPDATE syllabusTopic SET topicNr = $topicNr, description = '$description', guidedLearnLecture = $guidedLearnLecture, guidedLearnTutorial = $guidedLearnTutorial, guidedLearnPractical = $guidedLearnPractical, guidedLearnOther = $guidedLearnOther, ";
			$stmt .= "indepLearnLecture = $indepLearnLecture, indepLearnTutorial = $indepLearnTutorial, indepLearnPractical = $indepLearnPractical, indepLearnOther = $indepLearnOther ";
			$stmt .= 'WHERE syllabusTopicId = ' . $topicId;
			$db->ExecuteSelectStmt($stmt);
		} else {
			$stmt  = 'INSERT INTO syllabusTopic (syllabusId, topicNr, description, guidedLearnLecture, ';
			$stmt .= 'guidedLearnTutorial, guidedLearnPractical, guidedLearnOther, indepLearnLecture, indepLearnTutorial, indepLearnPractical, indepLearnOther) ';
			$stmt .= 'VALUES (' . $syllabusId . ', ' . $topicNr . ', \'' . $description . '\', ' . $guidedLearnLecture . ', ' . $guidedLearnTutorial . ', ' . $guidedLearnPractical . ', ' . $guidedLearnOther . ', ' . $indepLearnLecture . ', ' . $indepLearnTutorial . ', ' . $indepLearnPractical . ', ' . $indepLearnOther . ')';
			$db->ExecuteSelectStmt($stmt);
		}
	}
	
	if(isset($_POST['cancel']) || isset($_POST['save'])) {
		App\Utils\OtherUtils::redirect('/syllabusWizard/'.$syllabusId);
	}
	
	$topicNr = 0;
	$description = '';
	$guidedLearnLecture =0;
	$guidedLearnTutorial = 0;
	$guidedLearnPractical = 0;
	$guidedLearnOther = 0;
	$indepLearnLecture = 0;
	$indepLearnTutorial = 0;
	$indepLearnPractical = 0;
	$indepLearnOther = 0;
				
	$topicStmt  = getSingleTopicStmt($topicId);
	if ($result = $db->ExecuteSelectStmt($topicStmt)) {
		if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
			$topicId = $row['syllabusTopicId'];
			$topicNr = $row['topicNr'];
			$description = htmlspecialchars(stripslashes($row['description']));
			$guidedLearnLecture = $row['guidedLearnLecture'];
			$guidedLearnTutorial = $row['guidedLearnTutorial'];
			$guidedLearnPractical = $row['guidedLearnPractical'];
			$guidedLearnOther = $row['guidedLearnOther'];
			$indepLearnLecture = $row['indepLearnLecture'];
			$indepLearnTutorial = $row['indepLearnTutorial'];
			$indepLearnPractical = $row['indepLearnPractical'];
			$indepLearnOther = $row['indepLearnOther'];
		}
	}
	if ($topicNr == 0) {
		$topicNr = getNextTopicBySyllabus($syllabusId);
	}
	
	
	$table = '<table style="width:100%;">';//<tr><th style="width:200px;"/><th style="width:400px;" />';
	$table .= '<tr><td style="width:200px;">Topic number</td><td><input name="txttopicNr" type="text" Value="' . $topicNr . '" style="width:40px;text-align:right;"></td></tr>';
	$table .= '<tr><td style="width:200px;">Topic description</td><td><textArea name="txtdescription" rows="4" class="textArea" style="width:100%;">' . $description . '</textArea></td></tr>';
	$table .= '</table><br/>';
	$content .= $table;
	
	$table = '<table style="width:100%;"><tr><th>Learning</th><th class="tdNumber">Lecture</th><th class="tdNumber">Tutorial</th><th class="tdNumber">Practical</th><th class="tdNumber">Other</th></tr>';
	$table .= '<tr><td>Guided learning:</td>';
	$table .= '<td class=\"tdNumber\"><input name="txtguidedLearnLecture" type="text" Value="' . formatHours($guidedLearnLecture) . '" class="numberInput"></td>';
	$table .= '<td class=\"tdNumber\"><input name="txtguidedLearnTutorial" type="text" Value="' . formatHours($guidedLearnTutorial) . '" class="numberInput"></td>';
	$table .= '<td class=\"tdNumber\"><input name="txtguidedLearnPractical" type="text" Value="' . formatHours($guidedLearnPractical) . '" class="numberInput"></td>';
	$table .= '<td class=\"tdNumber\"><input name="txtguidedLearnOther" type="text" Value="' . formatHours($guidedLearnOther) . '" class="numberInput"></td></tr>';
	$table .= '<tr><td>Independant learning:</td>';
	$table .= '<td class=\"tdNumber\"><input name="txtindepLearnLecture" type="text" Value="' . formatHours($indepLearnLecture) . '" class="numberInput"></td>';
	$table .= '<td class=\"tdNumber\"><input name="txtindepLearnTutorial" type="text" Value="' . formatHours($indepLearnTutorial) . '" class="numberInput"></td>';
	$table .= '<td class=\"tdNumber\"><input name="txtindepLearnPractical" type="text" Value="' . formatHours($indepLearnPractical) . '" class="numberInput"></td>';
	$table .= '<td class=\"tdNumber\"><input name="txtindepLearnOther" type="text" Value="' . formatHours($indepLearnOther) . '" class="numberInput"></td></tr>';
	$table .= '</table>';
	$content .= $table;
			
	$content .= '<div id="divSubmitButtons">';
	$content .= '<input type="submit" name="cancel" value="Cancel"  class="btnBack"/>';
	$content .= '<input type="submit" name="save" value="Save"  class="btnSave"/>';
	$content .= '</div>';
	$content .= '</form>';
	
	$template->setVariable("SYLLABUS_CONTENT", $content);
	
	
	// Display all hours with 1 decimal point for half hours as 0.5
	function formatHours($hours) {
		return sprintf("%01.1f", $hours);
	}
	include 'app/moduleBar.inc';
?>
