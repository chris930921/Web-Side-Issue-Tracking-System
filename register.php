<?php
	header("Content-Type:application/json");
	
	$check = true;
	$check &= isset($_POST['email']);
	$check &= isset($_POST['password']);
	if(strlen($_POST['email']) == 0 & strlen($_POST['password']) == 0){
		exit(json_encode(array("state" =>false, "message"=>"Email or Password is empty.")));
	}

	$result = false;
	if($check){

		//限制長度
		$account = substr($_POST['email'],0,30);
		$password = substr($_POST['password'],0,20);
		//資料庫
		$host_url = "mysql:host=localhost;port=6033;dbname=ajax_final_web";
		$pdo = new PDO($host_url, "ajax_final","ajax_final" );
		$pdo->query('SET NAMES "UTF8"');
		$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
		//掩蔽密碼
		$salt = str_pad(rand(0,9999999999999999), 16, "0", STR_PAD_LEFT);
		$password_sha1 = sha1($password."".$salt, false);
		//插入資料
		$insert_account = $pdo->prepare('INSERT INTO `ajax_final_web`.`login`(`email`,`password`,`salt`) VALUES (?,?,?)');
		$insert_account->bindParam(1,$account);
		$insert_account->bindParam(2,$password_sha1);
		$insert_account->bindParam(3,$salt);
		$result = $insert_account->execute();
	}
	$check &= $result;
	if($check){
		$result = array("state" =>true, "message"=>"Register success.");
	}else{
		$result = array("state" =>false, "message"=>"This email is uesed.");
	}

	echo json_encode($result);