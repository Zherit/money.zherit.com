<?php 
	require('../common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	$uid = $_SESSION['user']['id'];
	
	if(!empty($_POST)) {
		if($_POST['name'] && $_POST['amount']) {
			$query = "INSERT INTO accounts (uid, username, account_name, amount, link_bud) VALUES (:uid, :username, :account_name, :amount, :link)";
			if (empty($_POST['link'])) {
				$query_params = array( 
							":uid" => $uid,
							":username" => $_SESSION['user']['username'],
							":account_name" => $_POST['name'],
							":amount" => $_POST['amount'],
							":link" => 0
							);
			} else if ($_POST['link']) {
				$query_params = array( 
							":uid" => $uid,
							":username" => $_SESSION['user']['username'],
							":account_name" => $_POST['name'],
							":amount" => $_POST['amount'],
							":link" => 1
							);
				
			}
			try {
				$stmt = $dbc->prepare($query);
				$execute = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error." . $ex);
			}
			
			
			
			header("Location: ../index.php");
		}
	}
	


?>
<html>
	<head>
		<title> Add Account </title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" type="text/css" href="../css/style.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
		<div class='content'>
		<br /><br /><br />
			<h1>Add Account</h1>
			<form action="add_account.php" method="post">
				<input type='text' placeholder='Account Name' name="name">
				<br /><br />
				<input type='number' step='0.01' placeholder='Starting Amount' name="amount">
				<br /><br />
				<p>When spending would you like to also pull money from your budget?</p>
				<input type='checkbox' class='w3-check' name='link' />
				<br /><br />
				<input type='submit'>
			</form>
		</div>
	</body>
</html>