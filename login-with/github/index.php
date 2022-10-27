<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);
	require_once 'config.php';

	if (isset($accessToken)) {
		$gitUser = $gitClient->getAuthenticatedUser($accessToken);
		if (!empty($gitUser)) {
			$slt = "SELECT * FROM users WHERE providers_token='{$accessToken}'";
			$sltQuery = mysqli_query($conn,$slt);
			if (mysqli_num_rows($sltQuery)>0) {
				$output = mysqli_fetch_assoc($sltQuery);
				// header("Location: " . 'https://front-end.../?oneTimeToken=' . $output['onetimetoken']); // FRONT-END GA QAYTA YO'NALTIRISH
			}else{
				$id = !empty($gitUser->id) ? $gitUser->id :'';
				$username = !empty($gitUser->login) ? $gitUser->login : '';
				$name = !empty($gitUser->name) ? $gitUser->name : '';
				$avatar_url = !empty($gitUser->avatar_url) ? $gitUser->avatar_url : '';
				$bio = !empty($gitUser->bio) ? $gitUser->bio : '';
				$location = !empty($gitUser->location) ? $gitUser->location : '';
				$created_at = strtotime('now');

				$token = md5(uniqid($username));
				$oneTimeToken = md5(uniqid($username . strtotime('now')));
				$sql = "INSERT INTO users (providers_id,providers_token,oauth_provider,onetimetoken,token,username,email,password,name,lastname,avatar_url,bio,locale,created_at) VALUES ('{$id}','{$accessToken}','github','{$oneTimeToken}','{$token}','','','','{$name}','','{$avatar_url}','{$bio}','{$location}','{$created_at}')";
				$query = mysqli_query($conn,$sql) or die(mysqli_error($conn));
				$slt = "SELECT * FROM users WHERE providers_token='{$accessToken}'";
				$sltQuery = mysqli_query($conn,$slt);
				if (mysqli_num_rows($sltQuery)>0) {
					$output = mysqli_fetch_assoc($sltQuery);
					// header("Location: " . 'https://front-end.../?oneTimeToken=' . $output['onetimetoken']); // FRONT-END GA QAYTA YO'NALTIRISH
				}else{
					$output = ['ok'=>false,'message'=>'erRor'];
				}
			}
		}
	}else if(isset($_GET['code'])){
		if (!$_GET['state'] || $_SESSION['state'] != $_GET['state']) {
			header("Location: " . $_SERVER['PHP_SELF']);
		}
		$accessToken = $gitClient->getAccessToken($_GET['state'], $_GET['code']);
		$_SESSION['acccess_token'] = $accessToken;
		header('Location: ./');
	}else{
		$_SESSION['state'] = hash('sha256', microtime(True) . rand() . $_SERVER['REMOTE_ADDR']);
		unset($_SESSION['acccess_token']);
		$authUrl = $gitClient->getAuthorizeURL($_SESSION['state']);
		$output = '<a href="' . htmlspecialchars($authUrl) . '">Login with github</a>';
		header("Location: " . $authUrl);
	}
	echo json_encode($output);
?>