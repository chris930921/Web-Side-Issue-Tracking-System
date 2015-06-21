<?php

function is_excute_success($result){
	global $call_count;
	$call_count++;
	if(!$result && !is_array($result)) 
		exit(json_encode(array("state" =>false, "message"=>"Database execute fail at $call_count time.")));

}

function is_no_permission($result){
	if(count($result) == 0 ) exit(json_encode(array("state" =>false, "message"=>"You don't have permission to access.")));
}

function at_least_one_row($result, $field){
	if(count($result) == 0 ) exit(json_encode(array("state" =>false, "message"=> "No ".$field." resource.")));
}

function already_exist($result, $field){
	if(count($result) > 0 ) exit(json_encode(array("state" =>false, "message"=> $field." is already exist.")));
}