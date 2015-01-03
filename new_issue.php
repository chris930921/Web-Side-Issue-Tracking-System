<?php
	header("Content-Type:application/json");

	$token = checkAndEqual('token',40);
	$title = checkAndZero('title',200);
	$state = checkAndZero('state',5);
	$priority = checkAndZero('priority',5);
	$expectation = checkAndZero('expectation',20);
	$content = check('content',5000);

	$state = to_number('state',$state);
	$priority = to_number('priority',$priority);
	$expectation = strtotime($expectation." GMT+8:00");

	$pdo = connect_database();
	//get token
	$sql = 'SELECT user_id FROM ajax_final_web.token WHERE token = ?;';
	$insert_account = $pdo->prepare($sql);
	$insert_account->bindParam(1, $token);
	is_excute_success($insert_account->execute());
	$result = is_no_result($insert_account->fetchAll());
	$publisher_id = $result[0]['user_id'];

	//insert issue info
	$sql = 'INSERT INTO `ajax_final_web`.`ticket_issue` (
		`publisher_id`,`title`,`content`,`state`,`priority`
		) VALUES (?, ?, ?, ?, ?);';
	$insert_account = $pdo->prepare($sql);
	$insert_account->bindParam(1, $publisher_id);
	$insert_account->bindParam(2, $title);
	$insert_account->bindParam(3, $content);
	$insert_account->bindParam(4, $state);
	$insert_account->bindParam(5, $priority);

	is_excute_success($insert_account->execute());
	$issue_id = $pdo->lastInsertId();

	//insert issue schedule
	$sql = 'INSERT INTO `ajax_final_web`.`schedule_issue`(
		`ticket_id`,`occurency_date`,`expectation_date`
		) VALUES ( ? ,CURRENT_TIMESTAMP, FROM_UNIXTIME(?) );';
	$insert_account = $pdo->prepare($sql);
	$insert_account->bindParam(1, $issue_id);
	$insert_account->bindParam(2, $expectation);

	$result = is_excute_success($insert_account->execute());
	//show api
	echo json_encode(array('state'=>$result));

	//function--------------------------------------------------------------------------------
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
	function connect_database(){
		$host_url = "mysql:host=localhost;port=6033;dbname=ajax_final_web";
		$pdo = new PDO($host_url, "ajax_final","ajax_final" );
		$pdo->query('SET NAMES "UTF8"');
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		return $pdo;
	}
	function is_excute_success($execute_result){
		$execute_fail = !$execute_result;
		if($execute_fail)	
			exit(json_encode(array("state" =>false, "message"=>"Database execute fail.")));
		return $execute_result;
	}
	function is_no_result($result){
		if(count($result) == 0) 
			exit(json_encode(array("state" =>false, "message"=>"You don't have permission to access.")));
		return $result;
	}
	function to_number($name, $check){
		if(!is_numeric($check))
			exit(json_encode(array("state" =>false, "message"=>$name." is not valid number.")));
		return (int)$check;
	}