/**
 * Web Database Applications - Assignment 2
 * DB Initial Script for the syllabus management portal
 * 
 * Author Daniel Hirsbrunner
 * Date 2016.11.09
 */
DROP DATABASE IF EXISTS moduleinfo;
CREATE DATABASE moduleinfo;

USE moduleinfo;

DROP TABLE IF EXISTS `user`;
CREATE TABLE `user` (
  `userId` int(5) NOT NULL AUTO_INCREMENT,
  `userName` varchar(50) NOT NULL,
  `password` varchar(60) NOT NULL,
  `name` varchar(50) DEFAULT NULL,
  `surname` varchar(50) DEFAULT NULL,
  `email` varchar(50) DEFAULT NULL,
  `qualification` varchar(255) DEFAULT NULL,
  `isAdmin` tinyint(4) NOT NULL,
  PRIMARY KEY (`userId`),
  UNIQUE KEY `userName` (`userName`),
  KEY `password` (`password`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX idxUserUsername on user (userName);

DROP TABLE IF EXISTS `module`;
CREATE TABLE `module` (
  `moduleId` int(5) NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `code` varchar(20) NOT NULL,
  `credits` tinyint(4) DEFAULT NULL,
  `moduleOwner` int(5) DEFAULT NULL,
  `purpose` text NOT NULL,
  `editBy` int(5) NOT NULL,
  `editTS` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`moduleId`),
  KEY `fk_module_editBy` (`editBy`),
  KEY `fk_module_owner` (`moduleOwner`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE INDEX idxModuleName on module (name);

CREATE TABLE modulePrerequisite (
  modulePrerequisiteId int(5) NOT NULL AUTO_INCREMENT,
  moduleId int(5) NOT NULL,
  moduleIdPrerequisite int(5) NOT NULL,
  CONSTRAINT fk_module_module FOREIGN KEY (moduleId) REFERENCES module(moduleId),
  CONSTRAINT fk_module_moduleIdPrerequisite FOREIGN KEY (moduleIdPrerequisite) REFERENCES module(moduleId),
  PRIMARY KEY (modulePrerequisiteId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX idxModulePrerequisiteModPreReq on modulePrerequisite (moduleId, moduleIdPrerequisite);

CREATE TABLE moduleRight (
  moduleRightId int(5) NOT NULL AUTO_INCREMENT,
  moduleId int(5) NOT NULL,
  userId int(5) NOT NULL,
  CONSTRAINT fk_moduleRight_user FOREIGN KEY (userId) REFERENCES user(userId) ON DELETE CASCADE,
  CONSTRAINT fk_moduleRight_module FOREIGN KEY (moduleId) REFERENCES module(moduleId) ON DELETE CASCADE,
  PRIMARY KEY (moduleRightId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX idxModuleRightUserModulRead on moduleRight (userId, moduleId);

CREATE TABLE syllabus (
  syllabusId int(5) NOT NULL AUTO_INCREMENT,
  moduleId int(5) NOT NULL,
  versionNr int(5) NOT NULL, 
  revisionNr int(5) NOT NULL, 
  academicStaff int(5) NULL,
  semester varchar(50) NULL,
  guidedLearnLecture decimal(5,2), 
  guidedLearnTutorial decimal(5,2), 
  guidedLearnPractical decimal(5,2), 
  guidedLearnOther decimal(5,2), 
  indepLearnLecture decimal(5,2), 
  indepLearnTutorial decimal(5,2), 
  indepLearnPractical decimal(5,2),
  indepLearnOther decimal(5,2), 
  creditHours int(5), 
  learningOutcomes text(4000),
  transferableSkills text(4000),
  synopsis text(4000),
  assessmentCode varchar(50),
  mainReferences text(4000),
  addReferences text(4000),
  addInformation text(4000),
  approvedBy int(5),
  approvedTS timestamp,  
  editBy int(5) NOT NULL,
  editTS timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  
  CONSTRAINT fk_syllabus_module FOREIGN KEY (moduleId) REFERENCES module(moduleId) ON DELETE CASCADE,
  CONSTRAINT fk_syllabus_academicStaff FOREIGN KEY (academicStaff) REFERENCES user(userId),
  CONSTRAINT fk_syllabus_approvedBy FOREIGN KEY (approvedBy) REFERENCES user(userId),
  CONSTRAINT fk_syllabus_editBy FOREIGN KEY (editBy) REFERENCES user(userId),
  PRIMARY KEY (syllabusId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX idxSyllabusModulVersion on syllabus (moduleId, versionNr, revisionNr);

CREATE TABLE syllabusTopic (
  syllabusTopicId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  topicNr int(5) NOT NULL,
  description varchar(255) NOT NULL,
  
  guidedLearnLecture decimal(5,2), 
  guidedLearnTutorial decimal(5,2), 
  guidedLearnPractical decimal(5,2), 
  guidedLearnOther decimal(5,2), 
  indepLearnLecture decimal(5,2), 
  indepLearnTutorial decimal(5,2), 
  indepLearnPractical decimal(5,2),
  indepLearnOther decimal(5,2), 
  
  CONSTRAINT fk_syllabusTopic_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  PRIMARY KEY (syllabusTopicId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX idxSyllabusTopicSyllabusTopicNr on syllabusTopic (syllabusId, topicNr);

CREATE TABLE assessmentType (
  assessmentTypeId int(5) NOT NULL AUTO_INCREMENT,
  description varchar(255) NOT NULL,
  PRIMARY KEY (assessmentTypeId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
Insert into assessmentType(description) values ('Final Exam'), ('Assignment'), ('Assignment/ Documentation'), ('Presentation');

CREATE TABLE syllabusAssessmentType (
  syllabusAssessmentTypeId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  assessmentTypeId int(5) NOT NULL,
  guidedLearning decimal(5,2), 
  indepLearning decimal(5,2), 
  CONSTRAINT fk_syllabusAssessmentType_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  CONSTRAINT fk_syllabusAssessmentType_assessmentType FOREIGN KEY (assessmentTypeId) REFERENCES assessmentType(assessmentTypeId),
  PRIMARY KEY (syllabusAssessmentTypeId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX idxSyllabusAssessmentTypesyllabusType on syllabusAssessmentType (syllabusId, assessmentTypeId);


CREATE TABLE mqfSkill (
  mqfSkillId int(5) NOT NULL AUTO_INCREMENT,
  description varchar(255) NOT NULL,
  PRIMARY KEY (mqfSkillId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
Insert into mqfSkill(description) values ('Knowledge'), ('Practical Skill'), ('Communication'), ('Leadership');

CREATE TABLE syllabusMqfSkill (
  syllabusMqfSkillId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  mqfSkillId int(5) NOT NULL,
  CONSTRAINT fk_syllabusMqfSkill_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  CONSTRAINT fk_syllabusMqfSkill_mqfSkill FOREIGN KEY (mqfSkillId) REFERENCES mqfSkill(mqfSkillId),
  PRIMARY KEY (syllabusMqfSkillId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX idxSyllabusMqfSkillsyllabusType on syllabusMqfSkill (syllabusId, mqfSkillId);


CREATE TABLE teachLearnActivity (
  teachLearnActivityId int(5) NOT NULL AUTO_INCREMENT,
  description varchar(255) NOT NULL,
  PRIMARY KEY (teachLearnActivityId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
Insert into teachLearnActivity(description) values ('Lecture'), ('Tutorial'), ('Lab'), ('Group work and Discussion');

CREATE TABLE syllabusTeachLearnActivity (
  syllabusTeachLearnActivityId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  teachLearnActivityId int(5) NOT NULL,
  CONSTRAINT fk_syllabusTeachLearnActivity_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  CONSTRAINT fk_syllabusTeachLearnActivity_teachLearnActivity FOREIGN KEY (teachLearnActivityId) REFERENCES teachLearnActivity(teachLearnActivityId),
  PRIMARY KEY (syllabusTeachLearnActivityId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE UNIQUE INDEX idxSyllabusTeachLearnActivitySyllabusTeachLearnActivity on syllabusTeachLearnActivity (syllabusId, teachLearnActivityId);

CREATE TABLE modeOfDelivery (
  modeOfDeliveryId int(5) NOT NULL AUTO_INCREMENT,
  description varchar(255) NOT NULL,
  PRIMARY KEY (modeOfDeliveryId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
Insert into modeOfDelivery(description) values ('Lecture'), ('Tutorial'), ('Practical'), ('Workshop'), ('Seminar');

CREATE TABLE syllabusModeOfDelivery (
  syllabusModeOfDeliveryId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  modeOfDeliveryId int(5) NOT NULL,
  CONSTRAINT fk_syllabusModeOfDelivery_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  CONSTRAINT fk_syllabusModeOfDelivery_modeOfDelivery FOREIGN KEY (modeOfDeliveryId) REFERENCES modeOfDelivery(modeOfDeliveryId),
  PRIMARY KEY (syllabusModeOfDeliveryId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
CREATE UNIQUE INDEX idxSyllabusModeOfDeliverySyllabusModeOfDeliveryId on syllabusModeOfDelivery (syllabusId, modeOfDeliveryId);

CREATE TABLE syllabusProgAim (
  syllabusProgAimId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  clo int(1) NOT NULL,
  peo int(1) NOT NULL,
  CONSTRAINT fk_syllabusProgAim_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  PRIMARY KEY (syllabusProgAimId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT ='Mapping of the Module to the Programme Aims';
CREATE UNIQUE INDEX idxSyllabusProgAimSyllabusCloPeo on syllabusProgAim (syllabusId, clo, peo);

CREATE TABLE syllabusProgLearnOutcome (
  syllabusProgLearnOutcomeId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  mlo int(1) NOT NULL,
  plo int(1) NOT NULL,
  CONSTRAINT fk_syllabusProgLearnOutcome_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  PRIMARY KEY (syllabusProgLearnOutcomeId)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Mapping of the Module to the Programme Learning Outcomes';
CREATE UNIQUE INDEX idxSyllabusProgLearnOutcomeMloPlo on syllabusProgLearnOutcome (syllabusId, mlo, plo);


DELIMITER //
CREATE PROCEDURE recalcSyllabus(IN id int(5))
BEGIN
	Update 
		syllabus s
		left join (	
			SELECT 
				syllabusId,
				SUM(guidedLearnLecture) as guidedLearnLecture,
				SUM(guidedLearnTutorial) as guidedLearnTutorial, 
				SUM(guidedLearnPractical) as guidedLearnPractical, 
				SUM(guidedLearnOther) as guidedLearnOther, 
				SUM(indepLearnLecture) as indepLearnLecture, 
				SUM(indepLearnTutorial) as indepLearnTutorial, 
				SUM(indepLearnPractical) as indepLearnPractical,
				SUM(indepLearnOther) as indepLearnOther
			FROM 
				syllabusTopic
			WHERE 
				syllabusId = id
			Group by 
				syllabusId
			) t on s.syllabusId = t.syllabusId
	SET 
		s.guidedLearnLecture = t.guidedLearnLecture,
		s.guidedLearnTutorial = t.guidedLearnTutorial,
		s.guidedLearnPractical = t.guidedLearnPractical,
		s.guidedLearnOther = t.guidedLearnOther,
		s.indepLearnLecture = t.indepLearnLecture,
		s.indepLearnTutorial = t.indepLearnTutorial,
		s.indepLearnPractical = t.indepLearnPractical,
		s.indepLearnOther = t.indepLearnOther
	WHERE 
		s.syllabusId = id;
END //

CREATE PROCEDURE copySyllabus(IN id int(5), IN newVersionNr int(5), IN userId int(5))
BEGIN
	DECLARE copyId INT;
	
	Insert into  
		syllabus (moduleId, versionNr, revisionNr, academicStaff, semester, creditHours, learningOutcomes, 
		transferableSkills, synopsis, assessmentCode, mainReferences, addReferences, addInformation, editBy)
	SELECT moduleId, newVersionNr, 0, academicStaff, semester, creditHours, learningOutcomes, 
		transferableSkills, synopsis, assessmentCode, mainReferences, addReferences, addInformation, userId
	FROM 
		syllabus s
	WHERE 
		s.syllabusId = id;
		
	SET copyId = LAST_INSERT_ID();
	
	Insert into  
		syllabusassessmenttype (syllabusId, assessmentTypeId, guidedLearning, indepLearning) 
	SELECT copyId, assessmentTypeId, guidedLearning, indepLearning FROM syllabusassessmenttype WHERE syllabusId = id;
	
	Insert into  
		syllabusmodeofdelivery (syllabusId, modeOfDeliveryId) 
	SELECT copyId, modeOfDeliveryId FROM syllabusmodeofdelivery WHERE syllabusId = id;
	
	Insert into  
		syllabusmqfskill (syllabusId, mqfSkillId) 
	SELECT copyId, mqfSkillId FROM syllabusmqfskill WHERE syllabusId = id;
	
	Insert into  
		syllabusprogaim (syllabusId, clo, peo) 
	SELECT copyId, clo, peo FROM syllabusprogaim WHERE syllabusId = id;
	
	Insert into  
		syllabusproglearnoutcome (syllabusId, mlo, plo) 
	SELECT copyId, mlo, plo FROM syllabusproglearnoutcome WHERE syllabusId = id;
	
	Insert into  
		syllabusteachlearnactivity (syllabusId, teachLearnActivityId) 
	SELECT copyId, teachLearnActivityId FROM syllabusteachlearnactivity WHERE syllabusId = id;
	
	Insert into  
		syllabustopic (syllabusId, topicNr, description, guidedLearnLecture, guidedLearnTutorial, guidedLearnPractical, 
			guidedLearnOther, indepLearnLecture, indepLearnTutorial, indepLearnPractical, indepLearnOther) 
	SELECT copyId, topicNr, description, guidedLearnLecture, guidedLearnTutorial, guidedLearnPractical, 
		guidedLearnOther, indepLearnLecture, indepLearnTutorial, indepLearnPractical, indepLearnOther 
	FROM syllabustopic WHERE syllabusId = id;
	
END //

CREATE PROCEDURE deleteSyllabus(IN id int(5))
BEGIN	
	DELETE FROM syllabusassessmenttype WHERE syllabusId = id;
	DELETE FROM syllabusmodeofdelivery WHERE syllabusId = id;
	DELETE FROM syllabusmqfskill WHERE syllabusId = id;
	DELETE FROM syllabusprogaim WHERE syllabusId = id;
	DELETE FROM syllabusproglearnoutcome WHERE syllabusId = id;
	DELETE FROM syllabusteachlearnactivity WHERE syllabusId = id;
	DELETE FROM syllabustopic WHERE syllabusId = id;
	DELETE FROM syllabus	WHERE syllabusId = id;
	
END //

CREATE TRIGGER trgSyllabusTopicInserted AFTER INSERT ON syllabusTopic
FOR EACH ROW
BEGIN
	CALL recalcSyllabus(NEW.syllabusId);
END; //

CREATE TRIGGER trgSyllabusTopicUpdated AFTER UPDATE ON syllabusTopic
FOR EACH ROW
BEGIN
	CALL recalcSyllabus(NEW.syllabusId);
END; //

CREATE TRIGGER trgSyllabusTopicDeleted AFTER DELETE ON syllabusTopic
FOR EACH ROW
BEGIN
	CALL recalcSyllabus(OLD.syllabusId);
END; //

DELIMITER ;