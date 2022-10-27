<?php
	
	define('CLIENT_ID', '<CLIENT_ID>');
	define('CLIENT_SECRET', '<CLIENT_SECRET>');
	define('REDIRECT_URL', '<REDIRECT_URL>');

	if (!session_id()) {
		session_start();
	}

	require_once 'Models/dbConfig.php';
	require_once 'Models/githubOauthClient.php';

	$gitClient = new githubOauthClient(
		array(
			'client_id'=>CLIENT_ID,
			'client_secret'=>CLIENT_SECRET,
			'redirect_uri'=>REDIRECT_URL
		)
	);
	if (isset($_SESSION['acccess_token'])) {
		$accessToken = $_SESSION['acccess_token'];
	}
?>