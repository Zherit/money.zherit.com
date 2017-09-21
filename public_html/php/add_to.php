<?php 

	require('../common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	if (!empty($_POST['whatis'])) {
		if ($_POST['whatis'] == 'day') {
			$query = "UPDATE money SET day_ac = (day_ac + :money) WHERE id = :uid";
			$query_params = array(':money' => $_POST['money'], ':uid' => $_SESSION['user']['id']);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 1");
			}
			header('Location: ../index.php');
			
		} elseif ($_POST['whatis'] == 'week') {
			$query = "UPDATE money SET week_ac = (week_ac + :money) WHERE id = :uid";
			$query_params = array(':money' => $_POST['money'], ':uid' => $_SESSION['user']['id']);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 1");
			}
			header('Location: ../index.php');
			
		} elseif ($_POST['whatis'] == 'month') {
			$query = "UPDATE money SET month_ac = (month_ac + :money) WHERE id = :uid";
			$query_params = array(':money' => $_POST['money'], ':uid' => $_SESSION['user']['id']);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 1");
			}
			header('Location: ../index.php');
		} elseif ($_POST['whatis'] == 'credit') {		
			$query = "UPDATE money SET credit = (credit + :money) WHERE id = :uid";
			$query_params = array(':money' => $_POST['money'], ':uid' => $_SESSION['user']['id']);
			
			try {
				$stmt = $dbc->prepare($query);
				$result = $stmt->execute($query_params);
			} catch(PDOException $ex) {
				die("500 Server Error. 1");
			}
			header('Location: ../index.php');
		
		
		}else { echo "ERROR"; }
	} else { }
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
				<h1> Add/Subtract Money to/from <?php echo $_GET['type']; ?> </h1>
			</div>
			<div class="w3-container" style = "text-align: center;">
			<br /><br />
				<div class='content'>
			
					<form action="add_to.php" method="post">
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