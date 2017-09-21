<?php 
	require('common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	
	$query = "SELECT recurs FROM money WHERE id = :id";
	$query_params = array(':id' => $_SESSION['user']['id']);
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($query_params);
		
		
	} catch(PDOException $ex) {
		die("500 Server Error.");
	}
	
	
	$row = $stmt->fetch();
	
	if ($row['recurs'] == '1') {
		$recus = 'checked';
	} else {
		$recus = null;
	} 
	
	$query = "SELECT use_cred FROM money WHERE id = :id";
	$query_params = array(':id' => $_SESSION['user']['id']);
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($query_params);
		
		
	} catch(PDOException $ex) {
		die("500 Server Error.");
	}
	
	
	$row = $stmt->fetch();
	if ($row['use_cred'] == '1') {
		$use_cred = 'checked';
	} else {
		$use_cred = null;
	}
	
	$query = "SELECT link FROM money WHERE id = :id";
	$query_params = array(':id' => $_SESSION['user']['id']);
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($query_params);
		
		
	} catch(PDOException $ex) {
		die("500 Server Error.");
	}
	
	
	$row = $stmt->fetch();
	if ($row['link'] == '1') {
		$link = 'checked';
	} else {
		$link = null;
	}
	
	$act_q = "SELECT id, account_name FROM accounts WHERE uid = " . $_SESSION['user']['id'];
	$act_e = $dbc->query($act_q);
	
	
	?>
	
<html>
	<head>
		<title>Settings</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
		<script>
			function toggle(el) {
				var x = document.getElementById(el);
				if (x.style.display === 'none') {
					x.style.display = 'block';
				} else {
					x.style.display = 'none';
				}
			}
		</script>
		
	</head>
	<body>
		<div class="content">
			<h1> Personal </h1>
				<form action="php/personal_update.php" method="post">
					<input type = "text" name = "username" placeholder="Username" />
					<br /><br />
					<input type = "text" name = "email" placeholder="Email" />
					<br /><br />
					<input type = "password" name = "password"  placeholder = "Password" />
					<br /><br />
					<input type="submit" />
				</form>
			<br />
		
			<h1> Monitary </h1>
				<form action="php/money_update.php" method="post">
				
					<h4><input type="button" onclick="toggle('cash');" value = "Generic"></h4>
					<div id="cash" style = 'display: none;' class = 'w3-card-4 w3-round setting'>
						<input type = "number" step="0.01" name = "money" placeholder="Spending Money" />
						<br /><br />
						<input type = "number" step="0.01" name = "tax" placeholder="Local Tax %" />
						<br /><br />
					</div>
					
					
					<h4><input type="button" onclick="toggle('creditinfo');" value = "Accounts"></h4>
						<div id='creditinfo' style = 'display: none;' class = 'w3-card-4 w3-round setting'>
							<p class="title_sub" >Other Accounts</p>
								<p>Accounts offers you the chance to add more accounts from which you can pull money.</p><p><a href='accounts.php'>Account settings</a></p>
								<br /><br />
								<a onclick='javascript:window.location = ("php/add_account.php")' class="w3-button w3-green">Add Account</a>
								<br /><br />
								<?php foreach ($act_e as $accounts) {
										echo $accounts['account_name'] . " ::  <a onclick='javascript:window.location = (\"php/del_account.php?id=".$accounts['id']."\")'  class='w3-button w3-red'>Delete</a><br /><br />";
								}
								?>
								
								
						</div>
						
					<h4><input type="button" onclick="toggle('recurs');" value = "Budgeting"></h4>
						<div id='recurs' style = 'display: none;' class = 'w3-card-4  w3-round setting'>
							<p>Budgeting replaces the default money shown on the home page and updates the day at 00:00, the Week on Monday, and the month on the 1st (all at 00:00).</p>
							<input type = 'number' step="0.01" name = 'month_def' placeholder = "Monthly" />
							<br /><br />
							<input type = 'number' step="0.01" name = 'week_def' placeholder = "Weekly" />
							<br /><br />
							<input type = 'number' step="0.01" name = 'day_def' placeholder = "Daily" />
							<br /><br />
							<input type = 'number' step="0.01" name = 'warning_week' placeholder = "Low Week $ Warning" />
							<br /><br />
							<input type = 'number' step="0.01" name = 'warning_month' placeholder = "Low Month $ Warning" />
							<br /><br />
							
							<p class="title_sub" >Use Budgeting</p>
								<input type="checkbox" class='w3-check' name = "recurs" <?php echo $recus ?>>
							
						</div>
						
					<br /><br />
					
					
					<br /><br />
<!--					<input type = "number" name = "credit_period"  placeholder = "Credit Period" />  
					<br /><br /> -->
					<input type="submit" />
				</form>
				<button onclick="javascript:window.location = ('index.php');" class='w3-button'>Home</button>
		</div>
	
	</body>
</html>