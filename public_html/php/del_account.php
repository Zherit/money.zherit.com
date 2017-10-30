<?php 
	require('../common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	$uid = $_SESSION['user']['id'];
	
	if(!empty($_GET)) {
			$query = "DELETE FROM accounts WHERE id = :id AND uid = :uid";
			$query_params = array( 
							":uid" => $uid,
							":id" => $_GET['id']
							);
			
			try {
				$stmt = $dbc->prepare($query);
				$execute = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error.");
			}
			
			header("Location: ../settings.php");
	}