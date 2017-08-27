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
				<h1>Users</h1>
			</div>
			<div class="w3-container" style = "text-align: left;">
			
				<br /><br />
				<table id = "users" class = "display" style="margin-bottom: 10px; margin-top: 10px;">
					<thead>
						<tr>
							<th>UserID</th>
							<th>Username</th>
							<th>Email</th>
							<th>Permission</th>
							<th></th>
							<th></th>
						</tr>
					</thead>
					<tfoot>
						<tr>
							<th>UserID</th>
							<th>Username</th>
							<th>Email</th>
							<th>Permission</th>
							<th></th>
							<th></th>
						</tr>
					</tfoot>
					
				<tbody>	
				<?php
					$query = "SELECT username, email, access_teir, id FROM users";
					$execute = $dbc->query($query);
					
					foreach($execute as $row) {
						print "<tr> 
						<td>".$row['id']."</td>  
						<td>".$row['username']."</td> 
						<td>".$row['email']."</td> 
						<td>".$row['access_teir']."</td> 
						<td> <button class = 'w3-button w3-gray w3-small' onclick='window.location.href=`user_info.php?user_id=".$row['id']."`'> History </button> </td>
						<td> <button class = 'w3-button w3-gray w3-small' onclick='window.location.href=`user_edit.php?user_id=".$row['id']."`'> Edit </button> </td>
						
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
		
	
	