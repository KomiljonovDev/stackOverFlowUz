<?php

	require_once './config/config.php';
	require_once './helpers/functions.php';

	class Comment extends db_mysqli
	{
		use Helper;

		public function __construct()
		{
			$this->dbConnect();
			$this->extract($_REQUEST);
		}

		public function getCommentsById()
		{
			if (!isset($this->post_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'post id (post_id) is required';
				return $this->data;
			}
			$post = $this->selectWhere('post_comments',[
				[
					'post_id'=>$this->post_id,
					'cn'=>'='
				],
			]);
			if ($post->num_rows) {
				$this->data['ok'] = true;	
				$this->data['code'] = 201;	
				$this->data['message'] = 'Post Comments gived successfully';
				foreach ($post as $key => $value) $this->data['result'][$key] = $value;
				return $this->data;
			}else{
				$this->data['code'] = 401;	
				$this->data['message'] = 'post_id is invalid';
				return $this->data;
			}
		}

		public function getCommentsByBetween()
		{
			if (!isset($this->post_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'post id (post_id) is required';
				return $this->data;
			}
			$from = isset($this->from) ? $this->from : 1; 
			$to = isset($this->to) ? $this->to : 10; 
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'Comments gived successfully';
			$post_comments = $this->selectWhere('post_comments',[
				[
					'id'=>$from,
					'cn'=>'>='
				],
				[
					'id'=>$to,
					'cn'=>'<='
				],
				[
					'post_id'=>$this->post_id,
					'cn'=>'<='
				],
			]);
			foreach ($post_comments as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function insertComment()
		{

			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			$user = $this->selectWhere('users',[
				[
					'token'=>$this->token,
					'cn'=>'='
				]
			]);
			if (!$user->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			if (!isset($this->post_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'post id (post_id) is required';
				return $this->data;
			}
			$post = $this->selectWhere('posts',[
				[
					'id'=>$this->post_id,
					'cn'=>'='
				]
			]);
			if (!$post->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'post_id is invalid';
				return $this->data;
			}
			if (!isset($this->comment)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'comment body (comment) is required';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($user);
			$this->insertInto('post_comments',[
				'post_id'=>$this->post_id,
				'user_id'=>$user['id'],
				'comment'=>$this->comment,
				'created_at'=>strtotime('now'),
				'updated_at'=>''
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'comment successfully inserted';
			$post_comments = $this->selectWhere('post_comments',[
				[
					'post_id'=>$this->post_id,
					'cn'=>'='
				]
			]);
			foreach ($post_comments as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function updateComment()
		{
			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			$user = $this->selectWhere('users',[
				[
					'token'=>$this->token,
					'cn'=>'='
				]
			]);
			if (!$user->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			if (!isset($this->comment_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'comment id (comment_id) is required';
				return $this->data;
			}
			$comment = $this->selectWhere('post_comments',[
				[
					'id'=>$this->comment_id,
					'cn'=>'='
				]
			]);
			if (!$comment->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'comment_id is invalid';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($user);
			$comment = mysqli_fetch_assoc($comment);
			if ($user['id'] != $comment['user_id']) {
				$this->data['code'] = 403;	
				$this->data['message'] = 'Comment does not belong to this user';
				return $this->data;
			}
			if (!isset($this->comment)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'comment body (comment) is required';
				return $this->data;
			}
			$this->update('post_comments',[
				'comment'=>$this->comment,
				'updated_at'=>strtotime('now'),
			],[
				'id'=>$this->comment_id,
				'cn'=>'='
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'Comment successfully updated';
			$post_comments = $this->selectWhere('post_comments',[
				[
					'post_id'=>$comment['post_id'],
					'cn'=>'='
				]
			]);
			foreach ($post_comments as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function deleteComment()
		{
			if (!isset($this->token)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'token is required';
				return $this->data;
			}
			$user = $this->selectWhere('users',[
				[
					'token'=>$this->token,
					'cn'=>'='
				]
			]);
			if (!$user->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'token is invalid';
				return $this->data;
			}
			if (!isset($this->comment_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'comment id (comment_id) is required';
				return $this->data;
			}
			$comment = $this->selectWhere('post_comments',[
				[
					'id'=>$this->comment_id,
					'cn'=>'='
				]
			]);
			if (!$comment->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'comment_id is invalid';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($user);
			$comment = mysqli_fetch_assoc($comment);
			if ($user['id'] != $comment['user_id']) {
				$this->data['code'] = 403;	
				$this->data['message'] = 'Comment does not belong to this user';
				return $this->data;
			}
			$this->delete('post_comments',[
				[
					'id'=>$this->comment_id,
					'cn'=>'='
				],
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 200;	
			$this->data['message'] = 'comment successfully deleted';
			$post_comments = $this->selectWhere('post_comments',[
				[
					'post_id'=>$comment['post_id'],
					'cn'=>'='
				]
			]);
			foreach ($post_comments as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}
	}


?>