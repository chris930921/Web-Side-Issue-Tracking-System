<?php
	header("Content-Type:application/json");
	include "connect.php";

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
		//掩蔽密碼
		$salt = str_pad(rand(0,9999999999999999), 16, "0", STR_PAD_LEFT);
		$password_sha1 = sha1($password."".$salt, false);

		$query = "INSERT INTO login(email,password,salt) VALUES ('$account','$password_sha1','$salt')";
		@$result = excute($query);
	}		
	
	$check &= $result;
	if($check){
		$result = array("state" =>true, "message"=>"Register success.");
	}else{
		$result = array("state" =>false, "message"=>"This email is uesed.");
	}
	echo json_encode($result);