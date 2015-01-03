<?php
	header("Content-Type:application/json");
	
	if(isset($_POST['token']) == false)	
		exit(json_encode(array("state" =>false, "message"=>"Token is not post.")));
	
	$token = substr($_POST['token'],0,40);
	if(strlen($_POST['token']) != 40)	
		exit(json_encode(array("state" =>false, "message"=>"Token is not valid.")));

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
	$own_id = $result[0]['user_id'];
	$statement->closeCursor();

	//get issue
	$sql = 'SELECT' 
	.' ticket_issue.id,' 
	.' ticket_issue.title,' 
	.' states.name as state,' 
	.' priorities.name as priority,'
	.' schedule_issue.occurency_date,'
	.' schedule_issue.expectation_date,'
	.' schedule_issue.finished_date'

	.' FROM ajax_final_web.ticket_issue' 
	.' LEFT JOIN schedule_issue ON schedule_issue.ticket_id = ticket_issue.id' 
	.' LEFT JOIN states ON states.id = ticket_issue.state'
	.' LEFT JOIN priorities ON priorities.id = ticket_issue.priority'

	.' WHERE ticket_issue.publisher_id = ?
		ORDER BY schedule_issue.occurency_date ASC;';

	$statement = $pdo->prepare($sql);
	$statement->bindParam(1,$own_id);
	
	is_excute_success($statement->execute());
	$result = is_no_result($statement->fetchAll());
	$statement->closeCursor();
	
	$result['state'] = true;
	echo json_encode($result);

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