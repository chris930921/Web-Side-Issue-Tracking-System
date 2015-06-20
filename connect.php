<?php


function query($query){
	$link = connect();
	$cursor = mssql_query($query, $link);
	
	if($cursor){
		$num=mssql_num_rows($cursor);
		$result = array();
		while($row = mssql_fetch_assoc($cursor)){
			array_push($result,$row);
		}
	}else{
		$result = false;
	}
	mssql_close($link);
	return $result;
}

function excute($query){
	$link = connect();
	$cursor = mssql_query($query, $link);
	
	if($cursor){
		$result = true;
	}else{
		$result = false;
	}
	mssql_close($link);
	return $result;
}

function connect(){
	$link = mssql_connect('127.0.0.1\SQLEXPRESS', 'user','user');
	$db_selected = mssql_select_db('databaseFinal', $link);
	return $link;
}