<?php 
	require('common.php');
	
	

	if (!$_SESSION['user']) {
		header("Location: reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	$account = null;
	
	$query_c = "SELECT first_login, cookie_accept, temp_pass FROM users WHERE id = :id";
	$query_params_c = array(':id' => $_SESSION['user']['id']);
	
	try {
		$stmt_c = $dbc->prepare($query_c);
		$result_c = $stmt_c->execute($query_params_c);
	} catch(PDOException $ex) {
		die("500 Server Error.");
	}
	
	$row_c = $stmt_c->fetch();
	
	$query = "SELECT * FROM money WHERE id = :id";
	$query_params = array(':id' => $_SESSION['user']['id']);
	
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($query_params);
	} catch(PDOException $ex) {
		die("500 Server Error.");
	}
	
	$row = $stmt->fetch();
	
	if ($row_c['first_login'] == 0) {
		$account = "<div class='w3-panel w3-pale-red w3-leftbar w3-border-red w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-green w3-panel w3-large w3-display-topright'>&times;</span><h3>Welcome</h3> <p>You are using this website for the first time, please visit <a href='settings.php'>SETTINGS</a> to set up your account.</div>";
		$query = "UPDATE users SET first_login = 1 WHERE id = :id";
		$query_params = array(':id' => $_SESSION['user']['id']);
	
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error." . $ex);
		}
	} else {
		$account = null;
		if ($row['recurs'] == 1) {
			$money = $row['day_ac'];
			if ($row['day_ac'] <= 0) {
				$account = "<div class='w3-panel w3-amber w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-green w3-panel w3-large w3-display-topright'>&times;</span><h3>Warning!</h3> <p>You are out of money for the day. The number displayed is your remaining for the week.</div>";
				$money = $row['week_ac'];
			
				if ($row['week_ac'] <= 0) {
					$account = "<div class='w3-panel w3-amber w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-amber w3-panel w3-large w3-display-topright'>&times;</span><h3>Warning!</h3> <p>You are out of money for the week. The number displayed is your remaining for the month.</div>";
					$money = $row['month_ac'];
				
					if ($row['month_ac'] <= 0) {
						$account = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Alert!</h3> <p>You are out of money for the month.</div>";
					$money = $row['month_ac'];
					}
				
				}
  
			}
		
			if ($row['month_ac'] <= $row['warning_month']) {
				$warning = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Alert!</h3> <p>You are low on money for the month.</div>";
			}
			if ($row['week_ac'] <= $row['warning_week']) {
				$warning = "<div class='w3-panel w3-amber w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-amber w3-panel w3-large w3-display-topright'>&times;</span><h3>Warning!</h3> <p>You are low on money for the week.</div>";
			} 
		
		} else {
			$money = $row['money'];
			if ($money <= 0) {
				$account = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Alert!</h3> <p>You are out of money. Adjust settings at <a href='settings.php'>SETTINGS</a></div>";
			}
		}
	}
	
	if($row_c['cookie_accept'] == 0) {
		$warning = "
			<div class='w3-card-4 w3-dark-grey w3-animate-opacity'>

				<div class='w3-container w3-center'>
					<h3>Alert!</h3>
					<p>This website uses cookies to make logins easier for our users. If you do not like this you are free to disable cookies and use the site without the convenience.</p>

					<button class='w3-button w3-green' onclick='window.location.href=`nocook.php`;'>Never see this again</button>
					<button class='w3-button w3-red' onclick='this.parentElement.style.display=`none`'>See this every time</button>
				</div>

			</div>";
	}
	
	if($row_c['temp_pass'] == 1) {
		$password = "
			<div class='overlay'>
			<div class='w3-card-4 w3-dark-grey w3-animate-opacity center width555' style = 'margin: auto;'>
				<div class='w3-container w3-center'>
					<h3>Alert!</h3>
					<p>Your password was reset. Please enter a new password.</p>
					<p />
					<form action='php/personal_update.php' method='post'>
						<input name='password' type = 'password' class = 'w3-input' placeholder = 'Password'>
						<input name='temp_pass' type = 'hidden' value='1'>
						<p>
						<input type = 'submit' class = 'w3-submit'>
					</form>
					
				</div>
			</div>
			</div>
		
		
		
		";
	} else {
		$password = null;
	}
	
	
/*	if ($row['money'] == 0 && $row['credit'] == 0) {
		$account = "<div class='w3-panel w3-red w3-card-4'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-large w3-display-topright'>&times;</span><h3>Alert!</h3> <p>Have you set up your account? <a href='settings.php'>Settings</a></p> </div>";
		
	} */
	
	
?>
<html>
	<head>
		<title>Money</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
	</head>
	<body>
		<?php echo $account ?>
		<?php echo $warning ?>
<div id = 'con'>
	<div id = 'content'>
	<?php echo $password ?>
		<?php
		
		$query = "SELECT * FROM money WHERE id = :id";
		$query_params = array(':id' => $_SESSION['user']['id']);
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);	
		} catch(PDOException $ex) {
			die("500 Server Error.");
		}
		$row = $stmt->fetch();
		if ($row['use_cred'] == '1') {
			echo '
		<form action="php/spend.php" method="post">
			<div id = "wrapper">
				<div class = "content">
					<div id = "upper">
						<h3 class="title" > Money / Credit</h3>
						<h1> ' . $money . '/' . $row["credit"] . ' </h1>
						
						<br /><br />
					</div>
					<div id = "lower">
						<br />
						<input type="submit" name="debit" class="w3-button w3-teal w3-hover-green w3-round-xxlarge button" value = "Debit"  style = "float: right"/>
						<input type="submit" name="credit" class="w3-button w3-teal w3-hover-green w3-round-xxlarge button" value="Credit" style="float: left"/>
						</div>
					</div>
			</div>
		</form> ';
		} else {
			echo '
		<form action="php/spend.php" method="post">
			<div id = "wrapper">
				<div class = "content">
					<div id = "upper">
						<h3 class="title" > Money</h3>
						<h1> ' . $money . ' </h1>
						
						<br /><br />
					</div>
					<div id = "lower">
						<br />
						<input type="submit" name="debit" class="w3-button w3-teal w3-hover-green w3-round-xxlarge button" value = "Debit"  style = "float: center"/>
						</div>
					</div>
			</div>
		</form> ';
		}
		?>
	</div>
		<div id="footer">
			<button onclick='javascript:window.location.replace("settings.php")' class="w3-button">Settings</button>
			<button onclick='javascript:window.location.replace("history.php")' class="w3-button">History</button>
			<button onclick='javascript:window.location.replace("reglog/logout.php")' class="w3-button">Logout</button>
			
<?php 
	$query = "SELECT access_teir FROM users WHERE id = :id";
	$query_params = array(":id" => $_SESSION['user']['id']);
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($query_params);
	} catch(PDOException $ex) {
		die("500 Server Error.");
	}
	$row = $stmt->fetch();
	
	if ($row['access_teir'] < 10) {
	} else { 
		print "<button onclick='javascript:window.location.replace(\"administrat/list.php\")' class=\"w3-button w3-red\">Administrate</button>";
	}
?>
		</div>
</div>
	</body>
</html>