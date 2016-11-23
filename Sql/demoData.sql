
INSERT INTO `user` (`userId`, `userName`, `password`, `name`, `surname`, `email`, `qualification`, `isAdmin`) VALUES
(1,	'admin',	'$2a$11$PrBfxnvJRlQ63Uif2EenGeek1E2BzNEf7WGw3ESL5RBOUSOjaNvRK',	'Administrator',	'admin',	'admin@example.com',	'Superuser',	1),
(2,	'LimEngLye',	'$2a$11$zLP04oaXKmNB/yfEmYf0k.SVZinGwDoS3aiAMF7Kyx3l1PscXpxdO',	'Lye',	'Lim Eng',	NULL,	'Bachelor of Science (Computer Science), Master of Computer Science',	0);

INSERT INTO `module` (`moduleId`, `name`, `code`, `credits`, `moduleOwner`, `purpose`, `editBy`, `editTS`) VALUES
(1,	'Web Database Applications',	'ITS62304',	4,	NULL,	'This module introduces students to the principles and practice of implementing and designing medium-size web database applications. Topics include server side scripting, session management, authentication and authorization. 60% of the assessment is assignment work, emphasizing the practical nature of the subject.',	1,	'2016-11-18 08:43:44'),
(2,	'Web Systems and Technologies',	'ITS61104',	4,	NULL,	'This module introduces the student to the basics of web technology concepts, the principles and tools that can be used to develop web applications. Topics would include Internet protocols, HTML and XML files, client processing with JavaScript and server side processing with PHP',	1,	'2016-11-18 08:44:13'),
(3,	'Distributed Application Development',	'ITS61604',	4,	NULL,	'This course introduces the concepts of distributed application development. Topics covered include client-server model and programming in socket level and using Remote Method Invocation (RMI). Laboratory instruction will include program development and walk-through.',	1,	'2016-11-18 08:41:12'),
(4,	'Data Structures and Algorithms',	'ITS60504',	4,	NULL,	'This course introduces students to algorithm analysis and discusses the working of various data structures in details. Topics covered include Principles of Algorithms Analysis, Linked Lists, Stacks and Queues, Trees and Recursion, Hashing, Sorting Methods, Binary Search Trees and Graph Theory.',	1,	'2016-11-18 08:41:45'),
(5,	'Forensic Computing Practice',	'ITS61503',	3,	NULL,	'This subject allows students to look in-depth into an individual computer crime scenario simulating a source of evidence of one or more computer-related crimes. They are to investigate the contents of the scenario using appropriate tools. Throughout the duration of the module advice can be sought from the subject tutor with whom the suitability of different approaches and the significance of particular pieces of evidence can be discussed.\r\nAs a result of their investigation students are to write a report detailing their findings for submission as evidence. Finally, they will give evidence as an expert witness in a mock courtroom and be cross examined by their peers or by staff.',	1,	'2016-11-18 08:42:35'),
(6,	'OOP using C++',	'ITS61804',	4,	NULL,	'This course strengthens studentsâ€™ understanding of object-oriented programming concept and introduces them to OO concepts supported in C++. Topics covered include inheritance, polymorphism, and generic programming, Standard Template Library, and design patterns.',	1,	'2016-11-18 08:43:00');

Insert into moduleRight (userId, moduleId) 
SELECT u.userId, m.moduleId
FROM user u cross join module m;

insert into syllabus(moduleId, versionNr, revisionNr, creditHours, academicStaff, semester, editBy, learningOutcomes, transferableSkills, synopsis)
	values
		((SELECT moduleId from module where code like 'ITS62304' LIMIT 1), 1, 2, 4, (SELECT userId from user where userName like 'admin' LIMIT 1), 'elective', (SELECT userId from user where userName like 'LimEngLye' LIMIT 1),
		'At the end of this course, the students should be able to:\n 1. Demonstrate the knowledge in developing web database solutions and web database applications. (C5, MQF LO 1)\n 2. Develop medium scale web applications using MySQL databases and evaluate the performance of the web database systems. (C6, P7, MQF LO 2)\n 3. Communicate effectively with peers to work on a medium scale web application in a team to accomplish task and demonstrate ability to work as an individual or in a team with leadership skills. (C3, P5, MQF LO 5)',
		'Helps to learn Practical Skills, Communication and Team work.',
		'This subject introduces students to the principles and practice of implementing and designing medium-size web database applications. Topics include server side scripting, session management, authentication and authorization.'),
		((SELECT moduleId from module where code like 'ITS62304' LIMIT 1), 2, 1, 4, (SELECT userId from user where userName like 'admin' LIMIT 1), 'elective', (SELECT userId from user where userName like 'LimEngLye' LIMIT 1),
		'At the end of this course, the students should be able to:\n 1. Demonstrate the knowledge in developing web database solutions and web database applications. (C5, MQF LO 1)\n 2. Develop medium scale web applications using MySQL databases and evaluate the performance of the web database systems. (C6, P7, MQF LO 2)\n 3. Communicate effectively with peers to work on a medium scale web application in a team to accomplish task and demonstrate ability to work as an individual or in a team with leadership skills. (C3, P5, MQF LO 5)',
		'Helps to learn Practical Skills, Communication and Team work.',
		'This subject introduces students to the principles and practice of implementing and designing medium-size web database applications. Topics include server side scripting, session management, authentication and authorization.');
		

DELIMITER //		
CREATE PROCEDURE createSyllabusRelatedData ()
BEGIN
	DECLARE syl1 INT;
	DECLARE syl2 INT;
	SELECT syllabusId INTO syl1 FROM syllabus s join module m on s.moduleId = m.moduleId WHERE m.code like 'ITS62304' AND s.versionNr = 1;
	SELECT syllabusId INTO syl2 FROM syllabus s join module m on s.moduleId = m.moduleId WHERE m.code like 'ITS62304' AND s.versionNr = 2;
	
	insert into syllabusTopic(syllabusId, topicNr, description, guidedLearnLecture, guidedLearnTutorial, guidedLearnPractical, guidedLearnOther, indepLearnLecture, indepLearnTutorial, indepLearnPractical, indepLearnOther) 
		values
			(syl1, 1, 'Course introduction. Introduction - Database Applications and the Web', 1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl1, 2, 'PHP Programming',2, 3, 4, 0, 4, 3, 0, 0),
			(syl1, 3, 'Database Query',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl1, 4, 'User-driven Query',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl1, 5, 'Abstraction (Using PEAR abstraction to separate PHP and HTML scripts)',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl1, 6, 'Abstraction(Writing PHP scripts to write database)',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl1, 7, 'Writing to Web Databases',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl1, 8, 'Validation \n- Design of database for group project\n- Validating HTML form user input on client-side and server-side',2, 3, 4, 0, 4, 3, 0, 0),
			(syl1, 9, 'Session',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl1, 10, 'Authentication and security',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl1, 11, 'Performance and Web Optimization',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl1, 12, 'Revision',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 1, 'Course introduction. Introduction - Database Applications and the Web', 1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 2, 'PHP Programming', 2, 3, 4, 0, 4, 3, 0, 0),
			(syl2, 3, 'Database Query', 1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 4, 'User-driven Query',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 5, 'Abstraction (Using PEAR abstraction to separate PHP and HTML scripts)',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 6, 'Abstraction(Writing PHP scripts to write database)',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 7, 'Writing to Web Databases',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 8, 'Validation \n- Design of database for group project\n- Validating HTML form user input on client-side and server-side',2, 3, 4, 0, 4, 3, 0, 0),
			(syl2, 9, 'Session',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 10, 'Authentication and security',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 11, 'Performance and Web Optimization',1, 1.5, 2, 0, 2, 1.5, 0, 0),
			(syl2, 12, 'Revision',1, 1.5, 2, 0, 2, 1.5, 0, 0);
			
		INSERT INTO syllabusAssessmentType (syllabusId, assessmentTypeId, guidedLearning, indepLearning)
			VALUES 
				(syl1, 1, 0, 14),
				(syl1, 2, 0.5, 27.5),
				(syl1, 3, 2, 6),
				(syl2, 1, 0, 14),
				(syl2, 2, 0.5, 27.5),
				(syl2, 3, 2, 6);
				
		INSERT INTO syllabusModeOfDelivery (syllabusId, modeOfDeliveryId)
		SELECT syl1, modeOfDeliveryId FROM modeOfDelivery;
		
		INSERT INTO syllabusModeOfDelivery (syllabusId, modeOfDeliveryId)
		SELECT syl2, modeOfDeliveryId FROM modeOfDelivery;
				
		INSERT INTO syllabusMqfSkill (syllabusId, mqfSkillId)
		SELECT syl1, mqfSkillId FROM mqfSkill;
		
		INSERT INTO syllabusMqfSkill (syllabusId, mqfSkillId)
		SELECT syl2, mqfSkillId FROM mqfSkill;
		
		INSERT INTO syllabusteachLearnActivity (syllabusId, teachLearnActivityId)
		SELECT syl1, teachLearnActivityId FROM teachlearnactivity;
		
		INSERT INTO syllabusteachLearnActivity (syllabusId, teachLearnActivityId)
		SELECT syl2, teachLearnActivityId FROM teachlearnactivity;
		
		INSERT INTO syllabusProgLearnOutcome(syllabusId, mlo, plo)
			VALUES 
				(syl1, 1, 1),
				(syl1, 2, 2),
				(syl1, 3, 5),
				(syl2, 1, 1),
				(syl2, 2, 2),
				(syl2, 3, 5);
				
END //
DELIMITER ;
call createSyllabusRelatedData();

INSERT INTO modulePrerequisite (moduleId, moduleIdPrerequisite)
	SELECT m.moduleId, p.moduleId FROM module m cross join module p WHERE m.code like 'ITS62304' AND (p.code like 'ITS61104' OR p.code like 'ITS61604');