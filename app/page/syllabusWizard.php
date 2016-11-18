<?php
	$template->loadTemplateFile("syllabus.tpl", true, true);
	$content = '<form method="post">';
	$currMod = '';
	if (isset($_SESSION['moduleId'])) {
		$currMod = $_SESSION['moduleId'];
	}
	
	// check if a submit button was pressed
	if(isset($_POST['save']) || isset($_POST['saveRevision']) || isset($_POST['saveVersion']) || isset($_POST['goBack'])) {
		require_once('/../syllabusSaver.inc');
	}
	
	
	// Initial Part
	$fg = App\Utils\formGenerator::Instance();
	$syllabusId = 0;
	if (isset($_GET["id"])) {
		$syllabusId = $_GET["id"];
	}
	$moduleId = 0;
	if (isset($_SESSION['moduleId'])) {
		$moduleId = $_SESSION['moduleId'];
	}
	$fg->initSyllabus($moduleId, $syllabusId);
	
	// WizardStep
	if (!isset($_SESSION['wizStep'])) {
		$_SESSION['wizStep'] = 1;
	}
	$wizStep = $_SESSION['wizStep'];
	
	if ($wizStep == 1) {
		$content .= '<h3>Step 1 of 4 - Insert general Module Information</h3>';
		$content .= $fg->getModuleInfo(false);
		$content .= $fg->getAcademicStaff(false);
		$content .= $fg->getAssessmentCode(false);
		$content .= $fg->getSemester(false);
		$content .= $fg->getLearningOutcome(false);
		$content .= $fg->getTransferableSkills(false);
		$content .= $fg->getSynopsis(false);
		
		$versionNr = 0;
		$revision = 0;
		if (isset($_SESSION['syllabus'])) {
			$versionNr = $_SESSION['syllabus']['versionNr'];
			$revision = $_SESSION['syllabus']['revisionNr'];
		}
		$nextVersionNr = getNextVersionByModul($moduleId);
		$content .= '<div id="divSubmitButtons">';
		$content .= '<input type="submit" name="goBack" value="Go back to overview" class ="btnBack btn btn-default"/>';
		if ($versionNr == 0) {
			$content .= '<input type="submit" name="saveVersion" value="Save as Version '. $nextVersionNr . '.0 and continue" class="btnSave btn btn-primary"/>';
		} else {                                           
			$content .= '<input type="submit" name="save" value="Save as current version ' . $versionNr . '.' . $revision . '" class="btnSave btn btn-default"/>';
			$content .= '<input type="submit" name="saveRevision" value="Save as new revision ' . $versionNr . '.' . ($revision + 1) . '" class="btnSave btn btn-default"/>';
			$content .= '<input type="submit" name="saveVersion" value="Save as new version ' . $nextVersionNr . '.0" class="btnSave btn btn-default"/>';
		}
		$content .= '</div>';
		
	} else if ($wizStep == 2) {
		$content .= '<H3>Step 2 of 4 - Assign related properties</H3>';
		$content .= $fg->getTeachLearnAssesStrategies(false);
		$content .= $fg->getModulAimsMapping(false);
		$content .= $fg->getLearningOutcomeMapping(false);
		$content .= $fg->getModeOfDelivery(false);
		
		$content .= '<div id="divSubmitButtons">';
		$content .= '<input type="submit" name="goBack" value="Go back to Step 1" class ="btnBack btn btn-default" />';
		$content .= '<input type="submit" name="save" value="Save and continue" class="btnSave btn btn-primary" />';
		$content .= '</div>';
		
	} else if ($wizStep == 3) {
		$content .= '<H3>Step 3 of 4 - Add the topics to the module</H3>';
		$content .= $fg->getTopics(false);
		$content .= '<div id="divSubmitButtons">';
		$content .= '<input type="submit" name="goBack" value="Go back to Step 2" class ="btnBack btn btn-default" />';
		$content .= '<input type="submit" name="save" value="Save and continue" class="btnSave btn btn-primary" />';
		$content .= '</div>';
		
	} else if ($wizStep == 4) {
		$content .= '<H3>Step 4 of 4 - Define the module references</H3>';
		$content .= $fg->getMainReferences(false);
		$content .= $fg->getAddReferences(false);
		$content .= $fg->getOtherAddInformation(false);
		
		$content .= '<div id="divSubmitButtons">';
		$content .= '<input type="submit" name="goBack" value="Go back to Step 3" class ="btnBack btn btn-default" />';
		$content .= '<input type="submit" name="save" value="Save and finish" class="btnSave btn btn-primary"/>';
		$content .= '</div>';
	}
	$content .= '</form>';
	$template->setVariable("SYLLABUS_CONTENT", $content);
	
	include 'app/moduleBar.inc';
?>
