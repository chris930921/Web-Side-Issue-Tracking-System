<?php
	header("Content-Type:application/json");
	include "connect.php";
	include "exception.php";
	include "checker.php";

	$token = checkAndEqual('token',40);
	$issue_id = checkAndZero('issue_id',10);
	$issue_id = to_number('issue_id',$issue_id);

	$result = query("SELECT user_id FROM token WHERE token like '$token';");
	is_no_permission($result);
	$current_user_id = $result[0]['user_id'];

	//get issue
	$sql = 'SELECT 
	 ticket_issue.id, 
	 ticket_issue.title,
	 ticket_issue.publisher_id,
	 ticket_issue.content,
	 states.name as state, 
	 priorities.name as priority,
	 schedule_issue.occurency_date,
	 schedule_issue.expectation_date,
	 schedule_issue.finished_date

	 FROM ticket_issue
	 LEFT JOIN schedule_issue ON schedule_issue.ticket_id = ticket_issue.id
	 LEFT JOIN states ON states.id = ticket_issue.state
	 LEFT JOIN priorities ON priorities.id = ticket_issue.priority

	 WHERE ticket_issue.id = '.$issue_id.' ;';
	$result = query($sql);
	is_excute_success($result);
	$view = $result[0];

	//get issue publisher_id
	$sql = 'SELECT login.email FROM login WHERE login.id = '.$view['publisher_id'].' ;';
	$result = query($sql);
	is_excute_success($result);
	$view['publisher_name'] = $result[0]['email'];

	//get issue charge by someone
	$sql = 'SELECT * FROM person_in_charge WHERE ticket_id = '.$issue_id.';';
	$result = query($sql);
	is_excute_success($result);
	$view['is_charge'] = count($result) > 0;

	if($view['is_charge']){
		$view['charge_id'] = $result[0]['publisher_id'];
		//get charge person name
		$sql = 'SELECT login.email FROM login WHERE login.id = '.$view['charge_id'].' ;';
		$result = query($sql);
		is_excute_success($result);
		$view['charge_name'] = $result[0]['email'];
		$view['is_charge_owner'] = ($view['charge_id'] == $current_user_id);
	}

	//get message_list
	$sql = 'SELECT login.email, message_ticket.message 
		FROM message_ticket 
		LEFT JOIN login ON login.id = message_ticket.publisher_id 
		WHERE message_ticket.ticket_id = '.$issue_id.'
		ORDER BY message_ticket.comment_date ASC;';

	$result = query($sql);
	is_excute_success($result);
	$view['message'] = $result;

	echo json_encode($view);