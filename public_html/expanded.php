<?php
	require('common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}

	$query = "SELECT day_ac, week_ac, month_ac, use_cred, credit FROM money WHERE id = :id";
	$query_params = array(':id' => $_SESSION['user']['id']);
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($query_params);
	} catch(PDOException $ex) {
		die("500 Server Error." . $ex);
	}
	
	$row = $stmt->fetch();
	
	$day = $row['day_ac'];
	$week = $row['week_ac'];
	$month = $row['month_ac'];
	
	$act_q = "SELECT id, account_name, amount, link_bud FROM accounts WHERE uid = " . $_SESSION['user']['id'];
	$act_e = $dbc->query($act_q);
?>
<html>
	<head>
		<title>Info</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
	</head>
	<body>
		<div class="w3-card-4" style = "max-width: 800px; margin: auto; margin-top: 10px;">
			<div class='w3-container w3-blue' style="text-align: center;">
				<h1>Information for UID: <?php echo $_SESSION['user']['id']; ?></h1>
			</div>
			<div class="w3-container" style = "text-align: left;">
			<br /><br />
				<div class='content'>
					<h1>Day</h1>
					<h3>$<?php echo $day ?> &nbsp&nbsp<button onclick='javascript:window.location = ("php/add_to.php?type=day")' class="w3-button">+ / -</button></h3>
					<br /><br />
					<h1>Week</h1>
					<h3>$<?php echo $week ?> &nbsp&nbsp<button onclick='javascript:window.location = ("php/add_to.php?type=week")' class="w3-button">+ / -</button></h3>
					<br /><br />
					<h1>Month</h1>
					<h3>$<?php echo $month ?> &nbsp&nbsp<button onclick='javascript:window.location = ("php/add_to.php?type=month")' class="w3-button">+ / -</button></h3>
					<br /><br />
					<?php 
						
						
						foreach ($act_e as $accounts) {
							if ($accounts['link_bud'] == 1) {
								echo "<h1>".$accounts['account_name']."</h1>";
								echo "<h3>$".round($accounts['amount'], 2)."&nbsp&nbsp<button onclick='javascript:window.location = (\"php/account_add-to.php?type=".$account['id']."\")' class=\"w3-button\">+ / -</button></h3>";
							} else {
							
							}
							
						}
					?>
				</div>
				<p>Note: The add/subtract button does not add to the permanent monthly/weekly/daily amount, just this month/day/week.</p>
			</div>
		</div>
	</body>
</html>
