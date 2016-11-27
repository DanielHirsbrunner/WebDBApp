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
			$fields_values = array(
				'topicNr' => $topicNr,
				'description' => $description,
				'guidedLearnLecture' => $guidedLearnLecture,
				'guidedLearnTutorial' => $guidedLearnTutorial,
				'guidedLearnPractical' => $guidedLearnPractical,
				'guidedLearnOther' => $guidedLearnOther,
				
				'indepLearnLecture' => $indepLearnLecture,
				'indepLearnTutorial' => $indepLearnTutorial,
				'indepLearnPractical' => $indepLearnPractical,
				'indepLearnOther' => $indepLearnOther
			);		
			$db->ExecuteUpdateStmt('syllabusTopic', $fields_values, 'syllabusTopicId = ' . $topicId);
			
		} else {
			$fields_values = array(
				'syllabusId' => $syllabusId,
				'topicNr' => $topicNr,
				'description' => $description,
				'guidedLearnLecture' => $guidedLearnLecture,
				'guidedLearnTutorial' => $guidedLearnTutorial,
				'guidedLearnPractical' => $guidedLearnPractical,
				'guidedLearnOther' => $guidedLearnOther,
				
				'indepLearnLecture' => $indepLearnLecture,
				'indepLearnTutorial' => $indepLearnTutorial,
				'indepLearnPractical' => $indepLearnPractical,
				'indepLearnOther' => $indepLearnOther
			);

			$res = $db->ExecuteInsertStmt('syllabusTopic', $fields_values);
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
	
	
	$table = '<table class="custom"><tbody>';//<tr><th style="width:200px;"/><th style="width:400px;" />';
	$table .= '<tr><td class="_25"><label for="txttopicNr">Topic number</label></td><td><input id="txttopicNr" name="txttopicNr" type="text" value="' . $topicNr . '" class="numberInput form-control pull-left"></td></tr>';
	$table .= '<tr><td class="_25"><label for="txtdescription">Topic description</label></td><td><textarea id="txtdescription" name="txtdescription" rows="4" class="textarea form-control">' . $description . '</textarea></td></tr>';
	$table .= '</tbody></table><br/>';
	$content .= $table;
	
	$table = '<table class="custom"><thead><tr><th>Learning</th><th class="tdNumber">Lecture</th><th class="tdNumber">Tutorial</th><th class="tdNumber">Practical</th><th class="tdNumber">Other</th></tr></thead>';
	$table .= '<tbody><tr><td>Guided learning</td>';
	$table .= '<td class="tdNumber"><input name="txtguidedLearnLecture" type="text" value="' . formatHours($guidedLearnLecture) . '" class="numberInput form-control"></td>';
	$table .= '<td class="tdNumber"><input name="txtguidedLearnTutorial" type="text" value="' . formatHours($guidedLearnTutorial) . '" class="numberInput form-control"></td>';
	$table .= '<td class="tdNumber"><input name="txtguidedLearnPractical" type="text" value="' . formatHours($guidedLearnPractical) . '" class="numberInput form-control"></td>';
	$table .= '<td class="tdNumber"><input name="txtguidedLearnOther" type="text" value="' . formatHours($guidedLearnOther) . '" class="numberInput form-control"></td></tr>';
	$table .= '<tr><td>Independant learning</td>';
	$table .= '<td class="tdNumber"><input name="txtindepLearnLecture" type="text" value="' . formatHours($indepLearnLecture) . '" class="numberInput form-control"></td>';
	$table .= '<td class="tdNumber"><input name="txtindepLearnTutorial" type="text" value="' . formatHours($indepLearnTutorial) . '" class="numberInput form-control"></td>';
	$table .= '<td class="tdNumber"><input name="txtindepLearnPractical" type="text" value="' . formatHours($indepLearnPractical) . '" class="numberInput form-control"></td>';
	$table .= '<td class="tdNumber"><input name="txtindepLearnOther" type="text" value="' . formatHours($indepLearnOther) . '" class="numberInput form-control"></td></tr>';
	$table .= '</tbody></table>';
	$content .= $table;
			
	$content .= '<div>';
	$content .= '<input type="submit" name="cancel" value="Cancel" class="pull-left btn btn-default"/>';
	$content .= '<input type="submit" name="save" value="Save" class="pull-right btn btn-primary"/>';
	$content .= '</div>';
	$content .= '<div class="clearfix"></div>';
	$content .= '</form>';
	
	$template->setVariable("SYLLABUS_CONTENT", $content);

	// Display all hours with 1 decimal point for half hours as 0.5
	function formatHours($hours) {
		return sprintf("%01.1f", $hours);
	}

	include 'app/moduleBar.inc';
