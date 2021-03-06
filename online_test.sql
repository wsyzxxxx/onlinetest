CREATE DATABASE online_test DEFAULT CHARSET utf8 COLLATE utf8_general_ci;

use online_test;

CREATE TABLE student(
	id INT PRIMARY KEY AUTO_INCREMENT,
	username VARCHAR(20) NOT NULL,
	email VARCHAR(60) DEFAULT NULL
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE courses(
	id INT PRIMARY KEY AUTO_INCREMENT,
	name VARCHAR(50) NOT NULL,
	description VARCHAR(255),
	credit INT
);

CREATE TABLE test(
	id INT PRIMARY KEY AUTO_INCREMENT,
	cid INT NOT NULL REFERENCES courses,
	sid INT NOT NULL REFERENCES student
);

CREATE TABLE grade(
	id INT PRIMARY KEY AUTO_INCREMENT,
	cid INT NOT NULL REFERENCES courses,
	sid INT NOT NULL REFERENCES student,
	start_time INT,
	end_time INT,
	score INT NOT NULL
);

CREATE TABLE question(
	id INT PRIMARY KEY AUTO_INCREMENT,
	cid INT REFERENCES courses,
	score INT NOT NULL,
	question VARCHAR(255) NOT NULL,
	choiceA VARCHAR(255) NOT NULL,
	choiceB VARCHAR(255) NOT NULL,
	choiceC VARCHAR(255) NOT NULL,
	choiceD VARCHAR(255) NOT NULL,
	right_ans INT NOT NULL
);

CREATE TABLE answer(
	sid INT REFERENCES student,
	qid INT REFERENCES question,
	answer INT NOT NULL,
	UNIQUE (sid, qid)
);

