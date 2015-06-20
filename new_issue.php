<?php
	header("Content-Type:application/json");
	include "connect.php";
	include "exception.php";
	include "checker.php";

	$token = checkAndEqual('token',40);
	$title = checkAndZero('title',200);
	$state = checkAndZero('state',5);
	$priority = checkAndZero('priority',5);
	$expectation = checkAndZero('expectation',20);
	$content = check('content',5000);

	$state = to_number('state',$state);
	$priority = to_number('priority',$priority);

	//get token
	$result = query("SELECT user_id FROM token WHERE token like '$token';");
	is_no_permission($result);
	$publisher_id = $result[0]['user_id'];

	//insert issue info
	$sql = "INSERT INTO ticket_issue (
		publisher_id,title,content,state,priority
		) VALUES (".$publisher_id.", '".$title."', '".$content."', ".$state.", ".$priority.");
	
		SELECT SCOPE_IDENTITY() AS lastInsertId;	
	";
	$result = query($sql);
	is_excute_success($result);
	$issue_id = $result[0]['lastInsertId'];

	//insert issue schedule
	$sql = "INSERT INTO schedule_issue(
		ticket_id,occurency_date,expectation_date
		) VALUES ( ".$issue_id." ,CURRENT_TIMESTAMP, '".$expectation."' );";

	$result = excute($sql);
	echo json_encode(array('state'=>$result));