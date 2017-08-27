<?php 
	require("../common.php");
	$alert = null;
	
	
	if(!empty($_POST)) {
		if (empty($_POST['username'])) {
			$alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Invalid Username.</div>";
		} elseif (empty($_POST['password'])) {
			$alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Invalid Password.</div>";
		} elseif ($_POST['password'] !== $_POST['password_verif']) {
			$alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Password's do not match.</div>";
		} elseif (empty($_POST['email'])) {
			$alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Invalid Email.</div>";
		} elseif (!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
			$alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Invalid Email Format.</div>";
		} else {
		 
		$query = "SELECT 1 FROM users WHERE username = :username";
		$query_params = array(':username' => $_POST['username']);
		
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error. 1");
		}
		
		$row = $stmt->fetch();
		if ($row) {
			$alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Username already taken.</div>";
		} else {
		
		$query = "SELECT 1 FROM users WHERE email = :email";
		$query_params = array(':email' => $_POST['email']);
		
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error. 2");
		}
		
		$row = $stmt->fetch();
		if ($row) {
			$alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Email already taken.</div>";
		} else {
		
		$salt = dechex(mt_rand(0,2147483647)) . dechex(mt_rand(0, 2147483647));
		$password = hash('sha256', $_POST['password'] . $salt);
		for($round = 0; $round < 65536; $round++) {
			$password = hash('sha256', $password . $salt);
		}
		
		
		$query = "INSERT INTO users (username, password, salt, email) VALUES (:username, :password, :salt, :email)";
		$query_params = array(':username' => $_POST['username'], ':password' => $password, ':salt' => $salt, ':email' => $_POST['email']);
		
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error. 3");
		}
		
		$query = "INSERT INTO money (username, money, credit) VALUES (:username, :money, :credit)";
		$query_params = array(':username' => $_POST['username'], ':money' => 0, ':credit' => 0);
		
		try {
			$stmt = $dbc->prepare($query);
			$result = $stmt->execute($query_params);
		} catch(PDOException $ex) {
			die("500 Server Error. 4");
		}
		
		
		header('Location: login.php');
	}
	}
	}
	}
?>

<html>
	<head>
		<title>Register</title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<meta charset="UTF-8">
	</head>
	<body>
	<?php echo $alert; ?>
	<div style = "width: 500px; margin: auto; margin-top: 10px;" class="w3-card-4">
		<div class="w3-container w3-blue">
			<h1>Register</h1>
		</div>
		
		<div class="w3-container" style = "text-align: left;">
			<form action="register.php" method="post">
				<p>
				<label>Username</label>
				<input type="text" class = 'w3-input' name="username" value=""  />
				<br />
				<br />
				<label>Email</label>
				<input type="text" class = 'w3-input' name="email" value=""  />
				<br />
				<br />
				<label>Password</label>
				<input type="password" class = 'w3-input' name="password" value=""  />
				<br />
				<br />
				<label>Confirm Password</label>
				<input type="password" class = 'w3-input' name="password_verif" value="" />
				<br />
				<br />
				<input class = 'w3-submit' type="submit" class = 'w3-input' name="submit" value="Submit" />
				<p>
		</form>
	</body>
</html>
		