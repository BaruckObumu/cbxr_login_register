<?php
session_start();

$GLOBALS['config'] = array(
		'mysql' => array(
				'host' => '127.0.0.1',
				'username' => 'root',
				'password' => 'meggiemoo12',
				'db' => 'logreg'
			),
		'remember' => array(
				'cookie_name' => 'hash',
				'cookie_expiry' => 604800,
			),
		'session' => array(
				'session_name' => 'user'
			)
	);

// spl = standard php library
spl_autoload_register(function($class) {
	require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';

?>