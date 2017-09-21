<?php
	require('common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}


	$act_q = "SELECT id, account_name, amount, link_bud FROM accounts WHERE uid = " . $_SESSION['user']['id'];
	$act_e = $dbc->query($act_q);

?>
<html>
	<head>
		<title>Accounts</title>
		<link rel="stylesheet" type="text/css" href="css/style.css">
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta charset="UTF-8">
	</head>
	<body>
		<div class="w3-card-4" style = "max-width: 800px; margin: auto; margin-top: 10px;">
			<div class='w3-container w3-blue' style="text-align: center;">
				<h1>Accounts for UID: <?php echo $_SESSION['user']['id']; ?></h1>
			</div>
			<div class="w3-container" style = "text-align: left;">
			<p style="text-align: center;">Accounts can be created and deleted in the settings page</p>
			<br /><br />
				<div class='content'>
			<?php 
					foreach ($act_e as $accounts) {
						echo "<h1 class='w3-border'>".$accounts['account_name']."</h1>";
						echo "<div class='w3-border'><h3><h3 class='w3-lime'>$".$accounts['amount']."</h3><div style='margin-left:5px;'><button onclick='javascript:window.location = (\"php/account_add-to.php?type=".$accounts['id']."\")' class=\"w3-button w3-teal\" style='margin-right: 5px;'>+ / -</button>";	
						if ($accounts['link_bud'] == 1) {
							echo "<button onclick='javascript:window.location = (\"php/account_unlink.php?type=".$accounts['id']."\")' class=\"w3-button w3-teal\">Unlink</button>";
							echo "<p style = 'font-size: x-small;'>Linked to primary account</p>";
						} else {
							echo "<button onclick='javascript:window.location = (\"php/account_link.php?type=".$accounts['id']."\")' class=\"w3-button w3-teal\">Link</button>";
							echo "<p style = 'font-size: x-small;'>Not linked to primary account</p>";
						}
						echo "</h3></div></div> <br /><br />";
					}
								?>
				
				<br /><br />
				<div class='w3-container w3-center'>
					<button onclick="javascript:window.location = ('index.php');" class='w3-button'>Home</button>
				</div>		
					<br /><br /
				</div>
			</div>
		</div>
	</body>
</html>
