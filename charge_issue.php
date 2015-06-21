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
	$publisher_id = $result[0]['user_id'];

	//check issue
	$sql = 'SELECT * FROM ticket_issue WHERE ticket_issue.id = '.$issue_id.' ;';
	$result = query($sql);
	is_excute_success($result);
	at_least_one_row($result, 'issue_id');

	//check is charged
	$sql = 'SELECT * FROM person_in_charge WHERE person_in_charge.ticket_id = '.$issue_id.';';
	$result = query($sql);
	
	already_exist($result, 'Issue charge');


	//insert charge person
	$sql = 'INSERT INTO person_in_charge
		(ticket_id,publisher_id)
		VALUES ('.$issue_id.', '.$publisher_id.');';
	$result = excute($sql);
	is_excute_success($result);

	//update state
	$sql = 'UPDATE ticket_issue
		SET state = 2 WHERE id = '.$issue_id.' ;';
	$result = excute($sql);
	is_excute_success($result);

	$view = array("state" =>true);
	echo json_encode($view);