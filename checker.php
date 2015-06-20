<?php

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