<?php
	require('../common.php');
	
	unset($_SESSION['user']);
	setcookie('money_zherit_username', '', time() - 3600);
	setcookie('money_zherit_password', '', time() - 3600);
	
	header('Location: login.php');
	die('Logged Out');