<?php


function query($query){
	$link = connect();
	@$result = mssql_query($query, $link);
	
	if($result){
		@$num=mssql_num_rows($result);  
		$stand= array();
		if($num === 1){
				$result_str =  json_encode(mssql_fetch_assoc($result));
		}else if($num > 1){
			while($row = mssql_fetch_assoc($result)){
				array_push($stand,$row);
			}
			$result_str = jsonencode($stand);
		}else{
			$result_str = json_encode($stand);
		}
	}else{
		$result_str = false;
	}
	mssql_close($link);
	return $result_str;
}

function excute($query){
	$link = connect();
	@$result = mssql_query($query, $link);
	
	if($result){
		$result_str = true;
	}else{
		$result_str = false;
	}
	mssql_close($link);
	return $result_str;
}

function connect(){
	$link = mssql_connect('127.0.0.1\SQLEXPRESS', 'user','user');
	$db_selected = mssql_select_db('databaseFinal', $link);
	return $link;
}