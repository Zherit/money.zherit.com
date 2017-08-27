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
		if ($_POST['money']) {
			$query = "UPDATE money SET money = :money WHERE id = :id";
			$query_params = array(':money' => $_POST['money'], ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 1");
			}
		}
		
		if($_POST['credit']) {
			$query = "UPDATE money SET credit = :credit WHERE id = :id";
			$query_params = array(':credit' => $_POST['credit'], ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 2");
			}
		}
		if($_POST['link']) {
			$query = "UPDATE money SET link = :link WHERE id = :id";
			$query_params = array(':link' => '1', ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 3" . $ex);
			}
		} else {
			$query = "UPDATE money SET link = :link WHERE id = :id";
			$query_params = array(':link' => '0', ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 4" . $ex);
			}
		}
		if($_POST['use_cred']) {
			$query = "UPDATE money SET use_cred = :use_cred WHERE id = :id";
			$query_params = array(':use_cred' => '1', ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 5");
			}
		} else {
			$query = "UPDATE money SET use_cred = :use_cred WHERE id = :id";
			$query_params = array(':use_cred' => '0', ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 6");
			}
		}
		
		if($_POST['tax']) {
			$tax = (($_POST['tax'] / 100) + 1);
		
		
			$query = "UPDATE money SET tax = :tax WHERE id = :id";
			$query_params = array(':tax' => $tax, ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 7");
			}
		}
		
		if(!$_POST['recurs']) {
			$query = "UPDATE money SET recurs = :recurs WHERE id = :id";
			$query_params = array(':recurs' => '0', ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 8");
			}
		} else {
			$query = "UPDATE money SET recurs = :recurs WHERE id = :id";
			$query_params = array(':recurs' => '1', ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 9");
			}
		}
		
		if($_POST['month_def']) {
			$query = "UPDATE money SET month_def = :month_def, month_ac = :month_def WHERE id = :id";
			$query_params = array(':month_def' => $_POST['month_def'], ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 10");
			}
		}
		
		if($_POST['week_def']) {
			$query = "UPDATE money SET week_def = :week_def, week_ac = :week_def WHERE id = :id";
			$query_params = array(':week_def' => $_POST['week_def'], ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 11");
			}
		}
		
		if($_POST['day_def']) {
			$query = "UPDATE money SET day_def = :day_def, day_ac = :day_def WHERE id = :id";
			$query_params = array(':day_def' => $_POST['day_def'], ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 12");
			}
		}
		if($_POST['warning_week']) {
			$query = "UPDATE money SET warning_week = :warning_week WHERE id = :id";
			$query_params = array(':warning_week' => $_POST['warning_week'], ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 13");
			}
		}
		if($_POST['warning_month']) {
			$query = "UPDATE money SET warning_month = :warning_month WHERE id = :id";
			$query_params = array(':warning_month' => $_POST['warning_month'], ':id' => $user_id);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 14");
			}
		}
		
		header('Location: user_list.php');
	} else {
		header('Location: user_list.php');
	}
			