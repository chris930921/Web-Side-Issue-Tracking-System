<?php
	header("Content-Type:application/json");
	
	$result = array();
	$host_url = "mysql:host=localhost;port=6033;dbname=ajax_final_web";
	$pdo = new PDO($host_url, "ajax_final","ajax_final" );
	$pdo->query('SET NAMES "UTF8"');
	$pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
	$select_salt = $pdo->prepare("SELECT id,email,salt FROM login");
	$select_salt->execute();
	$result = $select_salt->fetchAll();

	
	echo json_encode($result);