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

CREATE TABLE user (
  userId int(5) NOT NULL AUTO_INCREMENT,
  userName varchar(50) NOT NULL UNIQUE,
  password varchar(60) NOT NULL,
  name varchar(50),
  surname varchar(50),
  email varchar(50),
  qualification varchar(255),
  isAdmin bit NOT NULL DEFAULT 0,
  PRIMARY KEY (userId),
  KEY password (password)
);

CREATE TABLE module (
  moduleId int(5) NOT NULL AUTO_INCREMENT,
  name varchar(50) NOT NULL,
  code varchar(50) NOT NULL,
  credits int(2),
  moduleOwner int(5),
  purpose text NOT NULL,
  editBy int(5) NOT NULL,
  editTS timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  CONSTRAINT fk_module_editBy FOREIGN KEY (editBy) REFERENCES user(userId),
  CONSTRAINT fk_module_owner FOREIGN KEY (moduleOwner) REFERENCES user(userId),
  PRIMARY KEY (moduleId)
);

CREATE TABLE modulePrerequisite (
  modulePrerequisiteId int(5) NOT NULL AUTO_INCREMENT,
  moduleId int(5) NOT NULL,
  moduleIdPrerequisite int(5) NOT NULL,
  CONSTRAINT fk_module_module FOREIGN KEY (moduleId) REFERENCES module(moduleId),
  CONSTRAINT fk_module_moduleIdPrerequisite FOREIGN KEY (moduleIdPrerequisite) REFERENCES module(moduleId),
  PRIMARY KEY (modulePrerequisiteId)
);

CREATE TABLE moduleRight (
  moduleRightId int(5) NOT NULL AUTO_INCREMENT,
  moduleId int(5) NOT NULL,
  userId int(5) NOT NULL,
  canRead bit NOT NULL DEFAULT 0,
  canWrite bit NOT NULL DEFAULT 0,
  canApprove bit NOT NULL DEFAULT 0,
  CONSTRAINT fk_moduleRight_user FOREIGN KEY (userId) REFERENCES user(userId) ON DELETE CASCADE,
  CONSTRAINT fk_moduleRight_module FOREIGN KEY (moduleId) REFERENCES module(moduleId) ON DELETE CASCADE,
  PRIMARY KEY (moduleRightId)
);

CREATE TABLE syllabus (
  syllabusId int(5) NOT NULL AUTO_INCREMENT,
  moduleId int(5) NOT NULL,
  versionNr int(5) NOT NULL, 
  revisionNr int(5) NOT NULL, 
  academicStaff int(5) NOT NULL,
  semester varchar(50) NOT NULL,
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
);

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
);


CREATE TABLE assessmentType (
  assessmentTypeId int(5) NOT NULL AUTO_INCREMENT,
  description varchar(255) NOT NULL,
  PRIMARY KEY (assessmentTypeId)
);
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
);


CREATE TABLE mqfSkill (
  mqfSkillId int(5) NOT NULL AUTO_INCREMENT,
  description varchar(255) NOT NULL,
  PRIMARY KEY (mqfSkillId)
);
Insert into mqfSkill(description) values ('Knowledge'), ('Practical Skill'), ('Communication'), ('Leadership');

CREATE TABLE syllabusMqfSkill (
  syllabusMqfSkillId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  mqfSkillId int(5) NOT NULL,
  CONSTRAINT fk_syllabusMqfSkill_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  CONSTRAINT fk_syllabusMqfSkill_mqfSkill FOREIGN KEY (mqfSkillId) REFERENCES mqfSkill(mqfSkillId),
  PRIMARY KEY (syllabusMqfSkillId)
);


CREATE TABLE teachLearnActivity (
  teachLearnActivityId int(5) NOT NULL AUTO_INCREMENT,
  description varchar(255) NOT NULL,
  PRIMARY KEY (teachLearnActivityId)
);
Insert into teachLearnActivity(description) values ('Lecture'), ('Tutorial'), ('Lab'), ('Group work and Discussion');

CREATE TABLE syllabusTeachLearnActivity (
  syllabusTeachLearnActivityId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  teachLearnActivityId int(5) NOT NULL,
  CONSTRAINT fk_syllabusTeachLearnActivity_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  CONSTRAINT fk_syllabusTeachLearnActivity_teachLearnActivity FOREIGN KEY (teachLearnActivityId) REFERENCES teachLearnActivity(teachLearnActivityId),
  PRIMARY KEY (syllabusTeachLearnActivityId)
);


CREATE TABLE modeOfDelivery (
  modeOfDeliveryId int(5) NOT NULL AUTO_INCREMENT,
  description varchar(255) NOT NULL,
  PRIMARY KEY (modeOfDeliveryId)
);
Insert into modeOfDelivery(description) values ('Lecture'), ('Tutorial'), ('Practical'), ('Workshop'), ('Seminar');

CREATE TABLE syllabusModeOfDelivery (
  syllabusModeOfDeliveryId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  modeOfDeliveryId int(5) NOT NULL,
  CONSTRAINT fk_syllabusModeOfDelivery_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  CONSTRAINT fk_syllabusModeOfDelivery_modeOfDelivery FOREIGN KEY (modeOfDeliveryId) REFERENCES modeOfDelivery(modeOfDeliveryId),
  PRIMARY KEY (syllabusModeOfDeliveryId)
);

CREATE TABLE syllabusProgAim (
  syllabusProgAimId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  clo int(1) NOT NULL,
  peo int(1) NOT NULL,
  CONSTRAINT fk_syllabusProgAim_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  PRIMARY KEY (syllabusProgAimId)
) COMMENT ='Mapping of the Module to the Programme Aims';

CREATE TABLE syllabusProgLearnOutcome (
  syllabusProgLearnOutcomeId int(5) NOT NULL AUTO_INCREMENT,
  syllabusId int(5) NOT NULL,
  mlo int(1) NOT NULL,
  plo int(1) NOT NULL,
  CONSTRAINT fk_syllabusProgLearnOutcome_syllabus FOREIGN KEY (syllabusId) REFERENCES syllabus(syllabusId),
  PRIMARY KEY (syllabusProgLearnOutcomeId)
) COMMENT='Mapping of the Module to the Programme Learning Outcomes';


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