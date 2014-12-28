<?php
	header("Content-Type:application/json");
	
	$check = true;
	$check &= isset($_POST['email']);
	$check &= isset($_POST['password']);

	$result = array();
	if($check){
		$account = substr($_POST['email'],0,30);
		$password = substr($_POST['password'],0,20);
		$host_url = "mysql:host=localhost;port=6033;dbname=ajax_final_web";
		$pdo = new PDO($host_url, "ajax_final","ajax_final" );
		$pdo->query('SET NAMES "UTF8"');
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		//取出salt
		$select_salt = $pdo->prepare('SELECT salt FROM login WHERE email=? LIMIT 1');
		$select_salt->bindParam(1,$account);
		//TODO $select_salt->bindValue(":Id",'0');
		$salt_check = $select_salt->execute();
		$salt_result = $select_salt->fetchAll();
		if(count($salt_result)==1){
			$salt = $salt_result[0]['salt'];
			$password_sha1 = sha1($password."".$salt,false);
			//比對是否正確
			$select_salt = $pdo->prepare('SELECT id FROM login WHERE email=? AND password=? LIMIT 1');
			$select_salt->bindParam(1,$account);
			$select_salt->bindParam(2,$password_sha1);
			//TODO $select_salt->bindValue(":Id",'0');
			$select_salt->execute();
			$result = $select_salt->fetchAll();
		}
	}

	$check &= (count($result) == 1);
	if($check){
		$token = sha1($password."".rand(0,1000)."".time());
		$token_result = $pdo->prepare('INSERT INTO `ajax_final_web`.`token`(`token`,`user_id`) VALUES (?,?)');
		$token_result->bindParam(1,$token);
		$token_result->bindParam(2,$result[0]['id']);
		//TODO $select_salt->bindValue(":Id",'0');
		$token_result->execute();
		$result = array("token" => $token);
	}else{
		$result = array("error"=>"Account or Password not correct.");
	}

	echo json_encode($result);