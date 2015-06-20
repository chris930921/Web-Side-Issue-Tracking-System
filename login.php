<?php
	header("Content-Type:application/json");
	include "connect.php";

	$check = true;
	$check &= isset($_POST['email']);
	$check &= isset($_POST['password']);

	$result = array();
	if($check){
		$account = substr($_POST['email'],0,30);
		$password = substr($_POST['password'],0,20);

		$salt_result = query("SELECT TOP 1 salt FROM login WHERE email LIKE '$account' ");
		if(count($salt_result)==1){
			$salt = $salt_result[0]['salt'];
			$password_sha1 = sha1($password."".$salt,false);
			//比對是否正確
			$result = query("SELECT TOP 1 id FROM login WHERE email LIKE '$account' AND password LIKE '$password_sha1' ");
		}
	}

	$check &= (count($result) == 1);
	if($check){
		$token = sha1($password."".rand(0,1000)."".time());
		$user_id = $result[0]['id'];
		excute("INSERT INTO token(token,user_id) VALUES ('$token','$user_id') ");
		$result = array("token" => $token);
	}else{
		$result = array("error"=>"Account or Password not correct.");
	}

	echo json_encode($result);