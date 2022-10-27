<?php

	$hostName = 'localhost';
	$userName = 'komiljonov_db18';
	$password = 'st6BWHM33WjiGfqd';
	$dbName = 'komiljonov_db18';

	$conn = mysqli_connect($hostName,$userName,$password,$dbName);
	if (!$conn) {
		echo "MYSQLI_EROR\n\n" . mysqli_error($conn);
	}
	function realstring($text) {
		global $conn;
    	$result = mysqli_real_escape_string($conn,$text);
    	return $result;
	}
?>