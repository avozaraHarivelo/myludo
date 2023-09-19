<?php

$host = 'localhost';
$db = 'myludo_data';
$user = 'myludo_root';
$password = 'xJ]@WiOQGkF2!';

$dsn = "mysql:host=$host;dbname=$db;charset=UTF8";

try {
	$pdo = new PDO($dsn, $user, $password);
	
	if ($pdo) {
		echo "connecté";
	}
} catch (PDOException $e) {
	echo phpinfo();
}

?>