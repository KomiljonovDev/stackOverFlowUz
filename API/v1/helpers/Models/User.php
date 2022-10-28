<?php

	class User extends db_mysqli
	{
		use Helper;

		public function __construct()
		{
			$this->dbConnect();
			$this->extract($_REQUEST);
		}

		public function getAllPost()
		{
			$this->data['ok'] = true;	
			$this->data['code'] = 200;	
			$this->data['message'] = 'posts gived successfully';
			foreach ($this->selectAll('posts') as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function signUpWithEmail()
		{

			if (!isset($this->username)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'username is required';
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

		public function updatePost()
		{
			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			if (!$this->isManager($this->token)) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			if (!isset($this->id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'post id (id) is required';
				return $this->data;
			}
			$post = $this->selectWhere('posts',[
				[
					'id'=>$this->id,
					'cn'=>'='
				],
			]);
			if ($post->num_rows) {
				$post = mysqli_fetch_assoc($post);
				$this->title = isset($this->title) ? $this->title : $post['title'];
				$this->body = isset($this->body) ? $this->body : $post['body'];
				$this->update('posts',[
					'title'=>$this->title,
					'body'=>$this->body,
					'updated_at'=>strtotime('now')
				],[
					'id'=>$this->id,
					'cn'=>'='
				]);
				$this->data['ok'] = true;	
				$this->data['code'] = 201;	
				$this->data['message'] = 'post successfully updated';
				foreach ($this->selectAll('posts') as $key => $value) $this->data['result'][$key] = $value;
				return $this->data;
			}else{
				$this->data['code'] = 401;	
				$this->data['message'] = 'post id is invalid';
				return $this->data;
			}
		}

		public function viewPost()
		{
			if (!isset($this->id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'post id (id) is required';
				return $this->data;
			}
			$post = $this->selectWhere('posts',[
				[
					'id'=>$this->id,
					'cn'=>'='
				],
			]);
			if ($post->num_rows) {
				$post = mysqli_fetch_assoc($post);
				$viewed_count = (int)$post['viewed_count'] + 1;
				$this->update('posts',[
					'viewed_count'=>$viewed_count,
				],[
					'id'=>$this->id,
					'cn'=>'='
				]);
				$this->data['ok'] = true;	
				$this->data['code'] = 201;	
				$this->data['message'] = 'post viewed';
				foreach ($this->selectAll('posts') as $key => $value) $this->data['result'][$key] = $value;
				return $this->data;
			}else{
				$this->data['code'] = 401;	
				$this->data['message'] = 'post id is invalid';
				return $this->data;
			}
		}

		public function deletePost()
		{
			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			if (!$this->isManager($this->token)) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			if (!isset($this->id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'post id (id) is required';
				return $this->data;
			}
			$post = $this->selectWhere('posts',[
				[
					'id'=>$this->id,
					'cn'=>'='
				],
			]);
			if ($post->num_rows) {
				$this->delete('posts',[
					[
						'id'=>$this->id,
						'cn'=>'='
					],
				]);
				$this->data['ok'] = true;	
				$this->data['code'] = 200;	
				$this->data['message'] = 'post successfully deleted';
				foreach ($this->selectAll('posts') as $key => $value) $this->data['result'][$key] = $value;
				return $this->data;
			}else{
				$this->data['code'] = 401;	
				$this->data['message'] = 'post id is invalid';
				return $this->data;
			}
		}
	}


?>