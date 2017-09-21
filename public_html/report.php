<?php 
	require('common.php');
	if (!$_SESSION['user']) {
		header("Location: ../reglog/login.php");
		die("Redirecting to login");
	} else {
		$username = $_SESSION['user']['username'];
	}
	
	$uid = $_SESSION['user']['id'];
	
?>
<html>
	<head>
		<title>Reports</title>
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
		<div class="w3-card-4" style = "width: 620px; margin: auto; margin-top: 10px;">
			<div class='w3-container w3-blue'>
				<h1>Reports for UID: <?php echo $uid; ?></h1>
				<button onclick="table.search('Daily'); draw();"	class='w3-button'>Daily</button><button onclick="table.search('Weekly'); draw();" class='w3-button'>Weekly</button><button onclick="table.search('Monthly'); draw();" class='w3-button'>Monthly</button>
			</div>
			<div class="w3-container" style = "text-align: left;">
			
				<br /><br />
				<table id = "users" class = "display" style="margin-bottom: 10px; margin-top: 10px;">
					<thead>
						<tr>
							<th>Type</th>
							<th>$ Remaining</th>
							<th>Alerts</th>
							<th>Spent</th>
							<th>Date</th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>Type</th>
							<th>$ Remaining</th>
							<th>Alerts</th>
							<th>Spent</th>
							<th>Date</th>
						</tr>
					</tfoot>
					
				<tbody>	
				<?php
					$query = "SELECT type, amount, alert, difference, date FROM reports WHERE uid = " . $uid;
					$execute = $dbc->query($query);
					
					foreach($execute as $row) {
						$row_serial = serialize($row);
						
						print "<tr>  
						<td>".$row['type']."</td> 
						<td>".$row['amount']."</td> 
						<td>".$row['alert']."</td> 
						<td>".$row['difference']."</td> 
						<td>".$row['date']."</td>
						
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
				<button onclick="javascript:window.location.replace('index.php');" class='w3-button'>Home</button>
		</div>
		
	</body>
</head>