<?php

	class User extends db_mysqli
	{
		use Helper;

		public function __construct()
		{
			$this->dbConnect();
			$this->extract($_REQUEST);
			$this->help();
		}

		public function signUpWithEmail()
		{

			if (!isset($this->username)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'username is required';
				return $this->data;
			}
			if (!preg_match('/^[a-z\d_]{5,20}$/i', $this->username)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'username is invalid';
				return $this->data;
			}
			$this->username = trim($this->username);
			$user_name = $this->selectWhere('users',[
				[
					'username'=>$this->username,
					'cn'=>'='
				]
			]);
			if ($user_name->num_rows) {
				$this->data['code'] = 403;
				$this->data['message'] = 'username already exists';
				return $this->data;
			}

			if (!isset($this->email)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'email is required';
				return $this->data;
			}
			if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'email is invalid';
				return $this->data;
			}
			$this->email = trim($this->email);
			$user_email = $this->selectWhere('users',[
				[
					'email'=>$this->email,
					'cn'=>'='
				]
			]);
			if ($user_email->num_rows) {
				$this->data['code'] = 403;
				$this->data['message'] = 'email already exists';
				return $this->data;
			}

			if (!isset($this->password)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'password is required';
				return $this->data;
			}

			if (!$this->checkPassword($this->password,[])['ok']) {
				return $this->data;
			}

			if (!isset($this->name) || !isset($this->lastname)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'name and lastname are required';
				return $this->data;
			}
			$this->insertInto('users',[
				'token'=>md5(uniqid($this->username,true)),
				'username'=>$this->username,
				'email'=>$this->email,
				'password'=>md5($this->password),
				'name'=>$this->name,
				'lastname'=>$this->lastname,
				'bio'=>isset($this->lastname) ? $this->lastname : '',
				'locale'=>isset($this->location) ? $this->location : '',
				'created_at'=>strtotime('now'),
				'updated_at'=>''
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'registered successfully';
			$user = $this->selectWhere('users',[
				[
					'username'=>$this->username,
					'cn'=>'='
				],
				[
					'password'=>md5($this->password),
					'cn'=>'='
				]
			]);
			$user = mysqli_fetch_assoc($user);
			foreach ($user as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function updateUserProfile()
		{
			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			if (!$this->isUser($this->token)) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			$user = $this->selectWhere('users',[
				[
					'token'=>$this->token,
					'cn'=>'='
				],
			]);
			if ($user->num_rows) {
				if (isset($this->username)) {
					if (!preg_match('/^[a-z\d_]{5,20}$/i', $this->username)) {
						$this->data['code'] = 400;	
						$this->data['message'] = 'username is invalid';
						return $this->data;
					}
				}
				if (isset($this->email)) {
					if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
						$this->data['code'] = 400;	
						$this->data['message'] = 'email is invalid';
						return $this->data;
					}
				}
				$user = mysqli_fetch_assoc($user);
				$this->username = isset($this->username) ? $this->username : $user['username'];
				$this->email = isset($this->email) ? $this->email : $user['email'];
				$this->name = isset($this->name) ? $this->name : $user['name'];
				$this->lastname = isset($this->lastname) ? $this->lastname : $user['lastname'];
				$this->avatar_url = $user['avatar_url'];
				$this->bio = isset($this->bio) ? $this->bio : $user['bio'];
				$this->locale = isset($this->locale) ? $this->locale : $user['locale'];
				$username = $this->selectWhere('users',[
					[
						'username'=>$this->username,
						'cn'=>'='
					],
				], " AND token!='" . $this->token . "'");
				if (!$username->num_rows) {
					$email = $this->selectWhere('users',[
						[
							'email'=>$this->email,
							'cn'=>'='
						],
					], " AND token!='" . $this->token . "'");
					if (!$email->num_rows) {
						if (isset($_FILES['avatar'])) {
							$allowed = ['jpg','png','jpeg','gif'];
							$pathinfo = pathinfo($_FILES['avatar']['name'],PATHINFO_EXTENSION);
							if (!in_array($pathinfo, $allowed)) {
								$this->data['code'] = 401;	
								$this->data['message'] = 'file type is invalid, allowed file types: jpg,png,jpeg,gif';
								return $this->data;
							}
							$filename = md5(uniqid($_FILES['avatar']['name'],true)) . '.' . $pathinfo;
							move_uploaded_file($_FILES['avatar']['tmp_name'], '../uploads/user/' . $filename);
							$this->avatar_url = $this->projectApiPATH . 'uploads/user/' . $filename;
							$oldFile = explode("uploads/user/", $user['avatar_url'])[1];
							if (file_exists('../uploads/user/' . $oldFile)) {
								unlink('../uploads/user/' . $oldFile);
							}
						}
						$this->update('users',[
							'username'=>$this->username,
							'email'=>$this->email,
							'name'=>$this->name,
							'lastname'=>$this->lastname,
							'avatar_url'=>$this->avatar_url,
							'bio'=>$this->bio,
							'locale'=>$this->locale,
							'updated_at'=>strtotime('now')
						],[
							'token'=>$this->token,
							'cn'=>'='
						]);
						$this->data['ok'] = true;	
						$this->data['code'] = 200;	
						$this->data['message'] = 'user successfully updated';
						$user = mysqli_fetch_assoc($this->selectWhere('users',[
							[
								'token'=>$this->token,
								'cn'=>'='
							],
						]));
						foreach ($user as $key => $value) $this->data['result'][$key] = $value;
						return $this->data;
					}else{
						$this->data['code'] = 403;
						$this->data['message'] = 'email is not available';
						return $this->data;
					}
				}else{
					$this->data['code'] = 403;
					$this->data['message'] = 'username is not available';
					return $this->data;
				}
			}else{
				$this->data['code'] = 401;	
				$this->data['message'] = 'user token is invalid';
				return $this->data;
			}
		}

		public function updateUserPassword()
		{
			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			if (!$this->isUser($this->token)) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			if (!isset($this->currentpassword)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'currentpassword is required';
				return $this->data;
			}
			if (!isset($this->newpassword)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'newpassword is required';
				return $this->data;
			}
			if (!$this->checkPassword($this->newpassword,[])['ok']) {
				return $this->data;
			}
			$user = $this->selectWhere('users',[
				[
					'token'=>$this->token,
					'cn'=>'='
				],
			]);
			if ($user->num_rows) {
				$user = mysqli_fetch_assoc($user);
				if ($user['password'] == md5($this->currentpassword)) {
					$this->update('users',[
						'password'=>md5($this->newpassword),
						'updated_at'=>strtotime('now')
					],[
						'token'=>$this->token,
						'cn'=>'='
					]);
					$user = $this->selectWhere('users',[
						[
							'token'=>$this->token,
							'cn'=>'='
						],
					]);
					$user = mysqli_fetch_assoc($user);
					$this->data['ok'] = true;	
					$this->data['code'] = 200;	
					$this->data['message'] = 'user password successfully updated';
					foreach ($user as $key => $value) $this->data['result'][$key] = $value;
					return $this->data;
				}
				$this->data['code'] = 401;	
				$this->data['message'] = 'currentpassword is invalid';
				return $this->data;
			}
			$this->data['code'] = 401;	
			$this->data['message'] = 'user token is invalid';
			return $this->data;
		}

		public function logInWithEmail()
		{
			if (!isset($this->username) && !isset($this->email)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'username or email is required';
				return $this->data;
			}
			if (!isset($this->password)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'password is required';
				return $this->data;
			}
			$key = isset($this->email) ? 'email' : 'username';
			$user = $this->selectWhere('users',[
				[
					$key=>isset($this->email) ? $this->email : $this->username,
					'cn'=>'='
				],
				[
					'password'=>md5($this->password),
					'cn'=>'='
				]
			]);
			if ($user->num_rows) {
				$user = mysqli_fetch_assoc($user);
				if ($user['password'] === md5($this->password)) {
					$this->data['ok'] = true;
					$this->data['code'] = 200;
					$this->data['message'] = 'login successfully';
					foreach ($user as $key => $value) $this->data['result'][$key] = $value;
					return $this->data;
				}
				$this->data['code'] = 401;
				$this->data['message'] = 'password is invalid';
				return $this->data;
			}
			$this->data['code'] = 401;
			$this->data['message'] = 'username or password are invalid';
			return $this->data;
		}

		public function deleteUserAccount()
		{
			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			if (!$this->isUser($this->token)) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($this->selectWhere('users',[
				[
					'token'=>$this->token,
					'cn'=>'='
				]
			]));
			$avatar = explode("uploads/user/", $user['avatar_url'])[1];
			if (file_exists('../uploads/user/' . $avatar)) {
				unlink('../uploads/user/' . $avatar);
			}
			$this->delete('users',[
				[
					'token'=>$this->token,
					'cn'=>'='
				],
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 200;	
			$this->data['message'] = 'user account successfully deleted';
			return $this->data;
		}
	}


?>