<?php
	
	/**
	 *  Login with github class
	 */
	class githubOauthClient {
		public $authorizeURL = "https://github.com/login/oauth/authorize";
		public $tokenURL = "https://github.com/login/oauth/access_token";
		public $apiURLBase = "https://api.github.com";
		public $clientId;
		public $clientSecret;
		public $redirectUri;

		public function __construct($config=[])
		{
			$this->clientId = isset($config['client_id']) ? $config['client_id'] : '';
			if (!$this->clientId) {
				die("client_id is required");
			}
			$this->clientSecret = isset($config['client_secret']) ? $config['client_secret'] : '';
			if (!$this->clientSecret) {
				die("client_secret is required");
			}
			$this->redirectUri = isset($config['redirect_uri']) ? $config['redirect_uri'] : '';
		}
		public function getAuthorizeURL($state)
		{
			return $this->authorizeURL . '?' . http_build_query([
				'client_id'=>$this->clientId,
				'redirect_uri'=>$this->redirectUri,
				'state'=>$state,
				'scope'=>'user:email'
			]);
		}

		// get access token

		public function getAccessToken($state,$outh_code)
		{
			$token = self::apiRequest($this->tokenURL . '?' . http_build_query([
				'client_id'=>$this->clientId,
				'client_secret'=>$this->clientSecret,
				'state'=>$state,
				'code'=>$outh_code
			]));
			return $token->access_token;
		}

		// API request

		public function apiRequest($access_token_url)
		{
			$apiURL = filter_var($access_token_url,FILTER_VALIDATE_URL) ? $access_token_url : $this->apiURLBase . 'user?access_token=' . $access_token;
			$context = stream_context_create([
				'http'=>[
					'user_agent'=>'KomiljonovDev GitHub OAuth Login',
					'header'=>'Accept: application/json'
				]
			]);
			$response = file_get_contents($apiURL,false,$context);
			return $response ? json_decode($response) : $response;
		}


		// Get autenticated user

		public function getAuthenticatedUser($access_token)
		{
			$apiURL = $this->apiURLBase . '/user';

			$ch = curl_init();
			curl_setopt($ch, CURLOPT_URL, $apiURL);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Authorization: token ' . $access_token));
			curl_setopt($ch, CURLOPT_USERAGENT, 'KomiljonovDev GitHub OAuth Login');
			curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');
			$api_response = curl_exec($ch);
			$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
			if ($http_code != 200) {
				if (curl_errno($ch)) {
					$error_msg = curl_error($ch);
				}else{
					$error_msg = $api_response;
				}
				// throw new Exception('Error: '. $http_code . ': ' . $error_msg);
				echo $error_msg;
			}else{
				return json_decode($api_response);
			}
		}

	}


?>