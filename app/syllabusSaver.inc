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
		
		$db = App\DB\ConnHelper::Instance();
		if ($createNew) {
			// insert
			$versionNr = getNextVersionByModul($moduleId);
			// copy an existing one to a new version
			if ($syllabusId > 0) {
				$stmt = "call  copySyllabus($syllabusId, $versionNr, $userId )";
				$db->ExecuteSelectStmt($stmt);
			} else {
				// Insert a new empty syllabus
				$fields_values = array(
					'moduleId' => $moduleId,
					'versionNr' => $versionNr ,
					'revisionNr' => 0,
					'academicStaff' => $academicStaffId,
					'editBy' => $userId
				);

				$res = $db->ExecuteInsertStmt('syllabus', $fields_values);
			}
			$_SESSION['syllabusId'] = getIdByModulVersion($moduleId, $versionNr);
			$syllabusId = $_SESSION['syllabusId'];
			$revisionNr = 0;
		}
		
		// update (also after insert or copy)
		$fields_values = array(
			'revisionNr' => ($revisionNr + ($increaseRevision ? 1 : 0)),
			'academicStaff' => $academicStaffId,
			'semester' => $semester,
			'learningOutcomes' => $learningOutcomes,
			'transferableSkills' => $transferableSkills,
			'synopsis' => $synopsis,
			'assessmentCode' => $assessmentCode,
			'editBy' => $userId
		);		
		$db->ExecuteUpdateStmt('syllabus', $fields_values, 'syllabusId = ' . $syllabusId);
		
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
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusProgLearnOutcomeId'] > 0) {
					$db->ExecuteDeleteStmt('syllabusProgLearnOutcome', 'syllabusProgLearnOutcomeId = ' . $row['syllabusProgLearnOutcomeId']);
				
				} else if ($isChecked && $row['syllabusProgLearnOutcomeId'] == 0) {
					// User checked, insert entry
					$fields_values = array(
						'syllabusId' => $syllabusId,
						'mlo' => $mloNr ,
						'plo' => $ploNr
					);
					$db->ExecuteInsertStmt('syllabusProgLearnOutcome', $fields_values);
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
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusProgAimId'] > 0) {
					$db->ExecuteDeleteStmt('syllabusProgAim', 'syllabusProgAimId = ' . $row['syllabusProgAimId']);
					
				} else if ($isChecked && $row['syllabusProgAimId'] == 0) {
					// User checked, insert entry 
					$fields_values = array(
						'syllabusId' => $syllabusId,
						'clo' => $cloNr ,
						'peo' => $peoNr
					);
					$res = $db->ExecuteInsertStmt('syllabusProgAim', $fields_values);
				}
			}
		}
		// Save the AssessmentTypes
		$ploStmt  = getAssessmentTypeStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($ploStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$assessmentTypeId = $row['assessmentTypeId'];
				
				$isChecked = isset($_POST['chkassessmentType'. $assessmentTypeId]);
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusassessmenttypeId'] > 0) {
					$db->ExecuteDeleteStmt('syllabusassessmenttype', 'syllabusassessmenttypeId = ' . $row['syllabusassessmenttypeId']);
				// User checked, insert entry 
				} else if ($isChecked && $row['syllabusassessmenttypeId'] == 0) {
					// User checked, insert entry 
					$fields_values = array(
						'syllabusId' => $syllabusId,
						'assessmentTypeId' => $assessmentTypeId
					);
					$res = $db->ExecuteInsertStmt('syllabusassessmenttype', $fields_values);
				}
			}
		}
		// Save the MFQ Skills
		$ploStmt  = getMqfSkillsStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($ploStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$mqfSkillId = $row['mqfSkillId'];
				
				$isChecked = isset($_POST['chkmqfSkill'. $mqfSkillId]);
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusMqfSkillId'] > 0) {
					$db->ExecuteDeleteStmt('syllabusMqfSkill', 'syllabusMqfSkillId = ' . $row['syllabusMqfSkillId']);
					
				} else if ($isChecked && $row['syllabusMqfSkillId'] == 0) {
					// User checked, insert entry 
					$fields_values = array(
						'syllabusId' => $syllabusId,
						'mqfSkillId' => $mqfSkillId
					);
					$res = $db->ExecuteInsertStmt('syllabusMqfSkill', $fields_values);
				}
			}
		}
		// Save the Teach Learning activities
		$tlaStmt  = getTeachLearnActivityStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($tlaStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$teachLearnActivityId = $row['teachLearnActivityId'];
				
				$isChecked = isset($_POST['chkteachlearnactivity'. $teachLearnActivityId]);
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusTeachLearnActivityId'] > 0) {
					$db->ExecuteDeleteStmt('syllabusTeachLearnActivity', 'syllabusTeachLearnActivityId = ' . $row['syllabusTeachLearnActivityId']);
					
				// User checked, insert entry 
				} else if ($isChecked && $row['syllabusTeachLearnActivityId'] == 0) {
					// User checked, insert entry 
					$fields_values = array(
						'syllabusId' => $syllabusId,
						'teachLearnActivityId' => $teachLearnActivityId
					);
					$res = $db->ExecuteInsertStmt('syllabusTeachLearnActivity', $fields_values);
				}
			}
		}
		// Save the Mode of delivery
		$modStmt  = getModeOfDeliveryStmt($syllabusId);
		if ($result = $db->ExecuteSelectStmt($modStmt)) {
			while ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				$modeOfDeliveryId = $row['modeOfDeliveryId'];
				
				$isChecked = isset($_POST['chkmodeOfDelivery'. $modeOfDeliveryId]);
				// User unchecked, delete entry
				if (!$isChecked && $row['syllabusModeOfDeliveryId'] > 0) {
					$db->ExecuteDeleteStmt('syllabusModeOfDelivery', 'syllabusModeOfDeliveryId = ' . $row['syllabusModeOfDeliveryId']);
					
				// User checked, insert entry 
				} else if ($isChecked && $row['syllabusModeOfDeliveryId'] == 0) {
					// User checked, insert entry 
					$fields_values = array(
						'syllabusId' => $syllabusId,
						'modeOfDeliveryId' => $modeOfDeliveryId
					);
					$res = $db->ExecuteInsertStmt('syllabusModeOfDelivery', $fields_values);
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
						$fields_values = array(
							'guidedLearning' => $guidedLearningNew,
							'indepLearning' => $indepLearningNew
						);		
						$db->ExecuteUpdateStmt('syllabusAssessmentType', $fields_values, 'syllabusAssessmentTypeId = ' . $syllabusAssessmentTypeId);
					}
				}
			}
		}
		// CreditHours
		if (isset($_POST['txtcreditHours'])) {
			$creditHoursNew = $_POST['txtcreditHours'];
			$creditHoursOld = $_SESSION['syllabus']['creditHours'];
			if ($creditHoursNew != $creditHoursOld) {
				$fields_values = array(
					'creditHours' => $creditHoursNew
				);		
				$db->ExecuteUpdateStmt('syllabus', $fields_values, 'syllabusId = ' . $syllabusId);
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
		$db = App\DB\ConnHelper::Instance();
		$fields_values = array(
			'addInformation' => addslashes(trim($_POST['txtaddInformation'])),
			'addReferences' => addslashes(trim($_POST['txtaddReferences'])),
			'mainReferences' => addslashes(trim($_POST['txtmainReferences'])),
			'editBy' => $userId
		);		
		$db->ExecuteUpdateStmt('syllabus', $fields_values, 'syllabusId = ' . $syllabusId);
		
		redirectToOverview($moduleId);
	}	
	
	function redirectToOverview($moduleId) {
		// redirect to module page
		App\Utils\OtherUtils::redirect('/module/'.$moduleId);
	}
?>