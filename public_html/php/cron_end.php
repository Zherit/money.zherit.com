<?php
	require('../common.php');

	date_default_timezone_set('America/Toronto');
	
	$day = date('N');
	$date = date("d", strtotime("+1 day")); 
	
	$query = 'SELECT month_def, week_def, day_def, month_ac, week_ac, day_ac, username, id FROM money';
	function update($qr, $params) {
		require('../common.php');
		try {
			$stmt = $dbc->prepare($qr);
			$result = $stmt->execute($params);
		} catch(PDOException $ex) {
			die("500 Server Error." . $ex);
		}
	}
	
	
	
	if ($date == 01) {
		foreach($dbc->query($query) as $row) {
			$sql = 'INSERT INTO reports (uid, username, type, amount, alert, difference, date) VALUES (:uid, :username, :type, :amount, :alert, :difference, :date)';
			
			if ($row['month_ac'] < 0) {
				$alert = 'OVER';
			} else {
				$alert = '';
			}
			$amount = sqrt(pow(($row['month_def'] - $row['month_ac']), 2));
			$query_params = array(
								':uid' => $row['id'], 
								':username' => $row['username'],
								':type' => 'Monthly',
								':amount' => $row['month_ac'],
								':alert' => $alert,
								':difference' => $amount,
								':date' => date('d-m-y')
								);
								
			update($sql, $query_params);
			echo "updated Month";
		}
	} 
	if ($day == 7) {
		foreach($dbc->query($query) as $row) {
			$sql = 'INSERT INTO reports (uid, username, type, amount, alert, difference, date) VALUES (:uid, :username, :type, :amount, :alert, :difference, :date)';
			
			if ($row['week_ac'] < 0) {
				$alert = 'OVER';
			} else {
				$alert = '';
			}
			$amount = sqrt(pow(($row['week_def'] - $row['week_ac']), 2));
			$query_params = array(
								':uid' => $row['id'], 
								':username' => $row['username'],
								':type' => 'Weekly',
								':amount' => $row['week_ac'],
								':alert' => $alert,
								':difference' => $amount,
								':date' => date('d-m-y')
								);
								
			update($sql, $query_params);
			echo "updated Week";
		}
	}
	
	foreach($dbc->query($query) as $row) {
		$sql = 'INSERT INTO reports (uid, username, type, amount, alert, difference, date) VALUES (:uid, :username, :type, :amount, :alert, :difference, :date)';
			
			if ($row['day_ac'] < 0) {
				$alert = 'OVER';
			} else {
				$alert = '';
			}
			$amount = sqrt(pow(($row['day_def'] - $row['day_ac']), 2));
			$query_params = array(
								':uid' => $row['id'], 
								':username' => $row['username'],
								':type' => 'Daily',
								':amount' => $row['day_ac'],
								':alert' => $alert,
								':difference' => $amount,
								':date' => date('d-m-y')
								);
								
			update($sql, $query_params);
			echo "updated Day";
	}
	