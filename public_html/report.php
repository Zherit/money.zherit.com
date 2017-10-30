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
		<title>Reports</title>
		<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
		<link rel="stylesheet" href="https://cdn.datatables.net/1.10.15/css/jquery.dataTables.min.css">
		<script src="//code.jquery.com/jquery-1.12.4.js"> </script>
		<script src="https://cdn.datatables.net/1.10.15/js/jquery.dataTables.min.js" > </script>
		<script>
		
	$(document).ready(function() {
		// DataTable
		var table = $('#users').DataTable( {
						"paging":   false
					} );
		table.columns(0).search("none" ).draw();
		//Search
		$('#week').on('click', function () {
			table.columns(0).search("Weekly" ).draw();
			document.getElementById("week").className = "w3-button w3-green";
			document.getElementById("day").className = "w3-button w3-blue";
			document.getElementById("month").className = "w3-button w3-blue";
			
		}); 
		$('#day').on('click', function () {
			table.columns(0).search("Daily" ).draw();
			document.getElementById("day").className = "w3-button w3-green";
			document.getElementById("week").className = "w3-button w3-blue";
			document.getElementById("month").className = "w3-button w3-blue";
		}); 
		$('#month').on('click', function () {
			table.columns(0).search("Monthly" ).draw();
			document.getElementById("month").className = "w3-button w3-green";
			document.getElementById("day").className = "w3-button w3-blue";
			document.getElementById("week").className = "w3-button w3-blue";
		}); 
		
	} );
		
	</script>
		
		
	</head>
	<body>
		<div class="w3-card-4" style = "width: 620px; margin: auto; margin-top: 10px;">
			<div class='w3-container w3-blue'>
				<div style = "text-align: center;">
					<h1>Reports for UID: <?php echo $uid; ?></h1>
					<h5>Total Spent: $<?php echo $spent ?> (Excluding Today)</h5>
				</div>
				<br />
			</div>
			<div class="w3-container" style = "text-align: left;">
		<button id='day' class='w3-button w3-blue'>Daily</button><button id='week' class='w3-button w3-blue'>Weekly</button><button id='month' class='w3-button w3-blue'>Monthly</button>
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