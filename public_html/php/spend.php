<?php 
	require('../common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	
	
	$reason = 'None';
	
	$query = "SELECT * FROM money WHERE id = :id";
	$query_params = array(':id' => $_SESSION['user']['id']);
	
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($query_params);
	} catch(PDOException $ex) {
		die("500 Server Error. 1");
	}
	
	$row = $stmt->fetch();
	
	$type = null;
	if($_POST['log']) {
			$reason = $_POST['log'];
		} else {
			$reason = 'None';
		}
		
	if(!empty($_POST['debit'])) {
		$type = 'debit';
	} elseif(!empty($_POST['credit'])) {
		$type = 'credit';
	} elseif(!empty($_POST['spend'])) {
		if ($row['recurs'] == 1) { 
			if($_POST['type'] == 'debit') {
				$query = 'UPDATE money SET day_ac = (day_ac - :money), week_ac = (week_ac - :money), month_ac = (month_ac - :money) WHERE id = :id';
			} elseif($_POST['type'] == 'credit') {
				if (1==1) {
					$query = 'UPDATE money SET day_ac = (day_ac - :money), week_ac = (week_ac - :money), month_ac = (month_ac - :money), credit = (credit - :money)  WHERE id = :id';
				} else {
					$query = "UPDATE money SET credit = (credit - :money) WHERE id = :id";
				}
			} else {
				die('Input Error');
			}
		} elseif ($row['recurs'] == 0) {
			if($_POST['type'] == 'debit') {
				$query = 'UPDATE money SET money = (money - :money)  WHERE id = :id';
			} elseif($_POST['type'] == 'credit') {
				if (1==1) {
					$query = 'UPDATE money SET money = (money - :money), credit = (credit - :money)  WHERE id = :id';
				} else {
					$query = "UPDATE money SET credit = (credit - :money) WHERE id = :id";
				}
			} else {
				die('Input Error');
			}
		} else {
			die('INVALID recurs VALUE NOTIFY DEVELOPER');
		}
	
	
		if($_POST['tax'] == "yes") {
			$query2 = "SELECT tax FROM money WHERE id = :id";
			$query_params2 = array(':id' => $_SESSION['user']['id']);
			
			try {
				$stmt2 = $dbc->prepare($query2);
				$result2 = $stmt2->execute($query_params2);
			} catch(PDOException $ex) {
				die("500 Server Error. 2");
			}
			$row2 = $stmt2->fetch();
			
			$spendtx = ($row2['tax'] * $_POST['spend']);
			$tax = $row2['tax'];
			
		} else {
			$spendtx = $_POST['spend'];
			$tax = 'no';
		}
		
		
			
		$query_params = array(':money' => $spendtx, ':id' => $_SESSION['user']['id']);
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error. 3 " . $spendtx . $query . $_SESSION['user']['id']);
		}
		if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
			$ip=$_SERVER['HTTP_CLIENT_IP'];
		} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
			$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
		} else {
			$ip=$_SERVER['REMOTE_ADDR'];
		}
		$query_2 = "INSERT INTO log (username, uid, amount, cred_deb, tax, reason, date, ip) VALUES (:username, :uid, :amount, :cred_deb, :tax, :reason, :date, :ip)";
		$query_params_2 = array(
								':username' => $_SESSION['user']['username'],
								':uid' => $_SESSION['user']['id'],
								':amount' => $spendtx,
								':cred_deb' => $_POST['type'],
								':tax' => $tax,
								':reason' => $reason,
								':ip' => $ip,
								':date' => date('d-m-y')
							);
		try {
			$stmt3 = $dbc->prepare($query_2);
			$result3 = $stmt3->execute($query_params_2);
		} catch(PDOException $ex) {
			die("500 Server Error. 4" . $ex . $query_2 . $spendtx);
		}
			
			header('Location: ../index.php');
	}
	
?>

<html>
	<head>
		<title>Money</title>
		
		<link rel="stylesheet" type="text/css" href="../css/keypad.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">

		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
	</head>
	<body>
		<form class = "w3-container w3-light-grey w3-center w3-card-4" style="height:100%" action="spend.php" method="post">
			<label class="w3-center w3-cell-middle">Ammount</label>
			<br /><br />
			<input type="number" step="0.01" name="spend" class="w3-input w3-border-0 w3-center w3-half" />
			<br /><br />
			<input type="text" name="log" class="w3-input w3-border-0 w3-center w3-half" placeholder="Purpose">
			<br /><br />
			<input class="w3-radio" type="radio" name="tax" value="yes"><label>Tax</label>
			<input class="w3-radio" type="radio" name="tax" value="no"><label>No Tax</label>
			<br /><br />
			<input type="submit" />
			
			<input type='hidden' name='type' value='<?php echo $type; ?>' />
			
		</form>
	
	</body>
</html>
