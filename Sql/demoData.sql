
Insert into user (userName, password, surname, name, qualification, isAdmin) 
	values
		('admin', 'admin', 'admin', 'Administrator', 'Superuser', 1),
		('LimEngLye', 'secret', 'Lim Eng', 'Lye', 'Bachelor of Science (Computer Science), Master of Computer Science', 0);

Insert into module (code, name, purpose, credits, editBy) 
	values 
		('ITS62304', 'WEB DATABASE APPLICATIONS', 'The purpose of the module is to introduce the use of PHP to generate web pages and to introduce the concepts and skills to develop a database backed, dynamic and feature rich website.', 4, (SELECT userId from user where userName like 'admin' LIMIT 1)),
		('ITS61104', 'Web Systems and Technologies', 'This module introduces the student to the basics of web technology concepts, the principles and tools that can be used to develop web applications. Topics would include internet protocols, HTML and XML files, client processing with Javascript and server side processing with PHP.', 4, (SELECT userId from user where userName like 'admin' LIMIT 1));
	