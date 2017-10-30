<?php 
	require('../common.php');
	
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	$uid = $_SESSION['user']['id'];
	$form = $_POST['form'];

	$row = unserialize($_POST['therow']);
	$row_serial = $_POST['therow'];
	
	$type = null;
	$budget = null;

	$tid = $row['id'];
	$tdate = $row['date'];
	$cdate = date('d-m-y');	
	if ($tdate == $cdate) { $diff_day = false; } else { $diff_day = true; }
	
	/* Checking the kind  */
	if($row['cred_deb'] == 'Main') {
		$type = 'main';
	} else if ($row['cred_deb'] == 'debit') {
		$type = 'main'; 
	} else  {
		$type = 'other';
	}
	
	/* CLEAR THE LOGS */ 
	$log_purge = "DELETE FROM log WHERE id = :tid AND uid = :uid;";
	$log_purge_arr = array(':tid' => $tid, ':uid' => $uid);
	
	try {
		$stmt = $dbc->prepare($log_purge);
		$result = $stmt->execute($log_purge_arr);
	} catch(PDOException $ex) {
		die("500 Server Error.");
	}
	
	/* Check if budgeting is in use */
	$bud = "SELECT recurs FROM money WHERE id = ".$uid;
	$bud_e = $dbc->query($bud);
	
	$bud_r = $bud_e->fetch();
	
	if ($bud_r['recurs'] == '1') {
		if ($type == 'main' || $type == 'debit') {
			$budget = 1;
		} else if ($type == 'other') {
			$link = "SELECT link_bud FROM accounts WHERE id = :id AND uid = :uid";
			$link_params = array(":id" => $row['aid'], ":uid" => $uid);
			try {
				$stmt = $dbc->prepare($link);
				$stmt->execute($link_params);
			} catch (Exception $e) {
				die("Account No Longer Exists".$e);
			}
			
			$link_r = $stmt->fetch(PDO::FETCH_ASSOC);
			if ($link_r['link_bud'] == 1) { $budget = 1; } else { $budget = 0; }
			
			
			
		} else {
			die("Error.");
		}
		
	} else {
		$budget = 0;
	}
	
	if ($form == "noForm") {
		echo "NO FORM";
		echo $form;
		if ($type == "main") {
			if ($budget == 0) {
				$query = "UPDATE money SET money = (money + :spent) WHERE id = :uid";
				$query_params = array(":spent" => $row['amount'], ":uid" => $uid);
				try {
					$stmt = $dbc->prepare($query);
					$result = $stmt->execute($query_params);
				} catch (exception $e) {
					die("500 Server Error. 1");
				}
				header("Location: ../history.php");
			} else if ($budget == 1){
				if ($diff_day == false) {
					$query = "UPDATE money SET day_ac = (day_ac + :spent), week_ac = (week_ac + :spent), month_ac = (month_ac + :spent) WHERE id = :uid";
					$query_params = array(":spent" => $row['amount'], ":uid" => $uid);
					try {
						$stmt = $dbc->prepare($query);
						$result = $stmt->execute($query_params);
					} catch (exception $e) {
						die("500 Server Error. 2");
					}
					header("Location: ../history.php");
				} else if ($diff_day == true) {
					
				} 
			} else {
				die("Error.");
			}
		} else if ($type == "other") {
			if ($budget == 0) {
				$query = "UPDATE accounts SET amount = (amount + :spent) WHERE id = :id AND uid = :uid";
				$query_params = array(":spent" => $row['amount'], ":id" => $row["aid"], ":uid" => $uid);
				try {
					$stmt = $dbc->prepare($query);
					$result = $stmt->execute($query_params);
				} catch (exception $e) {
					die("500 Server Error. 3");
				}
				header("Location: ../history.php");
				
			} else if ($diff_day == false) {
				$query = "UPDATE money SET day_ac = (day_ac + :spent), week_ac = (week_ac + :spent), month_ac = (month_ac + :spent) WHERE id = :uid";
				$query_params = array(":spent" => $row['amount'], ":uid" => $uid);
				try {
					$stmt = $dbc->prepare($query);
					$result = $stmt->execute($query_params);
				} catch (exception $e) {
					die("500 Server Error. 4 :: " . $e);
				}
				$query = "UPDATE accounts SET amount = (amount + :spent) WHERE id = :id AND uid = :uid";
				$query_params = array(":spent" => $row['amount'], ":id" => $row["aid"], ":uid" => $uid);
				try {
					$stmt = $dbc->prepare($query);
					$result = $stmt->execute($query_params);
				} catch (exception $e) {
					die("500 Server Error. 5");
				}
				header("Location: ../history.php");
				
			} else if ($diff_day == true) {
				$query = "UPDATE accounts SET amount = (amount + :spent) WHERE id = :id AND uid = :uid";
				$query_params = array(":spent" => $row['amount'], ":id" => $row["aid"], ":uid" => $uid);
				try {
					$stmt = $dbc->prepare($query);
					$result = $stmt->execute($query_params);
				} catch (exception $e) {
					die("500 Server Error. 5");
				}
			} else {
				die("Error.");
			}
		}
	} else {
		echo $form;
		$row = unserialize($_POST['therow']);
		if ($_POST['reset'] == "day") {
			$query = "UPDATE money SET day_ac = (day_ac + :spent) WHERE id = :uid";
			$query_params = array(":spent" => $row['amount'], ":uid" => $uid);
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch (exception $e) {
				die("500 Server Error. 4 :: " . $e);
			}
			header("Location: ../history.php");
		} else if ($_POST['reset'] == "week") {
			$query = "UPDATE money SET day_ac = (day_ac + :spent), week_ac = (week_ac + :spent) WHERE id = :uid";
			$query_params = array(":spent" => $row['amount'], ":uid" => $uid);
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch (exception $e) {
				die("500 Server Error. 4 :: " . $e);
			}
			header("Location: ../history.php");
		} else if ($_POST['reset'] == "month") {
			$query = "UPDATE money SET day_ac = (day_ac + :spent), week_ac = (week_ac + :spent), month_ac = (month_ac + :spent) WHERE id = :uid";
			$query_params = array(":spent" => $row['amount'], ":uid" => $uid);
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch (exception $e) {
				die("500 Server Error. 4 :: " . $e);
			}
			header("Location: ../history.php");
		} else {
			echo "Invalid Entry";
		}
		
		
		
		
		
		/* IF THE POST WAS NOT EMPTY */
		echo "WE REACHED THIS POINT";
	}

	
?>

<html>
	<head> 
		<title>Undo Spending</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://code.jquery.com/mobile/1.4.5/jquery.mobile-1.4.5.min.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
	</head>
	<body>
		<div id="content">
			<div class='content'>
				<div>
					<form method="post" action="undo_new.php">
						<h1>Undo Expendature</h1>
						<p>Because this expendature was not made today you will need to select where you would like the money to be added to when undone</p>
						<input type='hidden' name='resetting' value='yes'>
						<input type='hidden' name='therow' value='<?php echo $row_serial ?>'>
						<input type='hidden' name='form' value = 'yes'>
						
						
						
						<input class="w3-radio" type="radio" name="reset" value='day'>
						<label>Only Today</label>
						<br>
						<input class="w3-radio" type="radio"  name="reset" value='week'>
						<label>This Week and Today</label>
						<br>

						<input class="w3-radio" type="radio" name="reset" value='month'>
						<label>This Week, This Month, and Today</label>
						<br>
						<br>
						<br>
						<input type="submit" class="w3-button w3-gray" value="Submit">
					</form>
				</div>
			</div>
		</div>
	</body>
</html>