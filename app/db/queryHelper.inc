<?php	
	function getIdByModulVersion($moduleId, $versionNr) {
		$db = App\DB\ConnHelper::Instance();
		$stmt = 'SELECT syllabusId from syllabus WHERE moduleId = ' . $moduleId . ' and versionNr = ' . $versionNr;
		if ($result = $db->ExecuteSelectStmt($stmt)) {
			if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				return $row['syllabusId'];
			}
		}
	}
	
	function getNextVersionByModul($moduleId) {
		$db = App\DB\ConnHelper::Instance();
		$stmt = 'SELECT MAX(versionNr) as versionNr from syllabus WHERE moduleId = ' . $moduleId ;
		if ($result = $db->ExecuteSelectStmt($stmt)) {
			if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				return ($row['versionNr'] + 1);
			}
		}
	}
	
	function getNextTopicBySyllabus($syllabusId) {
		$db = App\DB\ConnHelper::Instance();
		$stmt = 'SELECT MAX(topicNr) as topicNr from syllabusTopic WHERE syllabusId = ' . $syllabusId ;
		if ($result = $db->ExecuteSelectStmt($stmt)) {
			if ($row = $result->fetchRow(DB_FETCHMODE_ASSOC)) {
				return ($row['topicNr'] + 1);
			}
		}
	}
	
	function getSyllabusInitStmt($syllabusId) {
		$sylStmt  = 'SELECT m.credits, m.purpose as modulePurpose, m.code as moduleCode, m.name as moduleName, m.moduleId, versionNr, revisionNr, academicStaff, semester, creditHours, guidedLearnLecture, guidedLearnTutorial, guidedLearnPractical, guidedLearnOther, indepLearnLecture, indepLearnTutorial, indepLearnPractical, indepLearnOther, learningOutcomes, transferableSkills, synopsis, ';
		$sylStmt .= "assessmentCode, mainReferences, addReferences, addInformation, approvedBy as approvedById, CONCAT(ap.name, ' ', ap.surname) as approvedBy, DATE_FORMAT(s.approvedTS,'%d.%c.%Y') as approvedTS, CONCAT(staff.name, ' ', staff.surname) as academicStaffName, staff.qualification as academicStaffQualification, CONCAT(u.name, ' ', u.surname) as editBy, DATE_FORMAT(s.editTS,'%d %b %Y %T') editTS ";
		$sylStmt .= 'from syllabus s left join user staff on staff.userId = s.academicStaff join user u on s.editBy = u.userId join module m on s.moduleId = m.moduleId left join user ap on s.approvedBy = ap.userId where syllabusId = ' . $syllabusId;
		return $sylStmt;
	}
	
	function getModuleAimStmt($syllabusId) {
		$peoStmt  = 'SELECT clo.nr as cloNr, peo.nr as peoNr, speo.syllabusProgAimId ';
		$peoStmt .= 'FROM ';
		$peoStmt .= '	(SELECT 1 as nr ';
		$peoStmt .= '	UNION SELECT 2 as nr ';
		$peoStmt .= '	UNION SELECT 3 as nr ';
		$peoStmt .= '	UNION SELECT 4 as nr) as peo ';
		$peoStmt .= '	CROSS JOIN ';
		$peoStmt .= '	(SELECT 1  as nr ';
		$peoStmt .= '	UNION SELECT 2 as nr ';
		$peoStmt .= '	UNION SELECT 3 as nr) as clo ';
		$peoStmt .= '	LEFT JOIN syllabusProgAim speo on speo.syllabusId = ' . $syllabusId . ' AND speo.clo = clo.nr AND speo.peo = peo.nr ';
		$peoStmt .= 'ORDER BY cloNr, peoNr';
		return $peoStmt;
	}
	
	function getLearningOutcomeStmt($syllabusId) {
		$ploStmt  = 'SELECT mlo.nr as mloNr, plo.nr as ploNr, splo.syllabusProgLearnOutcomeId ';
		$ploStmt .= 'FROM ';
		$ploStmt .= '	(SELECT 1 as nr ';
		$ploStmt .= '	UNION SELECT 2 as nr ';
		$ploStmt .= '	UNION SELECT 3 as nr ';
		$ploStmt .= '	UNION SELECT 4 as nr ';
		$ploStmt .= '	UNION SELECT 5 as nr ';
		$ploStmt .= '	UNION SELECT 6 as nr ';
		$ploStmt .= '	UNION SELECT 7 as nr ';
		$ploStmt .= '	UNION SELECT 8 as nr) as plo ';
		$ploStmt .= '	CROSS JOIN ';
		$ploStmt .= '	(SELECT 1  as nr ';
		$ploStmt .= '	UNION SELECT 2 as nr ';
		$ploStmt .= '	UNION SELECT 3 as nr) as mlo ';
		$ploStmt .= '	LEFT JOIN syllabusProgLearnOutcome splo on splo.syllabusId = ' . $syllabusId . ' AND splo.mlo = mlo.nr AND splo.plo = plo.nr ';
		$ploStmt .= 'ORDER BY mloNr, ploNr';
		return $ploStmt;
	}
	
	function getMqfSkillsStmt($syllabusId) {
		$mqfSkillStmt  = 'SELECT s.mqfSkillId, s.description, ss.syllabusMqfSkillId ';
		$mqfSkillStmt .= 'FROM mqfSkill s left join syllabusMqfSkill ss on s.mqfSkillId = ss.mqfSkillId and ss.syllabusId = ' . $syllabusId . ' ';
		$mqfSkillStmt .= 'ORDER BY s.description';
		return $mqfSkillStmt;
	}
	
	function getTeachLearnActivityStmt($syllabusId) {
		$teachlearnactivityStmt  = 'SELECT t.teachLearnActivityId, t.description, st.syllabusTeachLearnActivityId ';
		$teachlearnactivityStmt .= 'FROM teachLearnActivity t left join syllabusTeachLearnActivity st on t.teachLearnActivityId = st.teachLearnActivityId and st.syllabusId = ' . $syllabusId . ' ';
		$teachlearnactivityStmt .= 'ORDER BY t.description';
		return $teachlearnactivityStmt;
	}
	
	function getAssessmentTypeStmt($syllabusId) {
		$assessmentTypeStmt  = 'SELECT a.assessmentTypeId, a.description, sa.syllabusAssessmentTypeId ';
		$assessmentTypeStmt .= 'FROM assessmentType a left join syllabusAssessmentType sa on a.assessmentTypeId = sa.assessmentTypeId and sa.syllabusId = ' . $syllabusId . ' ';
		$assessmentTypeStmt .= 'ORDER BY a.description';
		return $assessmentTypeStmt;
	}
	
	function getModeOfDeliveryStmt($syllabusId) {
		$teachlearnactivityStmt  = 'SELECT t.modeOfDeliveryId, t.description, st.syllabusModeOfDeliveryId ';
		$teachlearnactivityStmt .= 'FROM modeOfDelivery t left join syllabusModeOfDelivery st on t.modeOfDeliveryId = st.modeOfDeliveryId and st.syllabusId = ' . $syllabusId . ' ';
		$teachlearnactivityStmt .= 'ORDER BY t.description';
		return $teachlearnactivityStmt;
	}
	
	function getTopicStmt($syllabusId) {
		$topicStmt  = 'SELECT syllabusTopicId, topicNr, description, guidedLearnLecture, ';
		$topicStmt .= 'guidedLearnTutorial, guidedLearnPractical, guidedLearnOther, indepLearnLecture, indepLearnTutorial, indepLearnPractical, indepLearnOther ';
		$topicStmt .= 'FROM syllabusTopic WHERE syllabusId = ' . $syllabusId . ' ORDER BY topicNr';
		return $topicStmt;
	}
	
	function getSingleTopicStmt($syllabusTopicId) {
		$topicStmt  = 'SELECT syllabusTopicId, topicNr, description, guidedLearnLecture, ';
		$topicStmt .= 'guidedLearnTutorial, guidedLearnPractical, guidedLearnOther, indepLearnLecture, indepLearnTutorial, indepLearnPractical, indepLearnOther ';
		$topicStmt .= 'FROM syllabusTopic WHERE syllabusTopicId = ' . $syllabusTopicId;
		return $topicStmt;
	}
	
	function getSyllabusAssessmentTypeStmt($syllabusId) {
		$assessmentStmt  = 'SELECT syllabusAssessmentTypeId, a.description, sa.guidedLearning, sa.indepLearning, sa.weightage, a.isWrittenTest ';
		$assessmentStmt .= 'FROM syllabusAssessmentType sa join assessmentType a on sa.assessmentTypeId = a.assessmentTypeId ';
		$assessmentStmt .= 'WHERE syllabusId = ' . $syllabusId . ' ';
		$assessmentStmt .= 'ORDER BY a.isWrittenTest DESC, a.description;';
		return $assessmentStmt;
	}
	
	function getModulesByUserStmt($userId) {
		$moduleStmt  = 'SELECT mr.moduleId, m.code, m.name ';
		$moduleStmt .= 'FROM moduleRight mr join module m on mr.moduleId = m.moduleId ';
		$moduleStmt .= 'WHERE mr.userId = ' . $userId . ' ';
		$moduleStmt .= 'ORDER BY m.name ';
		return $moduleStmt;
	}
	
	function getSyllabusesByModuletmt($modId) {
		$moduleStmt  = 'SELECT syllabusId, versionNr, revisionNr, CONCAT(u.name, \' \', u.surname) as editBy, DATE_FORMAT(editTS,\'%d %b %Y %T\') editTS ';
		$moduleStmt .= 'FROM syllabus s JOIN user u on s.editBy = u.userId ';
		$moduleStmt .= 'WHERE moduleId = ' . $modId . ' ';
		$moduleStmt .= 'ORDER BY versionNr, revisionNr';
		return $moduleStmt;
	}
	
	function getModulePrerequisiteStmt($syllabusId) {
		$modPrereqStmt  = 'SELECT m.moduleId, p.code  ';
		$modPrereqStmt .= 'FROM syllabus s inner join module m on s.moduleId = m.moduleId INNER JOIN module p on m.prerequisite = p.moduleId ';
		$modPrereqStmt .= 'WHERE syllabusId = ' . $syllabusId . ' ';
		$modPrereqStmt .= 'ORDER BY p.code';
		return $modPrereqStmt;
	}
