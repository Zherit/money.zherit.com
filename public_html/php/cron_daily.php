<?php
	require('../common.php');

	
	
	
	
/*	$cycle_day = "1";
	$last_update = "1";
	
	$query = "INSERT INTO cron (cycle_day, last_update) VALUES (:cycle_day, :last_update)";
	$query_params = array(':cycle_day' => $cycle_day, ':last_update' => $last_update);
	
	try {
		$stmt = $dbc->prepare($query);
		$result = $stmt->execute($query_params);
	} catch(PDOException $ex) {
		die("500 Server Error.");
	} */

	date_default_timezone_set('America/Toronto');
	
	$day = date('N');
	$date = date('d'); 
	
	$query = 'SELECT month_def, week_def, day_def, id FROM money';
	function update($qr, $params) {
		require('../common.php');
		try {
			$stmt = $dbc->prepare($qr);
			$result = $stmt->execute($params);
		} catch(PDOException $ex) {
			die("500 Server Error.");
		}
	}
	
	
	
	if ($date == 01) {
		foreach($dbc->query($query) as $row) {
			$sql = 'UPDATE money SET month_ac = :month_ac WHERE id = :id';
			$query_params = array(':month_ac' => $row['month_def'], ':id' => $row['id']);
			
			update($sql, $query_params);
			echo "updated Month";
		}
	} 
	if ($day == 1) {
		foreach($dbc->query($query) as $row) {
			$sql = 'UPDATE money SET week_ac = :week_ac WHERE id = :id';
			$query_params = array(':week_ac' => $row['week_def'], ':id' => $row['id']);
			
			update($sql, $query_params);
			echo "updated Week";
		}
	}
	
	foreach($dbc->query($query) as $row) {
		$sql = 'UPDATE money SET day_ac = :day_ac WHERE id = :id';
		$query_params = array(':day_ac' => $row['day_def'], ':id' => $row['id']);
			
		update($sql, $query_params);
		echo "updated Day";
	}
	