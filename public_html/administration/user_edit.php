<?php
require('../common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
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
		header('Location: ../error/403error.php');
	} else { ?>
	
	
	<?php
	$user_id = $_GET['user_id'];
	
	$query = "SELECT recurs FROM money WHERE id = :id";
	$query_params = array(':id' => $user_id);
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
	$query_params = array(':id' => $user_id);
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
	$query_params = array(':id' => $user_id);
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
	
	?>
	
<html>
	<head>
		<title>ADMIN Settings</title>
		<link rel="stylesheet" type="text/css" href="../css/style.css">
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
		<br/><br/>
		<h1 style = "text-align: center;">EDITING USER ACCOUNT: <?php echo $user_id; ?></h1>
		<div class="content">
			<h1> Personal </h1>
				<form action="personal_update.php" method="post">
					<input type = "text" name = "username" placeholder="Username" />
					<br /><br />
					<input type = "text" name = "email" placeholder="Email" />
					<br /><br />
					<input type = "password" name = "password"  placeholder = "Password" />
					<br /><br />
					<input type='hidden' value='<?php echo $user_id ?>' name='user_id'>
					<input type="submit" />
				</form>
			<br />
		
			<h1> Monitary </h1>
				<form action="money_update.php" method="post">
				
					<h4><input type="button" onclick="toggle('cash');" value = "Generic"></h4>
					<div id="cash" style = 'display: none;' class = 'w3-card-4 w3-round setting'>
						<input type = "number" step="0.01" name = "money" placeholder="Spending Money" />
						<br /><br />
						<input type = "number" step="0.01" name = "tax" placeholder="Local Tax %" />
						<br /><br />
					</div>
					
					
					<h4><input type="button" onclick="toggle('creditinfo');" value = "Credit"></h4>
						<div id='creditinfo' style = 'display: none;' class = 'w3-card-4 w3-round setting'>
							<label class="switch">
								<input type="checkbox" name = "card">
								<span class="slider round"></span>
							</label>
							<br /><br />
							<input type = "number" step="0.01" name = "credit" placeholder="Credit" />
							<br /><br />
							<p class="title_sub" >Link Credit & Spending</p>
								<input type="checkbox" class='w3-check' name = "link" <?php echo $link ?>>
							<p class="title_sub" >Use Credit</p>
								<input type="checkbox" class='w3-check' name = "use_cred" <?php echo $use_cred ?>>
						</div>
						
					<h4><input type="button" onclick="toggle('recurs');" value = "Recursion"></h4>
						<div id='recurs' style = 'display: none;' class = 'w3-card-4  w3-round setting'>
							<input type = 'number' name = 'month_def' placeholder = "Monthly" />
							<br /><br />
							<input type = 'number' name = 'week_def' placeholder = "Weekly" />
							<br /><br />
							<input type = 'number' name = 'day_def' placeholder = "Daily" />
							<br /><br />
							<input type = 'number' name = 'warning_week' placeholder = "Low Week $ Warning" />
							<br /><br />
							<input type = 'number' name = 'warning_month' placeholder = "Low Month $ Warning" />
							<br /><br />
							
							<p class="title_sub" >Use Recursion</p>
								<input type="checkbox" class='w3-check' name = "recurs" <?php echo $recus ?>>
							
						</div>
						
					<br /><br />
					
					
					<br /><br />
<!--					<input type = "number" name = "credit_period"  placeholder = "Credit Period" />  
					<br /><br /> -->
					<input type='hidden' value='<?php echo $user_id ?>' name='user_id'>
					<input type="submit" />
				</form>
		</div>
	
	</body>
</html>
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	
	<?php } ?>