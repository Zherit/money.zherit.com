<?php 

	$dbhost = "localhost";
	$dbusr = "dbaccess";
	$dbpass = "54SEJYEqvqrLLOS8";
	$db = "money";

	$utf = array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8');
	
	try {
		$dbc = new PDO("mysql:host={$dbhost}; dbname={$db}; charset=utf8", $dbusr, $dbpass, $utf);
	} catch (PDOException $ex) {
		die("500 Error, Failed to connect."); //$ex-> getMessage()
	}
	
	$dbc->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$dbc->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
	
	if(function_exists('get_magic_quotes_gpc') && get_magic_quotes_gpc()) { 
        function undo_magic_quotes_gpc(&$array) { 
            foreach($array as &$value) { 
            if(is_array($value)) { 
                undo_magic_quotes_gpc($value); 
            } else { 
                $value = stripslashes($value); 
            } 
        } 
    } 
     
        undo_magic_quotes_gpc($_POST); 
        undo_magic_quotes_gpc($_GET); 
        undo_magic_quotes_gpc($_COOKIE); 
    }

	header('Content-Type: text/html; charset=utf-8');
	
	session_start();
	
/*	
if (!isset($_SERVER['HTTPS']) || $_SERVER['HTTPS'] !== 'on') {
    if(!headers_sent()) {
        header("Status: 301 Moved Permanently");
        header(sprintf(
            'Location: https://%s%s',
            $_SERVER['HTTP_HOST'],
            $_SERVER['REQUEST_URI']
        ));
        exit();
	}
}
*/