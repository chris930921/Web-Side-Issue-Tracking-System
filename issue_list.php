<?php
	header("Content-Type:application/json");
	include "connect.php";

	if(isset($_POST['token']) == false)	
		exit(json_encode(array("state" =>false, "message"=>"Token is not post.")));
	
	$token = substr($_POST['token'],0,40);
	if(strlen($_POST['token']) != 40)	
		exit(json_encode(array("state" =>false, "message"=>"Token is not valid.")));

	$select_sql = "SELECT TOP 1 1 FROM token WHERE token LIKE '$token' ";
	@$result = query($select_sql);

	if(count($result) == 0 ) 
		exit(json_encode(array("state" =>false, "message"=>"You don't have permission to access.")));

	$select_sql = "SELECT
	 ticket_issue.id,
	 ticket_issue.title,
	 states.name as state,
	 priorities.name as priority,
	 convert(varchar, schedule_issue.occurency_date, 120) AS occurency_date,
	 convert(varchar, schedule_issue.expectation_date, 120) AS expectation_date,
	 convert(varchar, schedule_issue.finished_date, 120) AS finished_date

	 FROM ticket_issue
	 LEFT JOIN schedule_issue ON schedule_issue.ticket_id = ticket_issue.id
	 LEFT JOIN states ON states.id = ticket_issue.state
	 LEFT JOIN priorities ON priorities.id = ticket_issue.priority

	 ORDER BY schedule_issue.occurency_date ASC; ";

	@$result = query($select_sql);
	if(!$result)
		exit(json_encode(array("state" =>false, "message"=>"Database execute fail.")));

	echo json_encode($result);