<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	require_once 'helpers/classes.php';
	
	$post = new Post;
	print_r($post->getPostsByBetween(1,10));
?>