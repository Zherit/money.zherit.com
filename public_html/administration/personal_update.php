<?php
	require('../common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	$user_id = $_POST['user_id'];
	
	
	
	
	if (!empty($_POST)) {
		if ($_POST['username']) {
			$query = "SELECT 1 FROM users WHERE username = :username";
			$query_params = array(':username' => $_POST['username']);
		
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error.");
			}
		
			$row = $stmt->fetch();
			if ($row) {
				die("This username exists in the database already");
			} else {
				$query = "UPDATE users SET username = :username WHERE id = :id";
				$query_params = array(':username' => $_POST['username'], ':id' => $user_id);
				
				try {
					$stmt = $dbc->prepare($query);
					$result = $stmt->execute($query_params);
				} catch(PDOException $ex) {
					die("500 Server Error.");
				}
				
				$query = "UPDATE money SET username = :username WHERE id = :id";
				$query_params = array(':username' => $_POST['username'], ':id' => $user_id);
				
				try {
					$stmt = $dbc->prepare($query);
					$result = $stmt->execute($query_params);
				} catch(PDOException $ex) {
					die("500 Server Error.");
				}
			}
		}
		
		if($_POST['email']) {
			$query = "SELECT 1 FROM users WHERE email = :email";
			$query_params = array(':email' => $_POST['email']);
		
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error.");
			}
		
			$row = $stmt->fetch();
			if ($row) {
				die("This email exists in the database already");
			} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
				die("Invalid: Email");
			} else {
				$query = "UPDATE users SET email = :email WHERE id = :id; UPDATE money SET email = :email WHERE id = :id";
				$query_params = array(':email' => $_POST['email'], ':id' => $user_id);
				
				try {
					$stmt = $dbc->prepare($query);
					$result = $stmt->execute($query_params);
				} catch(PDOException $ex) {
					die("500 Server Error.");
				}
			}
		}
		
		if($_POST['password']) {
			$salt = dechex(mt_rand(0,2147483647)) . dechex(mt_rand(0, 2147483647));
			$password = hash('sha256', $_POST['password'] . $salt);
			for($round = 0; $round < 65536; $round++) {
				$password = hash('sha256', $password . $salt);
			}
		
			$query = "UPDATE users SET password = :password, salt = :salt, temp_pass = 1 WHERE id = :id; ";
			$query_params = array(':password' => $password, ':salt' => $salt, ':id' => $user_id);
				
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error.");
			}
		
		}
		
		header('Location: user_list.php');
	} else {
		header('Location: user_list.php');
	}
		