<?php
	
	define('CLIENT_ID', 'e06da182073046ab9172');
	define('CLIENT_SECRET', 'c25cb41a3963daf844d8f134a07ac01eea745419');
	define('REDIRECT_URL', 'https://komiljonovdev.uz/okdeveloper/sites/stackOverFlowUz/login-with-github/');

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