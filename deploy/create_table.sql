
-- clear

IF OBJECT_ID('dbo.token', 'U') IS NOT NULL DROP TABLE dbo.token;
IF OBJECT_ID('dbo.message_ticket', 'U') IS NOT NULL DROP TABLE dbo.message_ticket;
IF OBJECT_ID('dbo.schedule_issue', 'U') IS NOT NULL DROP TABLE dbo.schedule_issue;
IF OBJECT_ID('dbo.person_in_charge', 'U') IS NOT NULL DROP TABLE dbo.person_in_charge;
IF OBJECT_ID('dbo.ticket_issue', 'U') IS NOT NULL DROP TABLE dbo.ticket_issue;
IF OBJECT_ID('dbo.login', 'U') IS NOT NULL DROP TABLE dbo.login;
IF OBJECT_ID('dbo.priorities', 'U') IS NOT NULL DROP TABLE dbo.priorities;
IF OBJECT_ID('dbo.states', 'U') IS NOT NULL DROP TABLE dbo.states;

-- create
CREATE TABLE states(
	id int NOT NULL IDENTITY(1,1),
	name TEXT NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE priorities(
	id int NOT NULL IDENTITY(1,1),
	name TEXT NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE login (
  id int NOT NULL IDENTITY(1,1),
  email VARCHAR(50) NOT NULL DEFAULT '0' UNIQUE,
  password TEXT NOT NULL DEFAULT '0',
  salt TEXT NOT NULL DEFAULT '0',
  PRIMARY KEY (id),
);

CREATE TABLE ticket_issue(
	id int NOT NULL IDENTITY(1,1),
	publisher_id int NOT NULL DEFAULT '0' FOREIGN KEY REFERENCES login(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
	title text NOT NULL,
	content text NOT NULL,
	state int NOT NULL DEFAULT '0' FOREIGN KEY REFERENCES states(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
	priority int NOT NULL DEFAULT '0' FOREIGN KEY REFERENCES priorities(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
	PRIMARY KEY (id),
);

CREATE TABLE person_in_charge(
	ticket_id int NOT NULL DEFAULT '0' FOREIGN KEY REFERENCES ticket_issue(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
	publisher_id int NOT NULL DEFAULT '0' FOREIGN KEY REFERENCES login(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
);

CREATE TABLE schedule_issue(
	ticket_id int NOT NULL DEFAULT '0' FOREIGN KEY REFERENCES ticket_issue(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
	occurency_date datetime NOT NULL,
	expectation_date datetime ,
	finished_date datetime ,
);

CREATE TABLE message_ticket(
	id int NOT NULL IDENTITY(1,1),
	ticket_id int NOT NULL DEFAULT '0' FOREIGN KEY REFERENCES ticket_issue(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
	publisher_id int NOT NULL DEFAULT '0' FOREIGN KEY REFERENCES login(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
	comment_date datetime NOT NULL,
	message text NOT NULL,
	PRIMARY KEY (id),
);

CREATE TABLE token(
	token TEXT NOT NULL DEFAULT '0',
	user_id int NOT NULL DEFAULT '0' FOREIGN KEY REFERENCES login(id) ON DELETE NO ACTION ON UPDATE NO ACTION,
	timestamp datetime NOT NULL,
);


-- insert constant data-----------------------------------------------------------------------------------------
INSERT INTO databaseFinal..states(name) VALUES('新建立');
INSERT INTO databaseFinal..states(name) VALUES('已指派');
INSERT INTO databaseFinal..states(name) VALUES('已解決');

INSERT INTO  databaseFinal..priorities(name) VALUES('低');
INSERT INTO  databaseFinal..priorities(name) VALUES('中等');
INSERT INTO  databaseFinal..priorities(name) VALUES('高');

-- insert test data-----------------------------------------------------------------------------------------
-- password 123456
DECLARE @publisher1_id char
DECLARE @publisher2_id char

INSERT INTO databaseFinal..login(email,password,salt)
VALUES('asdzxc@email.com','9a9eb9c3582cc2603014e97e05dbc589908325c1','1234567890123456');
SET @publisher1_id = SCOPE_IDENTITY();

INSERT INTO  databaseFinal..login(email,password,salt)
VALUES('qwerty@email.com','9a9eb9c3582cc2603014e97e05dbc589908325c1','1234567890123456');
SET @publisher2_id = SCOPE_IDENTITY();

INSERT INTO databaseFinal..ticket_issue(publisher_id,title,content,state,priority)
VALUES(@publisher1_id,'how to catch the value of a dropdown using jquery and pass it to the controller','i am very new to jquery and mvc want to learn something like, i have two dropdown in mvc app i want to catch that selected value using jquery and want to pass it to the controller how can i do that.here is my dropdwon code in my view . please help.',1,2);
INSERT INTO databaseFinal..schedule_issue(ticket_id,occurency_date,expectation_date)
VALUES(SCOPE_IDENTITY(),CURRENT_TIMESTAMP,DATEADD(DAY,10,CURRENT_TIMESTAMP));

INSERT INTO databaseFinal..ticket_issue(publisher_id,title,content,state,priority)
VALUES(@publisher2_id,'How to echo a form value into url without page refresh','How can I accomplish to make form.action="analyse1.php" in this case be form.action="analyseday1.php ',1,3);
INSERT INTO databaseFinal..schedule_issue(ticket_id,occurency_date,expectation_date)
VALUES(SCOPE_IDENTITY(),CURRENT_TIMESTAMP,DATEADD(DAY,15,CURRENT_TIMESTAMP));
INSERT INTO databaseFinal..ticket_issue(publisher_id,title,content,state,priority)
VALUES(@publisher2_id,'gmap3 | 2nd or 3rd maps are not working properly when I used them in tabs,','',2,3);
INSERT INTO databaseFinal..schedule_issue(ticket_id,occurency_date,expectation_date)
VALUES(SCOPE_IDENTITY(),CURRENT_TIMESTAMP,DATEADD(DAY,20,CURRENT_TIMESTAMP));

INSERT INTO databaseFinal..ticket_issue(publisher_id,title,content,state,priority)
VALUES(@publisher1_id,'Where NOT in pivot table','Maybe I can use show hide in js but not sure how to find a way to work it. Perhaps the problem is caused by maps are placing over each. You can reach to page via',3,1);
INSERT INTO databaseFinal..schedule_issue(ticket_id,occurency_date,expectation_date)
VALUES(SCOPE_IDENTITY(),CURRENT_TIMESTAMP,DATEADD(DAY,25,CURRENT_TIMESTAMP));

INSERT INTO databaseFinal..ticket_issue(publisher_id,title,content,state,priority)
VALUES(@publisher1_id,'NPE while publishing jasper reports?','However what if I want to get the opposite of that. And get all items the user DOES NOT have yet. So NOT in the pivot table.',2,1);
INSERT INTO databaseFinal..schedule_issue(ticket_id,occurency_date,expectation_date)
VALUES(SCOPE_IDENTITY(),'2015-01-01 12:00:00' ,CURRENT_TIMESTAMP);
