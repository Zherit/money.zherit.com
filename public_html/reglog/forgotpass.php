<?php
	require('../common.php');
	
	$form = "block";
	$text = "none";
	$alert = null;
	
	if(!empty($_POST)) {
		if(!empty($_POST['username'])) {
			if(!empty($_POST['email'])) {
				$query = "SELECT username, email FROM users WHERE username = :username AND email = :email";
				$query_params = array(':username' => $_POST['username'], ':email' => $_POST['email']);
				try {
					$stmt = $dbc->prepare($query);
					$result = $stmt->execute($query_params);
				} catch(PDOException $ex) {
					die("500 Server Error.");
				}
				
				$row = $stmt->fetch();
				if(!empty($row)) {
					$to      = 'amackay1999@gmail.com';
					$subject = 'Connection Error';

					if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
						$ip=$_SERVER['HTTP_CLIENT_IP'];
					} elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
						$ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
					} else {
						$ip=$_SERVER['REMOTE_ADDR'];
					}

					$message = 'A user has forgotten their password.  ----  Username: '. $_POST['username'] . '  ----  Email: ' . $_POST['email'] . '  ----  IP Address: ' . $ip;
					$headers = 'From: UserAlert' . "\r\n" .
								'Reply-To: no-reply' . "\r\n" .
								'X-Mailer: PHP/' . phpversion();

					if (mail($to, $subject, $message, $headers)) {
						$form = "none";
						$text = "block";
					} else {
						die('Error with the mail server, contact admin directly.');
					}
					
				} else { $alert = "<div class='w3-panel w3-red w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Alert!</h3> <p>No account with that Username/Email combo.</div>"; }
			} else { $alert = "<div class='w3-panel w3-amber w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Matching account email required.</div>"; }
		} else { $alert = "<div class='w3-panel w3-amber w3-card-4 w3-animate-opacity'> <span onclick='this.parentElement.style.display=`none`'
  class='w3-button w3-red w3-panel w3-large w3-display-topright'>&times;</span><h3>Error!</h3> <p>Username and matching account email required.</div>"; }
	} else {  }

?>

<html>
	<head>
		<title> Forgot Password </title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
	</head>
	<body>
	<?php echo $alert; ?>
		<div id='incomplete' style='text-align: center; width: 500px; margin: auto; display: <?php echo $form; ?>;'>
		<br /><br /><br />
			<h1>Forgot Password</h1>
			<form action="forgotpass.php" method="post">
				<input type='text' placeholder='username' name="username">
				<br /><br />
				<input type='text' placeholder='email' name="email">
				<br /><br />
				<input type='submit'>
			</form>
		</div>
		
		
		<div id='complete' style="text-align: center; width: 500px; margin: auto; display: <?php echo $text; ?>;" >
			<div class="w3-panel w3-pale-green w3-bottombar w3-border-green w3-border w3-animate-opacity">
				<h1>Forgot Password</h1>
				<p>An email has been sent to the administration notifying them that you have forgotten your password. Expect to hear back from them in the next 24 Hours with a temporary password. This password will be sent to the email you provided.</p>
			</div>
		</div>
	</body>
</html>