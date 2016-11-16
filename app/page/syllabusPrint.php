<!DOCTYPE html>
<html>
<head> 
    <title>Print Syllabus</title>
	<?php
		echo '<link rel="stylesheet" type="text/css" href="' . $_SESSION["basePath"] . '/css/syllabus.css">'; 
		echo '<link rel="stylesheet" type="text/css" href="' . $_SESSION["basePath"] . '/css/bootstrap.min.css">'; 
		echo '<link rel="stylesheet" type="text/css" href="' . $_SESSION["basePath"] . '/css/syllabusPrint.css">'; 
	?>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
	
</head>
<body>
<div id="syllabusPrint">
<?php
	// Initial Part
	include_once('/../formGenerator.inc');
	$fg = App\formGenerator::Instance();
	$syllabusId = 0;
	if (isset($_GET["id"])) {
		$syllabusId = $_GET["id"];
	}
	$fg->initSyllabus(0, $syllabusId);
	$pageHeader = $fg->getModuleHead(true);
	echo str_replace('PageNumber', '1 of 4', $pageHeader);
	echo $fg->getModuleInfo(true);
	echo $fg->getAcademicStaff(true);
	echo $fg->getModulePurpose(true);
	echo $fg->getSemester(true);
	echo $fg->getModuleCreditValue(true);
	// TODO: get Prerequisite
	echo $fg->getLearningOutcome(true);
	echo $fg->getTransferableSkills(true);
	echo $fg->getTeachLearnAssesStrategies(true);
	echo $fg->getSynopsis(true);
	echo '<div id="pageBreak"  class="pageBreak"></div>';
	echo str_replace('PageNumber', '2 of 4', $pageHeader);
	echo $fg->getModeOfDelivery(true);
	// TODO: Assessment Methods and Types
	echo $fg->getAssessmentCode(true);
	echo $fg->getModulAimsMapping(true);
	echo $fg->getLearningOutcomeMapping(true);
	echo '<div id="pageBreak"  class="pageBreak"></div>';
	echo str_replace('PageNumber', '3 of 4', $pageHeader);
	echo $fg->getTopics(true);
	
	echo '<div id="pageBreak"  class="pageBreak"></div>';
	echo str_replace('PageNumber', '4 of 4', $pageHeader);
	echo $fg->getMainReferences(true);
	echo $fg->getAddReferences(true);
	echo $fg->getOtherAddInformation(true);
	exit();
?>
</div>
</body>
</html>