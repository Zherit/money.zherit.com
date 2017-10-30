<?php 
	require('../common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	$uid = $_SESSION['user']['id'];
	$id = $_GET['type'];
	
	$query = "UPDATE accounts SET link_bud = 1 WHERE id = :id AND uid = :uid";
	$params = array(":id" => $id, ":uid" => $uid);
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($params);
	} catch(PDOException $ex) {
		die("500 Server Error." . $ex);
	}
	
	header("Location: ../accounts.php");