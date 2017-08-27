<?php 
	require("../common.php");
	
	$alert = null;
	
	if(!empty($_POST)) {
		$login = false; 
	
		$query = "SELECT id, username, password, salt, email FROM users WHERE username = :username";
		$query_params = array(':username' => $_POST['username']);
		
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error.");
		}
		
	
		
		$row = $stmt->fetch();
		if ($row) {
			$pass_check = hash('sha256', $_POST['password'] . $row['salt']);
			for ($round = 0; $round < 65536; $round++) {
				$pass_check = hash('sha256', $pass_check . $row['salt']);
			} if ($pass_check === $row['password']) {
				$login = true;
			}
		}
		
		if ($login) {
			unset($row['salt']);
			unset($row['password']);
			$_SESSION['user'] = $row;
			
			setcookie("money_zherit_username", $_POST['username'] , time()+3600*24*30*12*10);
			setcookie("money_zherit_password", $_POST['password'] , time()+3600*24*30*12*10);
			
			header("Location: ../index.php");
			die("Login Successful");
		} else {
			$alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Login Failed. Incorrect Username or Password. </div>";
		}
	} elseif($_COOKIE["money_zherit_username"]) {
		$login = false; 
	
		$query = "SELECT id, username, password, salt, email FROM users WHERE username = :username";
		$query_params = array(':username' => $_COOKIE["money_zherit_username"]);
		
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error.");
		}
		
	
		
		$row = $stmt->fetch();
		if ($row) {
			$pass_check = hash('sha256', $_COOKIE["money_zherit_password"] . $row['salt']);
			for ($round = 0; $round < 65536; $round++) {
				$pass_check = hash('sha256', $pass_check . $row['salt']);
			} if ($pass_check === $row['password']) {
				$login = true;
			}
		}
		
		if ($login) {
			unset($row['salt']);
			unset($row['password']);
			$_SESSION['user'] = $row;
			
			header("Location: ../index.php");
			die("Login Successful");
		} else {
			$alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Login Failed. Incorrect Username or Password. </div>";
		}
	}
?>

<html>
	<head>
		<title>Login</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<meta charset="UTF-8">
	</head>
	<body>
	<?php echo $alert; ?>
	<div style = "width: 500px; margin: auto; margin-top: 10px;" class="w3-card-4">
		<div class="w3-container w3-blue">
			<h1>Login</h1>
		</div>
		<div class="w3-container" style = "text-align: left;">
			<form action = "login.php" method="post">
			<p />
				<label>Username</label>
					<input class = "w3-input" type = "text" name = "username" value = "" placeholder = "Username" />
				<br />
				<br />
				<label>Password</label>
					<input class = "w3-input" type = "password" name = "password" value = "" placeholder = "password" />
				<br />
				<br />
				<input class = "w3-submit" type = "submit" name = "submit" value = "Submit" />
			</form>
			<a href='register.php'>Register</a>
			<a href='forgotpass.php'>Forgot Password</a>
			<p />
		</div>
	</div>
	</body>
</html>
		