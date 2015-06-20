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