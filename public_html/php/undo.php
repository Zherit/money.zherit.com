<?php 
	require('../common.php');
	
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	$uid = $_SESSION['user']['id'];

	$row = unserialize($_POST['therow']);
	$row_serial = $_POST['therow'];
	
	$uid = $_SESSION['user']['id'];
	$tid = $row['id'];
	$tdate = $row['date'];
	$cdate = date('d-m-y');
	
	$money = $row['amount'];
	
	$log_purge = "DELETE FROM log WHERE id = :tid AND uid = :uid;";
	$log_purge_arr = array(':tid' => $tid, ':uid' => $uid);
	
	$day_restore = "UPDATE money SET day_ac = (day_ac + :ammount) WHERE id = :uid;";
	$week_restore = "UPDATE money SET week_ac = (week_ac + :ammount) WHERE id = :uid;";
	$month_restore = "UPDATE money SET month_ac = (month_ac + :ammount) WHERE id = :uid;";
	
	function query($x, $y) {
		require('../common.php');
		$query = $x;
		$query_params = $y;
		
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error." . $ex);
		}
	}
	
	
	query($log_purge, $log_purge_arr);
	
	if ($row['cred_deb'] == 'credit') {
		$cq = "UPDATE money SET credit = (credit + :money);";
		$cqp = array(':money' => $money);
		query($cq, $cqp);
	} 
	
	if ($tdate == $cdate) {
		$reset_d = array(':ammount' => $_POST['trans_mon'], ':uid' => $uid);
		$reset_w = array(':ammount' => $_POST['trans_mon'], ':uid' => $uid);
		$reset_m = array(':ammount' => $_POST['trans_mon'], ':uid' => $uid);
		
		query($day_restore, $reset_d);
		query($week_restore, $reset_w);
		query($month_restore, $reset_m);
		
		
		header('Location: ../history.php');
		
	} elseif ($_POST['resetting']) { 
		if ($_POST['reset_day'] == 'yes') {
			$restore_all = "UPDATE money SET day_ac = (day_ac + :ammount), week_ac = (week_ac + :ammount), month_ac = (month_ac + :ammount) WHERE id = :uid";		
			$reset = array(':ammount' => $row['amount'], ':uid' => $uid);
			query($restore_all, $reset);
			
		}
		if ($_POST['reset_week'] == 'yes') {
			$restore_all = "UPDATE money SET day_ac = (day_ac + :ammount), week_ac = (week_ac + :ammount), month_ac = (month_ac + :ammount) WHERE id = :uid";		
			$reset = array(':ammount' => $row['amount'], ':uid' => $uid);
			query($restore_all, $reset);
			
		}
		if ($_POST['reset_month'] == 'yes') {
			$restore_all = "UPDATE money SET day_ac = (day_ac + :ammount), week_ac = (week_ac + :ammount), month_ac = (month_ac + :ammount) WHERE id = :uid";		
			$reset = array(':ammount' => $row['amount'], ':uid' => $uid);
			query($restore_all, $reset);
			
		}
		
		header('Location: ../history.php');
	} else {
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
					<form method="post" action="undo.php">
						<h1>Undo Expendature</h1>
						<p>Because this expendature was not made today you will need to select where you would like the money to be added to when undone</p>
						<input type='hidden' name='resetting' value='yes'>
						<input type='hidden' name='therow' value='<?php echo $row_serial ?>'>
						
						
						<label for="switch">Today</label>
						<input type="checkbox" name="reset_day" value='yes' id="switch">
						<br>
						<label for="switch">This Week</label>
						<input type="checkbox"  name="reset_week" value='yes' id="switch">
						<br>
						<label for="switch">This Month</label>
						<input type="checkbox" name="reset_month" value='yes' id="switch">
						<br>
						<input type="submit" value="Submit">
					</form>
				</div>
			</div>
			
			

	
<?php	} ?>
	