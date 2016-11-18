<?php

	if (empty($_POST)) {
		// before the first search, show the welcome Text
		echo "<p>Error, your not allowed to call this page directly";
	} else {
		// WizardStep
		$wizStep = 1;
		if (isset($_SESSION['wizStep'])) {
			$wizStep = $_SESSION['wizStep'];
		}
		$userId = 1;
		if (isset($_SESSION["user"]["id"])) {
			$userId = $_SESSION["user"]["id"];
		}
		$syllabusId = 0;
		if (isset($_SESSION['syllabusId'])) {
			$syllabusId = $_SESSION['syllabusId'];
		}
		$moduleId = 0;
		if (isset($_SESSION['moduleId'])) {
			$moduleId = $_SESSION['moduleId'];
		}
		if ($wizStep == 1) {
			saveWizardStep1($userId, $syllabusId);
			$syllabusId = $_SESSION['syllabusId'];
			
		} else if ($wizStep == 2) {
			saveWizardStep2($userId, $syllabusId);
			
		} else if ($wizStep == 3) {
			saveWizardStep3($userId, $syllabusId);
			
		} else if ($wizStep == 4) {
			saveWizardStep4($userId, $syllabusId, $moduleId);
		}	
	}
	
	
	function saveWizardStep1($userId, $syllabusId) {
		// don't save, back to page 1
		$createNew = (isset($_POST['saveVersion']) || $syllabusId == 0);
		$increaseRevision = isset($_POST['saveRevision']);
		
		$moduleId = trim($_POST['selModul']);
		if (isset($_POST['goBack'])) {
			redirectToOverview($moduleId);
			return;
		}
		$academicStaffId = trim($_POST['selAcademicStaff']);
		$semester= addslashes(trim($_POST['txtsemester']));
		$learningOutcomes= addslashes(trim($_POST['txtlearningOutcomes']));
		$transferableSkills= addslashes(trim($_POST['txttransferableSkills']));
		$synopsis= addslashes(trim($_POST['txtsynopsis']));
		$assessmentCode= addslashes(trim($_POST['txtassessmentCode']));
		
		$versionNr = 0;
		$revisionNr = 0;
		if (isset($_SESSION['syllabus'])) {
			$versionNr = $_SESSION['syllabus']['versionNr'];
			$revisionNr = $_SESSION['syllabus']['revisionNr'];
		}
		
		if ($createNew) {
			// insert
			$versionNr = getNextVersionByModul($moduleId);
			$stmt = "call  copySyllabus($syllabusId, $versionNr, $userId )";
			executeSqlStmt($stmt);
			$_SESSION['syllabusId'] = getIdByModulVersion($moduleId, $versionNr);
			$syllabusId = $_SESSION['syllabusId'];
			$revisionNr = 0;
		}
		
		// update
		$stmt = 'UPDATE syllabus SET revisionNr = ' . ( $revisionNr + ($increaseRevision ? 1 : 0)) . ', academicStaff = ' . $academicStaffId . ', semester = \'' . $semester . '\', learningOutcomes = \'' . $learningOutcomes . '\', transferableSkills = \'' . $transferableSkills . '\', synopsis = \'' . $synopsis . '\', assessmentCode = \'' . $assessmentCode . '\', editBy = ' . $userId . ' ';
		$stmt .= 'WHERE syllabusId = ' . $syllabusId;
		executeSqlStmt($stmt);
		
		$_SESSION['wizStep'] = 2;
	}
	
	function saveWizardStep2($userId, $syllabusId) {
		// don't save, back to page 1
		if (isset($_POST['goBack'])) {
			$_SESSION['wizStep'] = 1;
			return;
		} else {
			$_SESSION['wizStep'] = 3;
		}
		$db = App\DB\ConnHelper::Instance();
		
		// Save the Learning outcomes
		$ploStmt  = getLearningOutcomeStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($ploStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$mloNr = $row['mloNr'];
				$ploNr = $row['ploNr'];
				
				$isChecked = isset($_POST['chkMlo'.$mloNr.'Plo'. $ploNr]);
				$stmt = '';
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusProgLearnOutcomeId'] > 0) {
					$stmt = 'DELETE FROM syllabusProgLearnOutcome WHERE syllabusProgLearnOutcomeId = ' . $row['syllabusProgLearnOutcomeId'];
				// User checked, insert entry 
				} else if ($isChecked && $row['syllabusProgLearnOutcomeId'] == 0) {
					$stmt = 'INSERT INTO syllabusProgLearnOutcome (syllabusId, mlo, plo) VALUES (' . $syllabusId . ', ' . $mloNr . ', ' . $ploNr . ')';
				}
				if ($stmt != '') {
					executeSqlStmt($stmt);
				}
			}
		}
		// Save the Module Aims
		$ploStmt  = getModuleAimStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($ploStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$cloNr = $row['cloNr'];
				$peoNr = $row['peoNr'];
				
				$isChecked = isset($_POST['chkClo'.$cloNr.'Peo'. $peoNr]);
				$stmt = '';
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusProgAimId'] > 0) {
					$stmt = 'DELETE FROM syllabusProgAim WHERE syllabusProgAimId = ' . $row['syllabusProgAimId'];
				// User checked, insert entry 
				} else if ($isChecked && $row['syllabusProgAimId'] == 0) {
					$stmt = 'INSERT INTO syllabusProgAim (syllabusId, clo, peo) VALUES (' . $syllabusId . ', ' . $cloNr . ', ' . $peoNr . ')';
				}
				if ($stmt != '') {
					executeSqlStmt($stmt);
				}
			}
		}
		// Save the AssessmentTypes
		$ploStmt  = getAssessmentTypeStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($ploStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$assessmentTypeId = $row['assessmentTypeId'];
				
				$isChecked = isset($_POST['chkassessmentType'. $assessmentTypeId]);
				$stmt = '';
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusassessmenttypeId'] > 0) {
					$stmt = 'DELETE FROM syllabusassessmenttype WHERE syllabusassessmenttypeId = ' . $row['syllabusassessmenttypeId'];
				// User checked, insert entry 
				} else if ($isChecked && $row['syllabusassessmenttypeId'] == 0) {
					$stmt = 'INSERT INTO syllabusassessmenttype (syllabusId, assessmentTypeId) VALUES (' . $syllabusId . ', ' . $assessmentTypeId . ')';
				}
				if ($stmt != '') {
					executeSqlStmt($stmt);
				}
			}
		}
		// Save the MFQ Skills
		$ploStmt  = getMqfSkillsStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($ploStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$mqfSkillId = $row['mqfSkillId'];
				
				$isChecked = isset($_POST['chkmqfSkill'. $mqfSkillId]);
				$stmt = '';
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusMqfSkillId'] > 0) {
					$stmt = 'DELETE FROM syllabusMqfSkill WHERE syllabusMqfSkillId = ' . $row['syllabusMqfSkillId'];
				// User checked, insert entry 
				} else if ($isChecked && $row['syllabusMqfSkillId'] == 0) {
					$stmt = 'INSERT INTO syllabusMqfSkill (syllabusId, mqfSkillId) VALUES (' . $syllabusId . ', ' . $mqfSkillId . ')';
				}
				if ($stmt != '') {
					executeSqlStmt($stmt);
				}
			}
		}
		// Save the Teach Learning activities
		$tlaStmt  = getTeachLearnActivityStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($tlaStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$teachLearnActivityId = $row['teachLearnActivityId'];
				
				$isChecked = isset($_POST['chkteachlearnactivity'. $teachLearnActivityId]);
				$stmt = '';
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusTeachLearnActivityId'] > 0) {
					$stmt = 'DELETE FROM syllabusTeachLearnActivity WHERE syllabusTeachLearnActivityId = ' . $row['syllabusTeachLearnActivityId'];
				// User checked, insert entry 
				} else if ($isChecked && $row['syllabusTeachLearnActivityId'] == 0) {
					$stmt = 'INSERT INTO syllabusTeachLearnActivity (syllabusId, teachLearnActivityId) VALUES (' . $syllabusId . ', ' . $teachLearnActivityId . ')';
				}
				if ($stmt != '') {
					executeSqlStmt($stmt);
				}
			}
		}
		// Save the Mode of delivery
		$modStmt  = getModeOfDeliveryStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($modStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$modeOfDeliveryId = $row['modeOfDeliveryId'];
				
				$isChecked = isset($_POST['chkmodeOfDelivery'. $modeOfDeliveryId]);
				$stmt = '';
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusModeOfDeliveryId'] > 0) {
					$stmt = 'DELETE FROM syllabusModeOfDelivery WHERE syllabusModeOfDeliveryId = ' . $row['syllabusModeOfDeliveryId'];
				// User checked, insert entry 
				} else if ($isChecked && $row['syllabusModeOfDeliveryId'] == 0) {
					$stmt = 'INSERT INTO syllabusModeOfDelivery (syllabusId, modeOfDeliveryId) VALUES (' . $syllabusId . ', ' . $modeOfDeliveryId . ')';
				}
				if ($stmt != '') {
					executeSqlStmt($stmt);
				}
			}
		}
		
	}
	
	function saveWizardStep3($userId, $syllabusId) {
		// don't save, back to page 2
		if (isset($_POST['goBack'])) {
			$_SESSION['wizStep'] = 2;
			return;
		} else {
			$_SESSION['wizStep'] = 4;
		}
		// Assessments
		$db = App\DB\ConnHelper::Instance();
		$totAssessmentsGuided = 0;
		$totAssessmentsIndependant = 0;
		$assessmentStmt = getSyllabusAssessmentTypeStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($assessmentStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$syllabusAssessmentTypeId = $row['syllabusAssessmentTypeId'];
				
				if (isset($_POST['txtsyllabusAssType' .$syllabusAssessmentTypeId . 'Guided']) && isset($_POST['txtsyllabusAssType' .$syllabusAssessmentTypeId . 'Indep'])) {
					$guidedLearningOld = $row['guidedLearning'];
					$indepLearningOld = $row['indepLearning'];
					$guidedLearningNew = $_POST['txtsyllabusAssType' .$syllabusAssessmentTypeId . 'Guided'];
					$indepLearningNew = $_POST['txtsyllabusAssType' .$syllabusAssessmentTypeId . 'Indep'];
					
					if ($guidedLearningOld != $guidedLearningNew || $indepLearningOld != $indepLearningNew) {
						$stmt = 'UPDATE syllabusAssessmentType SET guidedLearning = '. $guidedLearningNew . ', indepLearning =  ' . $indepLearningNew . ' WHERE syllabusAssessmentTypeId = ' . $syllabusAssessmentTypeId;
						$db->ExecuteSelectStmt($stmt);
					}
				}
			}
		}
		// CreditHours
		if (isset($_POST['txtcreditHours'])) {
			$creditHoursNew = $_POST['txtcreditHours'];
			$creditHoursOld = $_SESSION['syllabus']['creditHours'];
			if ($creditHoursNew != $creditHoursOld) {
				$stmt = 'UPDATE syllabus set creditHours = ' . $creditHoursNew . ' WHERE syllabusId = ' . $syllabusId;
				$db->ExecuteSelectStmt($stmt);
			}
		}
	}
	
	function saveWizardStep4($userId, $syllabusId, $moduleId) {
		// don't save, back to page 2
		if (isset($_POST['goBack'])) {
			$_SESSION['wizStep'] = 3;
			return;
		} else {
			$_SESSION['wizStep'] = 1;
		}
		// Save
		$mainReferences = addslashes(trim($_POST['txtmainReferences']));
		$addReferences = addslashes(trim($_POST['txtaddReferences']));
		$addInformation= addslashes(trim($_POST['txtaddInformation']));
		
		$stmt = 'UPDATE syllabus SET addInformation = \'' . $addInformation . '\', addReferences = \'' . $addReferences . '\', mainReferences = \'' . $mainReferences . '\', editBy = ' . $userId . ' ';
		$stmt .= 'WHERE syllabusId = ' . $syllabusId;
		executeSqlStmt($stmt);
		redirectToOverview($moduleId);
	}	
	
	function redirectToOverview($moduleId) {
		// redirect to module page
		App\Utils\OtherUtils::redirect('/module/'.$moduleId);
	}
	
	function executeSqlStmt($stmt) {
		$db = App\DB\ConnHelper::Instance();
		return $db->ExecuteSelectStmt($stmt);
	}
?>