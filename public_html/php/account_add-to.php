<?php 
	require('../common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	if(empty($_GET['type'])) {
		header("Location: ../accounts.php");
		
	}
	
	$type = $_GET['type'];
	
	if (!empty($_POST['whatis'])) {
		$query = "UPDATE accounts SET amount = (amount + :money) WHERE id = :type AND uid = :uid";
		$qp = array(":money" => $_POST['money'], ":type" => $_POST['whatis'], ":uid" => $_SESSION['user']['id']);
		
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($qp);
		} catch(PDOException $ex) {
			die("500 Server Error. 1");
		}
		
		header('Location: ../accounts.php');
		
		
	}
	
	?>
	
<html>
	<head>
		<title>Settings</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
	</head>
	<body>
		<div class="w3-card-4" style = "max-width: 800px; margin: auto; margin-top: 10px;">
			<div class='w3-container w3-blue' style='text-align: center;'>
				<h1> Add/Subtract Money </h1>
			</div>
			<div class="w3-container" style = "text-align: center;">
			<br /><br />
				<div class='content'>
			
					<form action="account_add-to.php" method="post">
						<input type='hidden' name='whatis' value='<?php echo $_GET['type']; ?>'>
						<input type = "number" step="0.01" name="money" placeholder="Amount" />
						<br /><br />
						<input type="submit" />
					</form>
					<br /><br />
					<p>To subtract, put in a negative number</p>
				</div>
			</div>
		</div>
	</body>
</html>