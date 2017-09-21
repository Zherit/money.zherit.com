<?php 
	require('../common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
		$uid = $_SESSION['user']['id'];
	}
	
	if(!empty($_POST)) {
		
		$query = "SELECT recurs FROM money WHERE id = :id";
		$query_params = array(':id' => $_SESSION['user']['id']);
	
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error. 1");
		}
		
		$row = $stmt->fetch();
		
		
		
		$query_m = null;
		$query_a = null;
		$queries = null;
		$option = $_POST['option'];
		
											/*Decide which queries to run */
											
		if($_POST['option'] == "qwil887jdns") {
			$from_acc = "Main";
			if ($row['recurs'] == 1) { 																															
				$query_m = 'UPDATE money SET day_ac = (day_ac - :money), week_ac = (week_ac - :money), month_ac = (month_ac - :money) WHERE id = :id';
				$queries = 1; 
				
			} elseif ($row['recurs'] == 0) {
				$query_m = 'UPDATE money SET money = (money - :money)  WHERE id = :id';
				$queries = 1;
				
			} else {
				die('INVALID recurs VALUE NOTIFY DEVELOPER');
			}
			
			
			
		} else {
			$check = "SELECT account_name, id, link_bud FROM accounts WHERE id = :id AND uid = :uid";															
			$check_p = array(":id" => $_POST['option'], ":uid" => $uid);

			try {
				$check_s = $dbc->prepare($check);
				$check_e = $check_s->execute($check_p);
				
			} catch(PDOException $ex) {
				die("ERROR: Invalid account.");
			}
			
			
			$account = $check_s->fetch();
			$from_acc = $account['account_name'];
			if ($row['recurs'] == 1) {
				if ($account['link_bud'] ==  1) {
					$query_a = "UPDATE accounts SET amount = (amount - :money) WHERE id = :id AND uid = :uid";
					$query_m = 'UPDATE money SET day_ac = (day_ac - :money), week_ac = (week_ac - :money), month_ac = (month_ac - :money) WHERE id = :id';
					$queries = 2;
					
					
				} else {
					$query_a = "UPDATE accounts SET amount = (amount - :money) WHERE id = :id AND uid = :uid";
					$queries = 1;
					
				}
				
			} else {
				$query_a = "UPDATE accounts SET amount = (amount - :money) WHERE id = :id AND uid = :uid";
				$queries = 1;
				
			}
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
		
		/* Declaring some useful variables 
		
		$spendtx = the money adjusted for tax
		$uid = the user id
		$_POST['option']
		
		
		 /* =============  */
		
									/* Run the update queries and log */
		
			if ($queries == 1) {
				if (empty($query_m)) {
					$query_params = array(':money' => $spendtx,':id' => $_POST['option'], ':uid' => $_SESSION['user']['id']);
					try {
						$stmt = $dbc->prepare($query_a);
						$result = $stmt->execute($query_params);
						
						
					} catch(PDOException $ex) {
						die("500 Server Error. 3 " . $spendtx . $query_a . $_SESSION['user']['id']);
					}	
					
				} else if (empty($query_a)) {
					$query_params = array(':money' => $spendtx, ':id' => $_SESSION['user']['id']);
					try {
						$stmt = $dbc->prepare($query_m);
						$result = $stmt->execute($query_params);
						
					} catch(PDOException $ex) {
						die("500 Server Error. 3 " . $spendtx . $query_m . $_SESSION['user']['id']);
					}
					
				} else {
					die("Well something went wrong ");
				}
				
			} elseif ($queries == 2) {
				if (!empty($query_a)) {
					$query_params = array(':money' => $spendtx,':id' => $_POST['option'], ':uid' => $_SESSION['user']['id']);
					try {
						$stmt = $dbc->prepare($query_a);
						$result = $stmt->execute($query_params);
						
					} catch(PDOException $ex) {
						die("500 Server Error. 3 " . $spendtx . $query_a . $_SESSION['user']['id']);
					}
					
				} else { die("ERROR Multi 1"); }
				if (!empty($query_m)) {
					$query_params = array(':money' => $spendtx, ':id' => $_SESSION['user']['id']);
					try {
						$stmt = $dbc->prepare($query_m);
						$result = $stmt->execute($query_params);
						
					} catch(PDOException $ex) {
						die("500 Server Error. 3 " . $spendtx . $query_m . $_SESSION['user']['id']);
					}
				} else { die("ERROR Multi 2"); }
			}
			
			
			
			if (!empty($_POST['option'])) {
				require("../common.php");
				
				$query = "SELECT id, sel FROM accounts WHERE uid = " . $_SESSION['user']['id'];
				$stmt = $dbc->query($query);
				
				foreach ($stmt as $row) {
					
					if ($row['id'] == $_POST['option']) {
						$set = "UPDATE accounts SET sel = 1 WHERE id = ".$row['id']." AND uid = ".$uid;
						$query_params = array(':id' => $row['id'], ':uid' => $_SESSION['user']['id']);
						
						try {
							$stmt = $dbc->prepare($set);
							$result = $stmt->execute($query_params);
						} catch(PDOException $ex) {
							die("500 Server Error. 2");
						}

					} else {
						$set = "UPDATE accounts SET sel = 0 WHERE id = ".$row['id']." AND uid = ".$uid;
						$query_params = array(':input' => 2);
						
						try {
							$stmt = $dbc->prepare($set);
							$result = $stmt->execute($query_params);
						} catch(PDOException $ex) {
							die("500 Server Error. 2");
						}
					}
				}
			}
			

			//logit
			
			if($_POST['log']) {
				$reason = $_POST['log'];
			} else {
				$reason = 'None';
			}
			
			$check = "SELECT account_name, id, link_bud FROM accounts WHERE id = :id AND uid = :uid";															
			$check_p = array(":id" => $_POST['option'], ":uid" => $uid);
			
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
								':cred_deb' => $from_acc,
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
	
	$act_q = "SELECT id, account_name, amount, sel FROM accounts WHERE uid = " . $_SESSION['user']['id'];
	$act_ex = $dbc->query($act_q);
	foreach ($act_ex as $account) {
		if ($account['sel'] == 1) {	
			$selected = null;
		} else { 
			$selected = "selected";
		}
	}
	
	$act_e = $dbc->query($act_q);
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
		<form class = "w3-container w3-light-grey w3-center w3-card-4" style="height:100%" action="spend_new.php" method="post">
			<label class="w3-center w3-cell-middle">Account</label>
			<br />
			<select class="w3-input w3-border-0 w3-center" style = "width: 50%; display: inline-block;" name="option">
				<option value="qwil887jdns" <?php echo $sel; ?>>Money</option>
	<?php		foreach ($act_e as $account) {
					if ($account['sel'] == 1) {
						echo "<option value='".$account['id']."' selected>".$account['account_name']."</option>";
					} else { 
						echo "<option value='".$account['id']."'>".$account['account_name']."</option>";
					}
				}
	?>		
				
			</select>
			<br /><br />
			<label class="w3-center w3-cell-middle">Amount</label>
			<br /><br />
			<input type="number" step="0.01" placeholder = "$$$" name="spend" class="w3-input w3-border-0 w3-center" style = "width: 50%; display: inline-block;" />
			<br /><br />
			<input type="text" name="log" class="w3-input w3-border-0 w3-center" style = "width: 50%; display: inline-block;" placeholder="Purpose">
			<br /><br />
			<input class="w3-radio" type="radio" name="tax" value="yes"><label>Tax</label>
			<input class="w3-radio" type="radio" name="tax" value="no"><label>No Tax</label>
			<br /><br />
			<input type="submit" />
			
			<input type='hidden' name='type' value='<?php echo $type; ?>' />
			
		</form>
	</body>
</html>