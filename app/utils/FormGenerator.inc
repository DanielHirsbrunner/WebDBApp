<?php

namespace App\Utils;

class FormGenerator
{
	static $instance = '';
	private $db = '';
	
	// Singelton Instance
	public static function Instance() {
		// check if thers already an instance object
		if (FormGenerator::$instance == '') {
			FormGenerator::$instance = new FormGenerator();
		}
		return FormGenerator::$instance;
	}
	
	private function __construct() {
		$this->db = \App\DB\ConnHelper::Instance();
	} 
	
	// Gets the Data from the syllabus into session variables
	public function initSyllabus($moduleId, $syllabusId) {
		$_SESSION['moduleId'] = $moduleId;
		$_SESSION['syllabusId'] = $syllabusId;
		
		$sylStmt  = getSyllabusInitStmt ($syllabusId);
		if ($result = $this->db->ExecuteSelectStmt($sylStmt)) {
			if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$_SESSION['syllabus'] = $row;
				if ($moduleId == 0) {
					$_SESSION['moduleId'] = $row['moduleId'];
				}
				
			}
		}
	}
	
	public function getModuleHead() {
		$content = '<div id="printHeader">';
		$moduleCode = $this->getSyllabusVarText('moduleCode');
		$moduleName = $this->getSyllabusVarText('moduleName');
		$effectiveDate = date("d.m.Y");
		$versionNr = $this->getSyllabusVarNr('versionNr');
		$revisionNr =  $this->getSyllabusVarNr('revisionNr');
		$approvedBy = $this->getSyllabusVarText('approvedBy');
		$approvalDate = $this->getSyllabusVarText('approvedTS');
		$content .= '<table><tr>';
		$content .= '<td style="width:25%;text-align:center;"><img src="' . $_SESSION["basePath"] . '/img/taylorsLogo.png" alt="Taylors\'s University" style="width:130px;"></td>';
		$content .= '<td style="width:35%;text-align:center;"><b>'. $moduleCode . ' ' . $moduleName . '<br/>Module Summary of Information</b></td>';
		$content .= '<td style="text-align:left;">';
		$content .= 	'<table>';
		$content .= 	"<tr><td style=\"width:100px;\">Effective Date</td><td>: $effectiveDate</td></tr>";
		$content .= 	"<tr><td style=\"width:100px;\">Revision</td><td>: $versionNr.$revisionNr </td></tr>";
		$content .= 	'<tr><td style="width:100px;">Page</td><td>: PageNumber</td></tr>';
		$content .= 	"<tr><td style=\"width:100px;\">Approved By</td><td>: $approvedBy</td></tr>";
		$content .= 	"<tr><td style=\"width:100px;\">Approval Date</td><td>: $approvalDate</td></tr>";
		$content .= 	'</table>';
		$content .= '</td>';
		$content .= '</tr></table>';
		$content .= '</div>';
		return $content;
	}
	
	
	public function getModuleInfo($print) {
		$divName = 'divModul';
		$inputLabel = 'Module:';
		$syllabusId = 0;
		if (isset($_SESSION['syllabusId'])) {
			$syllabusId = $_SESSION['syllabusId'];
		}			
		$currModule = 0;
		$currModuleName = $this->getSyllabusVarText('moduleName');
		$currModuleCode = $this->getSyllabusVarText('moduleCode');
		if (isset($_SESSION['moduleId'])) {
			$currModule = $_SESSION['moduleId'];
		}
		if ($print) {
			$retval = $this->getDiv($divName, $inputLabel, $this->getTextLabel('<b>' . $currModuleName . '</b>'));
			$retval .= $this->getDiv('divModuleCode', 'Module Code:', $this->getTextLabel($currModuleCode));
			return $retval;
			
		} else {
			$inputPart = '<select name="selModul">';
			
			if ($result = $this->db->ExecuteSelectStmt('SELECT moduleId, code, name from module order by code')) {
				while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
					$modCode = htmlspecialchars(stripslashes($row['code']));
					$modName = htmlspecialchars(stripslashes($row['name']));
					$modId = $row['moduleId'];
					$selected = ($modId == $currModule ? " selected" : "");
					$inputPart .= "<option$selected value=\"$modId\">$modCode - $modName</option>";
				}
			}
			$inputPart .= '</select>';
			return $this->getDiv($divName, $inputLabel, $inputPart);
		}
	}
	
	public function getModulePurpose($print) {
		$divName = 'divModulePurpose';
		$inputLabel = 'Rationale for the inclusion of the module in the programme:';
		$currModulePurpose = $this->getSyllabusVarText('modulePurpose');
		
		if ($print) {
			return $this->getDiv($divName, $inputLabel, $this->getTextLabel($currModulePurpose));
		} else {
			$inputPart = $this->getTextInput('txtModulePurpose', $currModulePurpose);
			return $this->getDiv($divName, $inputLabel, $inputPart);
		}
	}
	
	public function getModuleCreditValue($print) {
		$divName = 'divModuleCreditValue';
		$inputLabel = 'Credit Value:';
		$currModuleCredits = $this->getSyllabusVarNr('credits');
		
		if ($print) {
			return $this->getDiv($divName, $inputLabel, $this->getTextLabel($currModuleCredits));
		} else {
			$inputPart = $this->getTextInput('txtModuleCredits', $currModuleCredits);
			return $this->getDiv($divName, $inputLabel, $inputPart);
		}
	}
	
	public function getAcademicStaff($print) {
		$divName = 'divStaff';
		$inputLabel = 'Names of academic staff' . ($print ? ' (Qualification)':'') . ':';
		$currStaff = $this->getSyllabusVarNr('academicStaff');
		
		if ($print) {
			$currStaffName = $this->getSyllabusVarText('academicStaffName');
			$currStaffQualification= $this->getSyllabusVarText('academicStaffQualification');
			$value = $this->getTextLabel($currStaffName. '<br/>' . $currStaffQualification);
			return $this->getDiv($divName, $inputLabel, $value);
			
		} else {
			$inputPart = '<select name="selAcademicStaff">';
			
			$moduleStmt = 'SELECT userId, name, surname from user order by name, surname';
			if ($result = $this->db->ExecuteSelectStmt($moduleStmt)) {
				while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
					$userName = htmlspecialchars(stripslashes($row['name']));
					$userSurname = htmlspecialchars(stripslashes($row['surname']));
					$userId = $row['userId'];
					$selected = ($userId == $currStaff ? " selected" : "");
					$inputPart .= "<option$selected value=\"$userId\">$userName - $userSurname</option>";
				}
			}
			$inputPart .= '</select>';
			return $this->getDiv($divName, $inputLabel, $inputPart);
		}
	}
	
	public function getSemester($print) {
		$divName = 'divSemester';
		$inputLabel = 'Semester and Year offered:';
		$currSemester = $this->getSyllabusVarText('semester');
		
		if ($print) {
			return $this->getDiv($divName, $inputLabel, $this->getTextLabel($currSemester));
		} else {
			$inputPart = $this->getTextInput('txtsemester', $currSemester);
			return $this->getDiv($divName, $inputLabel, $inputPart);
		}
	}
	
	public function getAssessmentCode($print) {
		$divName = 'divAssessmentCode';
		$inputLabel = 'Assessment Code:';
		$currValue = $this->getSyllabusVarText('assessmentCode');
		
		if ($print) {
			return $this->getDiv($divName, $inputLabel, $this->getTextLabel($currValue));
		} else {
			$inputPart = $this->getTextInput('txtassessmentCode', $currValue);
			return $this->getDiv($divName, $inputLabel, $inputPart);
		}
	}
	
	public function getLearningOutcome($print) {
		$fieldName = 'learningOutcomes';
		$inputLabel = 'Learning Outcomes:';
		return $this->getTextAreaDiv($print, $fieldName, $inputLabel);
	}
	
	public function getTransferableSkills($print) {
		$fieldName = 'transferableSkills';
		$inputLabel = 'Transferable Skills:';
		return $this->getTextAreaDiv($print, $fieldName, $inputLabel);
	}
	
	public function getSynopsis($print) {
		$fieldName = 'synopsis';
		$inputLabel = 'Synopsis:';
		return $this->getTextAreaDiv($print, $fieldName, $inputLabel);
	}
	
	public function getMainReferences($print) {
		$fieldName = 'mainReferences';
		$inputLabel = 'Main references supporting the Module:';
		return $this->getTextAreaDiv($print, $fieldName, $inputLabel);
	}
	
	public function getAddReferences($print) {
		$fieldName = 'addReferences';
		$inputLabel = 'Additional references supporting the Module:';
		return $this->getTextAreaDiv($print, $fieldName, $inputLabel);
	}
	
	public function getOtherAddInformation($print) {
		$fieldName = 'addInformation';
		$inputLabel = 'Other additional information:';
		return $this->getTextAreaDiv($print, $fieldName, $inputLabel);
	}
	
	public function getModulAimsMapping($print) {
		$divName = 'divModulAimsMapping';
		$inputLabel = 'Mapping of the Module to the Programme Aims:';
		$sylId = 0;
		if (isset($_SESSION['syllabusId'])) {
			$sylId = $_SESSION['syllabusId'];
		}
		
		$table  = '<table><tr class="table-heading"><th>CLO</th><th>PEO1</th><th>PEO2</th><th>PEO3</th><th>PEO4</th></tr>';
		
		$prevCloNr = 0;
		$peoStmt  = getModuleAimStmt($sylId);
		if ($result = $this->db->ExecuteSelectStmt($peoStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$cloNr = $row['cloNr'];
				$peoNr = $row['peoNr'];
				if ($prevCloNr != $cloNr) {
					$table .= ($prevCloNr > 0 ? '</tr> ' : '') . "<tr><td>CLO$cloNr</td>";
					$prevCloNr = $cloNr;
				}
				$table .= '<td><input  type="checkbox" name="chkClo'.$cloNr.'Peo'. $peoNr. '" ' . ($row['syllabusProgAimId'] > 0 ? 'checked ' : '') . 'class="textInput"></td>';
			}
			$table .= '</tr>';
		}
		$table .= '</table>';
		if ($print) {
			return $this->getDiv($divName, $inputLabel, $table);
		} else {
			return $this->getDiv($divName, $inputLabel, $table, '');
		}
	}
	
	public function getLearningOutcomeMapping($print) {
		$divName = 'divLearningOutcomeMapping';
		$inputLabel = 'Mapping of the Module to the Programme Learning Outcomes:';
		$sylId = 0;
		if (isset($_SESSION['syllabusId'])) {
			$sylId = $_SESSION['syllabusId'];
		}
	
		$table  = '<table><tr class="table-heading"><th>MLO</th><th>PLO1</th><th>PLO2</th><th>PLO3</th><th>PLO4</th><th>PLO5</th><th>PLO6</th><th>PLO7</th><th>PLO8</th></tr>';
		$prevMloNr = 0;
		$ploStmt  = getLearningOutcomeStmt($sylId);
		if ($result = $this->db->ExecuteSelectStmt($ploStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$mloNr = $row['mloNr'];
				$ploNr = $row['ploNr'];
				if ($prevMloNr != $mloNr) {
					$table .= ($prevMloNr > 0 ? '</tr> ' : '') . "<tr><td>MLO$mloNr</td>";
					$prevMloNr = $mloNr;
				}
				$table .= '<td><input  type="checkbox" name="chkMlo'.$mloNr.'Plo'. $ploNr. '" ' . ($row['syllabusProgLearnOutcomeId'] > 0 ? 'checked ' : '') . 'class="textInput"></td>';
			}
			$table .= '</tr>';
		}
		$table .= '</table>';
		if ($print) {
			return $this->getDiv($divName, $inputLabel, $table);
		} else {
			return $this->getDiv($divName, $inputLabel, $table, '');
		}
	}
	
	public function getModeOfDelivery($print) {
		$divName = 'divModeOfDelivery';
		$inputLabel = 'Mode of Delivery:';
		$sylId = 0;
		if (isset($_SESSION['syllabusId'])) {
			$sylId = $_SESSION['syllabusId'];
		}
		
		$modeOfDeliveryStmt = getModeOfDeliveryStmt($sylId);
		$list = '';
		if ($result = $this->db->ExecuteSelectStmt($modeOfDeliveryStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$modeOfDeliveryId = $row['modeOfDeliveryId'];
				$description = $row['description'];
				if ($print && $row['syllabusModeOfDeliveryId'] > 0) {
					$list .= $description . ' ';
				} else if (!$print) {
					$list .= '<label class="checkbox-inline"><input type="checkbox" name="chkmodeOfDelivery'. $modeOfDeliveryId . '" ' . ($row['syllabusModeOfDeliveryId'] > 0 ? 'checked ' : '') . 'value = "' . $description . '">' . $description . '</label>';
				}
			}
		}
		return $this->getDiv($divName, $inputLabel, $list, "");
	}
		
	
	public function getTeachLearnAssesStrategies($print) {
		$divName = 'divTeachLearnAssesStrategies';
		$inputLabel = 'Teaching-Learning and Assessment strategies:';
		$sylId = 0;
		if (isset($_SESSION['syllabusId'])) {
			$sylId = $_SESSION['syllabusId'];
		}
		
		// MQF Skills
		$mqfSkillStmt  = getMqfSkillsStmt($sylId);
		
		$syllabusMqfSkillPart = '';
		if ($result = $this->db->ExecuteSelectStmt($mqfSkillStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$mqfSkillId = $row['mqfSkillId'];
				$description = $row['description'];
				$syllabusMqfSkillPart .= '<div class="checkbox"><label><input type="checkbox" name="chkmqfSkill'. $mqfSkillId . '" ' . ($row['syllabusMqfSkillId'] > 0 ? 'checked ' : '') . 'value = "' . $description . '">' . $description . '</label></div>';
			}
		}
		
		// teach and learn acitvities
		$teachlearnactivityStmt  = getTeachLearnActivityStmt($sylId);
	
		$teachlearnactivityPart = '';
		if ($result = $this->db->ExecuteSelectStmt($teachlearnactivityStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$teachLearnActivityId = $row['teachLearnActivityId'];
				$description = $row['description'];
				$teachlearnactivityPart .= '<div class="checkbox"><label><input type="checkbox" name="chkteachlearnactivity'. $teachLearnActivityId . '" ' . ($row['syllabusTeachLearnActivityId'] > 0 ? 'checked ' : '') . 'value = "' . $description . '">' . $description . '</label></div>';
			}
			$teachlearnactivityPart .= '</ul>';
		}
			
		// Assesment Types
		$assessmentTypeStmt  = getAssessmentTypeStmt($sylId);	
		$assessmentTypePart = '';
		if ($result = $this->db->ExecuteSelectStmt($assessmentTypeStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$assessmentTypeId = $row['assessmentTypeId'];
				$description = $row['description'];
				$assessmentTypePart .= '<div class="checkbox"><label><input  type="checkbox" name="chkassessmentType'. $assessmentTypeId . '" ' . ($row['syllabusassessmenttypeId'] > 0 ? 'checked ' : '') . ' value = "' . $description . '" />' . $description . '</label></div>';
			}
			$assessmentTypePart .= '</ul>';
		}
		
		$table  = '<table><tr class="table-heading"><th>MQF Skills</th><th>Teaching and Learning activities</th><th>Type of Assessment</th></tr>';
		$table  .= '<tr><td>' . $syllabusMqfSkillPart . '</td><td>' . $teachlearnactivityPart . '</td><td>' . $assessmentTypePart . '</td></tr>';
		$table .= '</table>';
		if ($print) {
			return $this->getDiv($divName, $inputLabel, $table);
		} else {
			return $this->getDiv($divName, $inputLabel, $table, '');
		}
	}
	
	// TODO: 
	// - Javascript to recal total?
	public function getTopics($print) {
		$divName = "divTopics";
		$inputLabel = "Content Outline of the Module and the SLT per Topic:";
		$syllabusId = 0;
		if (isset($_SESSION['syllabusId'])) {
			$syllabusId = $_SESSION['syllabusId'];
		}
		
		$table = '<table><tr class="table-heading"><th>Nr</th><th>Topic</th><th colspan="4">Face to Face / Guided Learning</th><th colspan="4">Independent Learning</th><th>Total</th>'. ($print? '' : '<th></th>') . '</tr> ';
		$table .= '<tr class="table-heading"><th/><th/><th class="tdNumber">L</th><th class="tdNumber">T</th><th class="tdNumber">P</th><th class="tdNumber">O</th><th class="tdNumber">L</th><th class="tdNumber">T</th><th class="tdNumber">P</th><th class="tdNumber">O</th><th/>'. ($print? '' : '<th/>') . '</tr>';
		
		// Table with Topics
		$topicStmt  = getTopicStmt($syllabusId);
		if ($result = $this->db->ExecuteSelectStmt($topicStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
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
				$topicTotal = $guidedLearnLecture + $guidedLearnTutorial + $guidedLearnPractical + $guidedLearnOther + $indepLearnLecture + $indepLearnTutorial + $indepLearnPractical + $indepLearnOther;
				$table .= "<tr><td>$topicNr</td>";
				$table .= "<td>$description</td>";
				$table .= '<td class="tdNumber">' . $this->formatHours($guidedLearnLecture) . '</td>';
				$table .= '<td class="tdNumber">' . $this->formatHours($guidedLearnTutorial) . '</td>';
				$table .= '<td class="tdNumber">' . $this->formatHours($guidedLearnPractical) . '</td>';
				$table .= '<td class="tdNumber">' . $this->formatHours($guidedLearnOther) . '</td>';
				$table .= '<td class="tdNumber">' . $this->formatHours($indepLearnLecture) . '</td>';
				$table .= '<td class="tdNumber">' . $this->formatHours($indepLearnTutorial) . '</td>';
				$table .= '<td class="tdNumber">' . $this->formatHours($indepLearnPractical) . '</td>';
				$table .= '<td class="tdNumber">' . $this->formatHours($indepLearnOther) . '</td>';
				$table .= '<td class="tdNumber">' . $this->formatHours($topicTotal) . '</td>';			
				if (!$print) {
					$table .= '<td><a href="' . $_SESSION["basePath"] . '/syllabusTopic/' . $topicId. '"><span class="glyphicon glyphicon-pencil"></a> ';
					$table .=  '<a href="' . $_SESSION["basePath"] . '/syllabusTopicDelete/' . $topicId. '"><span class="glyphicon glyphicon-remove"></a></td></tr>';
				}
			}
		}
		
		// Get Values from session
		$moduleTotal = 0;
		$totGuidedLearn = 0;
		$totGuidedLearnLecture = 0;
		$totGuidedLearnTutorial = 0;
		$totGuidedLearnPractical = 0;
		$totGuidedLearnOther = 0;
		$totIndepLearn = 0;
		$totIndepLearnLecture = 0;
		$totIndepLearnTutorial = 0;
		$totIndepLearnPractical = 0;
		$totIndepLearnOther = 0;
		$creditHours = 0;
		
		if (isset($_SESSION['syllabus'])) {
			$valuePart = htmlspecialchars(stripslashes($_SESSION['syllabus']['mainReferences']));
			$totGuidedLearnLecture = $_SESSION['syllabus']['guidedLearnLecture'];
			$totGuidedLearnTutorial = $_SESSION['syllabus']['guidedLearnTutorial'];
			$totGuidedLearnPractical = $_SESSION['syllabus']['guidedLearnPractical'];
			$totGuidedLearnOther = $_SESSION['syllabus']['guidedLearnOther'];
			$totIndepLearnLecture = $_SESSION['syllabus']['indepLearnLecture'];
			$totIndepLearnTutorial = $_SESSION['syllabus']['indepLearnTutorial'];
			$totIndepLearnPractical = $_SESSION['syllabus']['indepLearnPractical'];
			$totIndepLearnOther = $_SESSION['syllabus']['indepLearnOther'];
			$totGuidedLearn = $totGuidedLearnLecture + $totGuidedLearnTutorial + $totGuidedLearnPractical + $totGuidedLearnOther;
			$totIndepLearn = $totIndepLearnLecture + $totIndepLearnTutorial + $totIndepLearnPractical + $totIndepLearnOther;
			$moduleTotal = $totGuidedLearn + $totIndepLearn;
			$creditHours = $_SESSION['syllabus']['creditHours'];
		}	
		// Total row of the topics
		$table .= "<tr><td/>";
		$table .= "<td><b>Total</b></td>";
		$table .= '<td class="tdNumber">' . $this->formatHours($totGuidedLearnLecture) . '</td>';
		$table .= '<td class="tdNumber">' . $this->formatHours($totGuidedLearnTutorial) . '</td>';
		$table .= '<td class="tdNumber">' . $this->formatHours($totGuidedLearnPractical) . '</td>';
		$table .= '<td class="tdNumber">' . $this->formatHours($totGuidedLearnOther) . '</td>';
		$table .= '<td class="tdNumber">' . $this->formatHours($totIndepLearnLecture) . '</td>';
		$table .= '<td class="tdNumber">' . $this->formatHours($totIndepLearnTutorial) . '</td>';
		$table .= '<td class="tdNumber">' . $this->formatHours($totIndepLearnPractical) . '</td>';
		$table .= '<td class="tdNumber">' . $this->formatHours($totIndepLearnOther) . '</td>';
		$table .= '<td class="tdNumber">' . $this->formatHours($moduleTotal) . '</td>';	
		$table .= '<td/></tr>';				
		// Rekapitulation by Learningstyle
		$table .= '<tr><td/>';
		$table .= '<td>Total Independant and Guided Learning</td>';
		$table .= '<td colspan="4">Face to Face Learning</td>';
		$table .= '<td colspan="4">Independent Learning</td>';
		$table .= '<td colspan="2"/></tr>';
		if ($totGuidedLearnLecture > 0 || $totIndepLearnLecture > 0) {
			$table .= '<tr><td/>';
			$table .= '<td>Lecture</td>';
			$table .= '<td colspan="4" class="tdNumber">'. $this->formatHours($totGuidedLearnLecture) . '</td>';
			$table .= '<td colspan="4" class="tdNumber">' . $this->formatHours($totIndepLearnLecture) . '</td>';
			$table .= '<td colspan="2"/></tr>';
		}
		if ($totGuidedLearnTutorial > 0 || $totIndepLearnTutorial > 0) {
			$table .='<tr><td/>';
			$table .='<td>Tutorial</td>';
			$table .='<td colspan="4" class="tdNumber">'. $this->formatHours($totGuidedLearnTutorial) . '</td>';
			$table .='<td colspan="4" class="tdNumber">' . $this->formatHours($totIndepLearnTutorial) . '</td>';
			$table .='<td colspan="2"/></tr>';
		}
		if ($totGuidedLearnPractical > 0 || $totIndepLearnPractical > 0) {
			$table .= '<tr><td/>';
			$table .= '<td>Practical</td>';
			$table .= '<td colspan="4" class="tdNumber">'. $this->formatHours($totGuidedLearnPractical) . '</td>';
			$table .= '<td colspan="4" class="tdNumber">' . $this->formatHours($totIndepLearnPractical) . '</td>';
			$table .= '<td colspan="2"/></tr>';
		}
		if ($totGuidedLearnOther > 0 || $totIndepLearnOther > 0) {
			$table .= '<tr><td/>';
			$table .= '<td>Other</td>';
			$table .= '<td colspan="4" class="tdNumber">'. $this->formatHours($totGuidedLearnOther) . '</td>';
			$table .= '<td colspan="4" class="tdNumber">' . $this->formatHours($totIndepLearnOther) . '</td>';
			$table .= '<td colspan="2"/></tr>';
		}
		
		// Assessments
		$totAssessmentsGuided = 0;
		$totAssessmentsIndependant = 0;
		$assessmentStmt = getSyllabusAssessmentTypeStmt($syllabusId);
		if ($result = $this->db->ExecuteSelectStmt($assessmentStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$syllabusAssessmentTypeId = $row['syllabusAssessmentTypeId'];
				$description = htmlspecialchars(stripslashes($row['description']));
				$guidedLearning = $row['guidedLearning'];
				$totAssessmentsGuided += $guidedLearning;
				$indepLearning = $row['indepLearning'];
				$totAssessmentsIndependant += $indepLearning;
				$table .= '<tr><td/>';
				$table .= "<td>$description</td>";
				if ($print) {
					$table .= '<td colspan="4" class="tdNumber">'. $this->formatHours($indepLearning) . '</td>';
					$table .= '<td colspan="4" class="tdNumber">' . $this->formatHours($guidedLearning) . '</td>';
				} else {
					$table .= '<td colspan="4" class="tdNumber"><input name="txtsyllabusAssType' .$syllabusAssessmentTypeId . 'Indep" type="text" Value="' . $this->formatHours($indepLearning) . '" class="numberInput"></td>';
					$table .= '<td colspan="4" class="tdNumber"><input name="txtsyllabusAssType' .$syllabusAssessmentTypeId . 'Guided" type="text" Value="' . $this->formatHours($guidedLearning) . '" class="numberInput"></td>';
				}
				$table .= '<td colspan="2"/></tr>';
			}
		}
		
		// Totals
		$totGuidedLearn += $totAssessmentsGuided;
		$totIndepLearn += $totAssessmentsIndependant;
		$table .= '<tr><td/>';
		$table .= '<td>Total</td>';
		$table .= '<td colspan="4" class="tdNumber">'. $this->formatHours($totGuidedLearn) . '</td>';
		$table .= '<td colspan="4" class="tdNumber">' . $this->formatHours($totIndepLearn) . '</td>';
		$table .= '<td colspan="2"/></tr>';
		
		$table .= '<tr><td/>';
		$table .= '<td>Total Student Learning Time (SLT)</td>';
		$table .= '<td colspan="8" class="tdNumber">'. $this->formatHours(($totGuidedLearn + $totIndepLearn)) . '</td>';
		$table .= '<td colspan="2"/></tr>';
		
		$table .= '<tr><td/>';
		$table .= '<td>Credit Hours</td>';
		if ($print) {
			$table .= '<td colspan="8" class="tdNumber"><p>'. $creditHours . '</p></td>';
		} else {
			$table .= '<td colspan="8" class="tdNumber"><input name="txtcreditHours" type="text" Value="' . $creditHours . '" class="numberInput"></td>';
		}
		$table .= '<td colspan="2"/></tr>';
		$table .= '</table>';
		if ($print) {
			//return $this->getDiv($divName, $inputLabel, $table);
			return '<div id = "' . $divName . '" class="inputDiv">' . $table . '</div>';
		} else {
		
			return '<a href ="' . $_SESSION["basePath"] . '/syllabusTopic/0" class="btn btn-default">+ add new topic</a><br/><br/>' . $table;
		}
	}
		
	
	private function getTextAreaDiv($print, $fieldName, $inputLabel) {
		$divName = 'div' . $fieldName;
		$currValue = $this->getSyllabusVarText($fieldName);
		
		if ($print) {
			return $this->getDiv($divName, $inputLabel, $this->getTextLabel($currValue));
		} else {
			$inputPart = $this->getTextArea('txt' . $fieldName, $currValue);
			return $this->getDiv($divName, $inputLabel, $inputPart);
		}
	}
	
	private function getDiv($divName, $labelText, $InputPart, $InputPartClass='inputLabel') {
		$div  = '<div id = "' . $divName . '" class="inputDiv">';
		$div .= '<p class="' . $InputPartClass . '">' . $labelText . '</p>';
		$div .= $InputPart;
		$div .= '</div>';
		return $div;
	}
	
	private function getTextInput($name, $value) {
		return  '<input name="' . $name . '" type="text" value = "' . $value . '" class="textInput">';
	}
	
	private function getTextLabel($value) {
		//return  '<p class="textLabel">' . str_replace(' ', '&nbsp', nl2br($value)) . '</p>';
		return  '<p class="textLabel">' . nl2br($value) . '</p>';
	}
	
	private function getTextArea($name, $value) {
		return  '<textArea name="' . $name . '" rows="4" class="textArea">' . $value . '</textArea>';
	}
	
	// Display all hours with 1 decimal point for half hours as 0.5
	private function formatHours($hours) {
		return sprintf("%01.1f", $hours);
	}
	
	private function getSyllabusVarText($variable) {
		if (isset($_SESSION['syllabus'])) {
			return htmlspecialchars(stripslashes($_SESSION['syllabus'][$variable]));
		} else {
			return '';
		}
	}
	private function getSyllabusVarNr($variable) {
		if (isset($_SESSION['syllabus'])) {
			return $_SESSION['syllabus'][$variable];
		} else {
			return 0;
		}
	}
}
?>