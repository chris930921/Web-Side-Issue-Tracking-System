<?php
	header("Content-Type:application/json");
	include "connect.php";
	include "exception.php";
	include "checker.php";

	$token = checkAndEqual('token',40);
	$issue_id = checkAndZero('issue_id',10);
	$issue_id = to_number('issue_id',$issue_id);
	$message = check('message',5000);

	$result = query("SELECT user_id FROM token WHERE token like '$token';");
	is_no_permission($result);
	$current_user_id = $result[0]['user_id'];

	//insert message
	$sql = 'INSERT INTO message_ticket(ticket_id,publisher_id,message)
		VALUES ( '.$issue_id.', '.$current_user_id.', "'.$message.'")';
	$result = excute($sql);
	is_excute_success($result);
	
	$view['state'] = true;
	echo json_encode($view);