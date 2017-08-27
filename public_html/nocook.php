<?php

require('common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	$query = "UPDATE users SET cookie_accept = 1 WHERE id = :id";
	$query_params = array(':id' => $_SESSION['user']['id']);
	
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($query_params);
		
		
	} catch(PDOException $ex) {
		die("500 Server Error.");
	}
	
	header('Location: index.php');