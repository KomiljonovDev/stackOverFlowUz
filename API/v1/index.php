<?php
	require_once 'helpers/classes.php';
	$db = new dbmysqli;
	$db->dbConnect();
	$class = new Post;
	print_r($class->getAllPost);
?>