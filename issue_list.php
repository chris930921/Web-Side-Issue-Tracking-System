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

	$select_sql = 'SELECT' 
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

	.' WHERE ? IN (SELECT token FROM ajax_final_web.token);';

	$insert_account = $pdo->prepare($select_sql);
	$insert_account->bindParam(1,$token);
	
	$search_fail = !$insert_account->execute();
	if($search_fail)	
		exit(json_encode(array("state" =>false, "message"=>"Search fail.")));

	$result = $insert_account->fetchAll();
	if(count($result) == 0 ) 
		exit(json_encode(array("state" =>false, "message"=>"You don't have permission to access.")));

	$result['state'] = true;
	echo json_encode($result);