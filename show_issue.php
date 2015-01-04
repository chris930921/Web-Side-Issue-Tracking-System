<?php
	header("Content-Type:application/json");

	$token = checkAndEqual('token',40);
	$issue_id = checkAndZero('issue_id',10);
	$issue_id = to_number('issue_id',$issue_id);

	//資料庫
	$host_url = "mysql:host=localhost;port=6033;dbname=ajax_final_web";
	$pdo = new PDO($host_url, "ajax_final","ajax_final" );
	$pdo->query('SET NAMES "UTF8"');
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);

	//get token
	$sql = 'SELECT user_id FROM ajax_final_web.token WHERE token = ?;';
	$statement = $pdo->prepare($sql);
	$statement->bindParam(1, $token);
	is_excute_success($statement->execute());
	$result = is_no_result($statement->fetchAll());
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

	 FROM ajax_final_web.ticket_issue
	 LEFT JOIN schedule_issue ON schedule_issue.ticket_id = ticket_issue.id
	 LEFT JOIN states ON states.id = ticket_issue.state
	 LEFT JOIN priorities ON priorities.id = ticket_issue.priority

	 WHERE ? IN (SELECT token FROM ajax_final_web.token)
	 	AND ticket_issue.id = ? ;';
	$statement = $pdo->prepare($sql);
	$statement->bindParam(1, $token);
	$statement->bindParam(2, $issue_id);
	is_excute_success($statement->execute());
	$result = is_no_permission($statement->fetchAll());
	$statement->closeCursor();
	$view = $result[0];

	//get issue publisher_id
	$sql = 'SELECT login.email FROM ajax_final_web.login WHERE login.id = ? ;';
	$statement = $pdo->prepare($sql);
	$statement->bindParam(1, $view['publisher_id']);
	is_excute_success($statement->execute());
	$result = $statement->fetchAll();
	$statement->closeCursor();
	$view['publisher_name'] = $result[0]['email'];

	//get issue charge by someone
	$sql = 'SELECT * FROM ajax_final_web.person_in_charge WHERE person_in_charge.ticket_id = ?;';
	$statement = $pdo->prepare($sql);
	$statement->bindParam(1, $issue_id);
	is_excute_success($statement->execute());
	$result = $statement->fetchAll();
	$statement->closeCursor();
	$view['is_charge'] = count($result) > 0;

	if($view['is_charge']){
		$view['charge_id'] = $result[0]['publisher_id'];
		//get charge person name
		$sql = 'SELECT login.email FROM ajax_final_web.login WHERE login.id = ? ;';
		$statement = $pdo->prepare($sql);
		$statement->bindParam(1, $view['charge_id']);
		is_excute_success($statement->execute());
		$result = $statement->fetchAll();
		$statement->closeCursor();
		$view['charge_name'] = $result[0]['email'];
		$view['is_charge_owner'] = ($view['charge_id'] == $current_user_id);
	}

	echo json_encode($view);

	function is_excute_success($execute_result){
		$execute_fail = !$execute_result;
		if($execute_fail)	
			exit(json_encode(array("state" =>false, "message"=>"Database execute fail.")));
		return $execute_result;
	}
	function is_no_permission($result){
		if(count($result) == 0) 
			exit(json_encode(array("state" =>false, "message"=>"You don't have permission to access.")));
		return $result;
	}
	function is_no_result($result){
		if(count($result) == 0) 
			exit(json_encode(array("state" =>false, "message"=>"Not find anything.")));
		return $result;
	}
	function checkAndEqual($name,$max_length){
		$check_value = check($name, $max_length);
		if(strlen($check_value) != $max_length)
			exit(json_encode(array("state" =>false, "message"=>$name." is not valid.")));
		return $check_value;
	}
	function checkAndZero($name,$max_length){
		$check_value = check($name, $max_length);
		if(strlen($check_value) == 0)	
			exit(json_encode(array("state" =>false, "message"=>$name." is empty.")));
		return $check_value;
	}
	function check($name, $max_length){
		if(isset($_POST[$name]) == false)	
			exit(json_encode(array("state" =>false, "message"=>$name." is not post.")));
		return substr($_POST[$name],0,$max_length);
	}
	function to_number($name, $check){
		if(!is_numeric($check))
			exit(json_encode(array("state" =>false, "message"=>$name." is not valid number.")));
		return (int)$check;
	}