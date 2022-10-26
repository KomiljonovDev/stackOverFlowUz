<?php

	require_once './config/config.php';
	require_once './helpers/functions.php';

	class Post extends db_mysqli
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

		public function getPostsByBetween()
		{
			$from = isset($this->from) ? $this->from : 1; 
			$to = isset($this->to) ? $this->to : 10; 
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'posts gived successfully';
			$posts = $this->selectWhere('posts',[
				[
					'id'=>$from,
					'cn'=>'>='
				],
				[
					'id'=>$to,
					'cn'=>'<='
				],
			]);
			foreach ($posts as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function getPostsBySeenCount()
		{
			$limit = isset($this->limit) ? $this->limit : 10; 
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'posts gived successfully';
			$posts = $this->selectWhere('posts',[
				[
					'id'=>1,
					'cn'=>'>='
				],
			], " ORDER BY viewed_count DESC LIMIT " . $limit);
			foreach ($posts as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function getPostById()
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
				$this->data['ok'] = true;	
				$this->data['code'] = 201;	
				$this->data['message'] = 'post gived successfully';
				foreach ($post as $key => $value) $this->data['result'][$key] = $value;
				return $this->data;
			}else{
				$this->data['code'] = 401;	
				$this->data['message'] = 'post id is invalid';
				return $this->data;
			}
		}

		public function insertPost()
		{

			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			if (!isManager($this->token)) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			if (!isset($this->title) || !isset($this->body)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'title and body are required';
				return $this->data;
			}
			$this->insertInto('posts',[
				'title'=>$this->title,
				'body'=>$this->body,
				'viewed_count'=>1,
				'created_at'=>strtotime('now'),
				'updated_at'=>''
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'post successfully inserted';
			foreach ($this->selectAll('posts') as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function updatePost()
		{
			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			if (!isManager($this->token)) {
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
			if (!isManager($this->token)) {
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