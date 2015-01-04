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
	$statement->closeCursor();
	$publisher_id = $result[0]['user_id'];

	//check issue
	$sql = 'SELECT * FROM ajax_final_web.ticket_issue WHERE ticket_issue.id = ? ;';
	$statement = $pdo->prepare($sql);
	$statement->bindParam(1, $issue_id);
	is_excute_success($statement->execute());
	is_no_result($statement->fetchAll());
	$statement->closeCursor();

	//check is charged
	$sql = 'SELECT * FROM ajax_final_web.person_in_charge WHERE person_in_charge.ticket_id = ?;';
	$statement = $pdo->prepare($sql);
	$statement->bindParam(1, $issue_id);
	is_excute_success($statement->execute());
	$result = $statement->fetchAll();
	$statement->closeCursor();
	if(count($result) > 0) exit(json_encode(array("state" =>false, "message"=>"Issue is already charged.")));

	//insert charge person
	$sql = 'INSERT INTO `ajax_final_web`.`person_in_charge`
		(`ticket_id`,`publisher_id`)
		VALUES (?, ?);';
	$statement = $pdo->prepare($sql);
	$statement->bindParam(1, $issue_id);
	$statement->bindParam(2, $publisher_id);
	is_excute_success($statement->execute());

	//update state
	$sql = 'UPDATE `ajax_final_web`.`ticket_issue`
		SET `state` = 2 WHERE `id` = ? ;';
	$statement = $pdo->prepare($sql);
	$statement->bindParam(1, $issue_id);
	is_excute_success($statement->execute());

	$view = array("state" =>true);
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