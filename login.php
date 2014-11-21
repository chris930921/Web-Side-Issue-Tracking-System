<?php
	header("Content-Type:application/json");
	
	$check = true;
	$check &= isset($_POST['Email']);
	$check &= isset($_POST['Password']);

	$result = array();
	if($check){
		$account = substr($_POST['Email'],0,30);
		$password = substr($_POST['Password'],0,20);
		$host_url = "mysql:host=localhost;port=6033;dbname=ajax_final_web";
		$pdo = new PDO($host_url, "ajax_final","ajax_final" );
		$pdo->query('SET NAMES "UTF8"');
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		$select_salt = $pdo->prepare('SELECT salt FROM login WHERE email=? AND password=? LIMIT 1');
		$select_salt->bindParam(1,$account);
		$select_salt->bindParam(2,$password);
		//TODO $select_salt->bindValue(":Id",'0');
		$select_salt->execute();
		$result = $select_salt->fetchAll();
	}

	$check &= (count($result) == 1);
	if($check){
		$result = array($result[0][0]);
	}else{
		$result = array("error"=>"Account or Password not correct.");
	}

	echo json_encode($result);