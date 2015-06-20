<?php
	header("Content-Type:application/json");
	include "connect.php";
	include "exception.php";
	
	if(isset($_POST['token']) == false)	
		exit(json_encode(array("state" =>false, "message"=>"Token is not post.")));
	
	$token = substr($_POST['token'],0,40);
	if(strlen($_POST['token']) != 40)	
		exit(json_encode(array("state" =>false, "message"=>"Token is not valid.")));

	//get token
	$select_sql = "SELECT TOP 1 user_id FROM token WHERE token LIKE '$token' ";
	@$result = query($select_sql);
	is_no_permission($result);
	$own_id = $result[0]['user_id'];

	//get issue
	$select_sql = 'SELECT' 
	.' ticket_issue.id,' 
	.' ticket_issue.title,' 
	.' states.name as state,' 
	.' priorities.name as priority,'
	.' schedule_issue.occurency_date,'
	.' schedule_issue.expectation_date,'
	.' schedule_issue.finished_date'

	.' FROM ticket_issue' 
	.' LEFT JOIN schedule_issue ON schedule_issue.ticket_id = ticket_issue.id' 
	.' LEFT JOIN states ON states.id = ticket_issue.state'
	.' LEFT JOIN priorities ON priorities.id = ticket_issue.priority'

	.' WHERE ticket_issue.publisher_id = '.$own_id.'
		ORDER BY schedule_issue.occurency_date ASC;';

	@$result = query($select_sql);
	is_excute_success($result);
	echo json_encode($result);