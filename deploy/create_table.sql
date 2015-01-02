
# clear
DROP TABLE IF EXISTS `token`;
DROP TABLE IF EXISTS `message_ticket`;
DROP TABLE IF EXISTS `schedule_issue`;
DROP TABLE IF EXISTS `person_in_charge`;
DROP TABLE IF EXISTS `ticket_issue`;
DROP TABLE IF EXISTS `login`;
DROP TABLE IF EXISTS `priorities`;
DROP TABLE IF EXISTS `states`;

# create
CREATE TABLE IF NOT EXISTS `states`(
	`id` int(5) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(20) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `priorities`(
	`id` int(5) unsigned NOT NULL AUTO_INCREMENT,
	`name` varchar(20) NOT NULL,
	PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `login` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `email` varchar(30) NOT NULL DEFAULT '0',
  `password` varchar(45) NOT NULL DEFAULT '0',
  `salt` varchar(16) NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  UNIQUE KEY `Email_UNIQUE` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	

CREATE TABLE IF NOT EXISTS `ticket_issue`(
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`publisher_id` int(10) unsigned NOT NULL DEFAULT '0',
	`title` text NOT NULL,
	`content` text NOT NULL,
	`state` int(5) unsigned NOT NULL DEFAULT '0',
	`priority` int(5) unsigned NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	/* CONSTRAINT `id` */ FOREIGN KEY (`publisher_id`) REFERENCES `login` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
	/* CONSTRAINT `id` */ FOREIGN KEY (`state`) REFERENCES `states` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
	/* CONSTRAINT `id` */ FOREIGN KEY (`priority`) REFERENCES `priorities` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;	

CREATE TABLE IF NOT EXISTS `person_in_charge`(
	`ticket_id` int(10) unsigned NOT NULL DEFAULT '0',
	`publisher_id` int(10) unsigned NOT NULL DEFAULT '0',
	/*CONSTRAINT `id` */ FOREIGN KEY (`ticket_id`) REFERENCES `ticket_issue` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
	/*CONSTRAINT `id` */ FOREIGN KEY (`publisher_id`) REFERENCES `login` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
)ENGINE=InnoDB DEFAULT CHARSET=utf8;	

CREATE TABLE IF NOT EXISTS `schedule_issue`(
	`ticket_id` int(10) unsigned NOT NULL DEFAULT '0',
	`occurency_date` timestamp NOT NULL,
	`expectation_date` timestamp ,
	`finished_date` timestamp ,
	/*CONSTRAINT `id` */ FOREIGN KEY (`ticket_id`) REFERENCES `ticket_issue` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
)ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `message_ticket`(
	`id` int(10) unsigned NOT NULL AUTO_INCREMENT,
	`ticket_id` int(10) unsigned NOT NULL DEFAULT '0',
	`publisher_id` int(10) unsigned NOT NULL DEFAULT '0',
	`comment_date` timestamp NOT NULL,
	`message` text NOT NULL,
	PRIMARY KEY (`id`),
	/*CONSTRAINT `id` */ FOREIGN KEY (`ticket_id`) REFERENCES `ticket_issue` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
	/*CONSTRAINT `id` */ FOREIGN KEY (`publisher_id`) REFERENCES `login` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE IF NOT EXISTS `token`(
	`token` varchar(40) NOT NULL DEFAULT '0',
	`user_id` int(10) unsigned NOT NULL DEFAULT '0',
	`timestamp` timestamp NOT NULL,
	/*CONSTRAINT `id` */ FOREIGN KEY (`user_id`) REFERENCES `login` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

# insert constant data-----------------------------------------------------------------------------------------
INSERT INTO `ajax_final_web`.`states`(`name`) VALUES('新建立');
INSERT INTO `ajax_final_web`.`states`(`name`) VALUES('已指派');
INSERT INTO `ajax_final_web`.`states`(`name`) VALUES('已解決');

INSERT INTO `ajax_final_web`.`priorities`(`name`) VALUES('低');
INSERT INTO `ajax_final_web`.`priorities`(`name`) VALUES('中等');
INSERT INTO `ajax_final_web`.`priorities`(`name`) VALUES('高');

# insert test data-----------------------------------------------------------------------------------------
# password 123456
INSERT INTO `ajax_final_web`.`login`(`email`,`password`,`salt`)
VALUES('asdzxc@email.com','9a9eb9c3582cc2603014e97e05dbc589908325c1','1234567890123456');
SET @publisher1_id = LAST_INSERT_ID();

INSERT INTO `ajax_final_web`.`login`(`email`,`password`,`salt`)
VALUES('qwerty@email.com','9a9eb9c3582cc2603014e97e05dbc589908325c1','1234567890123456');
SET @publisher2_id = LAST_INSERT_ID();

INSERT INTO `ajax_final_web`.`ticket_issue`(`publisher_id`,`title`,`content`,`state`,`priority`)
VALUES(@publisher1_id,'how to catch the value of a dropdown using jquery and pass it to the controller','i am very new to jquery and mvc want to learn something like, i have two dropdown in mvc app i want to catch that selected value using jquery and want to pass it to the controller how can i do that.here is my dropdwon code in my view . please help.',1,2);
INSERT INTO `ajax_final_web`.`schedule_issue`(`ticket_id`,`occurency_date`,`expectation_date`)
VALUES(LAST_INSERT_ID(),CURRENT_TIMESTAMP,adddate(CURRENT_TIMESTAMP,INTERVAL 10 DAY));

INSERT INTO `ajax_final_web`.`ticket_issue`(`publisher_id`,`title`,`content`,`state`,`priority`)
VALUES(@publisher2_id,'How to echo a form value into url without page refresh','How can I accomplish to make form.action="analyse1.php" in this case be form.action="analyseday1.php ',1,3);
INSERT INTO `ajax_final_web`.`schedule_issue`(`ticket_id`,`occurency_date`,`expectation_date`)
VALUES(LAST_INSERT_ID(),CURRENT_TIMESTAMP,adddate(CURRENT_TIMESTAMP,INTERVAL 15 DAY));
INSERT INTO `ajax_final_web`.`ticket_issue`(`publisher_id`,`title`,`content`,`state`,`priority`)
VALUES(@publisher2_id,'gmap3 | 2nd or 3rd maps are not working properly when I used them in tabs,','',2,3);
INSERT INTO `ajax_final_web`.`schedule_issue`(`ticket_id`,`occurency_date`,`expectation_date`)
VALUES(LAST_INSERT_ID(),CURRENT_TIMESTAMP,adddate(CURRENT_TIMESTAMP,INTERVAL 20 DAY));

INSERT INTO `ajax_final_web`.`ticket_issue`(`publisher_id`,`title`,`content`,`state`,`priority`)
VALUES(@publisher1_id,'Where NOT in pivot table','Maybe I can use show hide in js but not sure how to find a way to work it. Perhaps the problem is caused by maps are placing over each. You can reach to page via',3,1);
INSERT INTO `ajax_final_web`.`schedule_issue`(`ticket_id`,`occurency_date`,`expectation_date`)
VALUES(LAST_INSERT_ID(),CURRENT_TIMESTAMP,adddate(CURRENT_TIMESTAMP,INTERVAL 25 DAY));

INSERT INTO `ajax_final_web`.`ticket_issue`(`publisher_id`,`title`,`content`,`state`,`priority`)
VALUES(@publisher1_id,'NPE while publishing jasper reports?','However what if I want to get the opposite of that. And get all items the user DOES NOT have yet. So NOT in the pivot table.',2,1);
INSERT INTO `ajax_final_web`.`schedule_issue`(`ticket_id`,`occurency_date`,`expectation_date`)
VALUES(LAST_INSERT_ID(),TIMESTAMP('2015-01-01 12:00:00','12:00:00'),CURRENT_TIMESTAMP);

