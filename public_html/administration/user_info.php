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
	} else {
	
?>
<html>
	<head>
		<title>Administrator Page</title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
		<script src="//code.jquery.com/jquery-1.12.4.js"> </script>
		<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" > </script>
		<script>
		$(document).ready(function() {
			$('#users').DataTable();
		} );
	</script>
		
		
	</head>
	<body>
		<div class="w3-card-4" style = "width: 800px; margin: auto; margin-top: 10px;">
			<div class='w3-container w3-blue'>
				<h1>Transations for UID: <?php echo $_GET['user_id']; ?></h1>
			</div>
			<div class="w3-container" style = "text-align: left;">
			
				<br /><br />
				<table id = "users" class = "display" style="margin-bottom: 10px; margin-top: 10px;">
					<thead>
						<tr>
							<th>ID</th>
							<th>Username</th>
							<th>UserID</th>
							<th>Amount</th>
							<th>Type</th>
							<th>Tax</th>
							<th>Reason</th>
							<th>Date</th>
							<th>IP Addr</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>ID</th>
							<th>Username</th>
							<th>UserID</th>
							<th>Amount</th>
							<th>Type</th>
							<th>Tax</th>
							<th>Reason</th>
							<th>Date</th>
							<th>IP Addr</th>
						</tr>
					</tfoot>
					
				<tbody>	
				<?php
					$query = "SELECT id, username, uid, amount, cred_deb, tax, reason, date, ip FROM log WHERE uid = " . $_GET['user_id'];
					$execute = $dbc->query($query);
					
					foreach($execute as $row) {
						print "<tr> 
						<td>".$row['id']."</td>  
						<td>".$row['username']."</td> 
						<td>".$row['uid']."</td> 
						<td>".$row['amount']."</td> 
						<td>".$row['cred_deb']."</td> 
						<td>".$row['tax']."</td> 
						<td>".$row['reason']."</td> 
						<td>".$row['date']."</td>
						<td>".$row['ip']."</td> 
						
						</tr>";
					}
				?>
				</tbody>
				</table>
			</div>
			<br /><br />
		</div>
		
	</body>
</head>












<?php } ?>