<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	require_once 'helpers/classes.php';
	
	// $db = new dbmysqli;
	// $db->dbConnect();
	$class = new Post;
	$class->dbConnect();
	print_r($class->getPostsByBetween(1,10));
?>