<?php
	header("Content-Type:application/json");
	include "connect.php";
	include "exception.php";
	include "checker.php";

	$token = checkAndEqual('token',40);
	$issue_id = checkAndZero('issue_id',10);
	$issue_id = to_number('issue_id',$issue_id);

	//get token
	$result = query("SELECT user_id FROM token WHERE token like '$token';");
	is_no_permission($result);
	$own_id = (int)$result[0]['user_id'];

	//check issue
	$sql = 'SELECT * FROM ticket_issue WHERE ticket_issue.publisher_id = '.$own_id.' AND ticket_issue.id = '.$issue_id.' ;';
	$result = query($sql);
	at_least_one_row($result, 'issue_id');

	//delete issue message
	$sql = 'DELETE FROM message_ticket WHERE message_ticket.ticket_id = '.$issue_id.' ;';
	$result = excute($sql);
	is_excute_success($result);

	//delete issue schedule
	$sql = 'DELETE FROM schedule_issue WHERE schedule_issue.ticket_id = '.$issue_id.' ;';
	$result = excute($sql);
	is_excute_success($result);

	//delete issue charge
	$sql = 'DELETE FROM person_in_charge WHERE person_in_charge.ticket_id = '.$issue_id.' ;';
	$result = excute($sql);
	is_excute_success($result);

	//delete issue
	$sql = 'DELETE FROM ticket_issue WHERE ticket_issue.publisher_id = '.$own_id.' AND ticket_issue.id = '.$issue_id.' ;';
	//echo $sql;
	$result = excute($sql);
	is_excute_success($result);

	$result = array();
	$result['state'] = true;
	echo json_encode($result);