<?php 
	require('common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	$uid = $_SESSION['user']['id'];
	
	$query_a = "SELECT difference FROM `reports` WHERE `uid` = ".$uid." AND `type` = 'Daily'";
	$result = $dbc->query($query_a);
	$spent = 0; 
	foreach($result as $thing) {
		$spent = $spent + (sqrt(pow($thing['difference'], 2)));
	}
	
?>
<html>
	<head>
		<title>History</title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
		<script src="//code.jquery.com/jquery-1.12.4.js"> </script>
		<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" > </script>
		<script>
		$(document).ready(function() {
			$('#users').DataTable({
				paging: false
			});
		} );
	</script>
		
		
	</head>
	<body>
		<div class="w3-card-4" style = "width: 800px; margin: auto; margin-top: 10px;">
			<div class='w3-container w3-blue text-align: center;'>
				<h1>Transations for UID: <?php echo $uid; ?></h1>
				<h5>Total Budget Spending: $<?php echo $spent ?> (Excluding Today)</h5>
			</div>
			<div class="w3-container" style = "text-align: left;">
			
				<br /><br />
				<table id = "users" class = "display" style="margin-bottom: 10px; margin-top: 10px;">
					<thead>
						<tr>
							<th>Username</th>
							<th>ID</th>
							<th>Amount</th>
							<th>Account</th>
							<th>Tax</th>
							<th>Reason</th>
							<th>Date</th>
							<th>Undo</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Username</th>
							<th>ID</th>
							<th>Amount</th>
							<th>Account</th>
							<th>Tax</th>
							<th>Reason</th>
							<th>Date</th>
							<th>Undo</th>
						</tr>
					</tfoot>
					
				<tbody>	
				<?php
					$query = "SELECT username, uid, amount, cred_deb, tax, reason, date, id, aid FROM log WHERE uid = " . $uid;
					$execute = $dbc->query($query);
					
					foreach($execute as $row) {
						$row_serial = serialize($row);
						
						print "<tr>  
						<td>".$row['username']."</td> 
						<td>".$row['id']."</td> 
						<td>".$row['amount']."</td> 
						<td>".$row['cred_deb']."</td> 
						<td>".$row['tax']."</td> 
						<td>".$row['reason']."</td> 
						<td>".$row['date']."</td>
						<td><form action='php/undo.php' method='post'>
								<input type='hidden' name='therow' value='".$row_serial."'>
								<input type='hidden' name='trans_mon' value='".$row['amount']."'>
								<button type='submit' class = 'w3-button w3-red'>Undo</button>
								<input type='hidden' name='form' value = 'noForm'>
								
							</form>
						</td>
						
						</tr>";
					}
				?>
				</tbody>
				</table>
			</div>
			<br /><br />
		</div>
		<br /><br />
		<div class='w3-container w3-center'>
				<button onclick="javascript:window.location = ('index.php');" class='w3-button'>Home</button>
		</div>
		
	</body>
</head>