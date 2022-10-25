<?php

	require_once './config/config.php';
	require_once 'functions.php';
	trait Helper
	{
		public $data;

		public function Help()
		{
			$this->data = array();
			$this->data['ok'] = false; 
			$this->data['code'] = null; 
			$this->data['message'] = "message"; 
			$this->data['result'] = []; 
		}

		public function extract($requests)
		{
			foreach ($requests as $key => $value) {
				$this->$key = $value;
			}
		}
	}

	class Post extends db_mysqli
	{
		use Helper;

		public $data;

		public $post_id;
		public $title;
		public $body;


		public function getAllPost()
		{
			return $this->selectAll('posts');
		}

		public function getPostsByBetween($start=1,$end=10)
		{
			return $this->selectWhere('posts',[
				[
					'id'=>$start,
					'cn'=>'>='
				],
				[
					'id'=>$end,
					'cn'=>'<='
				],
			]);
		}

		public function insertPost()
		{
			if (!$this->token) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			if (isManager($this->$token)) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			if (!$this->title || !$this->body) {
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
			return $this->getAllPost();
		}

	}


?>