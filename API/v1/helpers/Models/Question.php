<?php

	require_once './config/config.php';
	require_once './helpers/functions.php';

	class Question extends db_mysqli
	{
		use Helper;

		public function __construct()
		{
			$this->dbConnect();
			$this->extract($_REQUEST);
		}

		public function getQuestionsByBetween()
		{
			$from = isset($this->from) ? $this->from : 1; 
			$to = isset($this->to) ? $this->to : 10; 
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'Questions gived successfully';
			$questions = $this->selectWhere('questions',[
				[
					'id'=>$from,
					'cn'=>'>='
				],
				[
					'id'=>$to,
					'cn'=>'<='
				],
			]);
			foreach ($questions as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function insertQuestion()
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
			
			if (!isset($this->title) || !isset($this->body) || !isset($this->tags)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'title,body and tags are required';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($user);
			$this->insertInto('questions',[
				'user_id'=>$user['id'],
				'title'=>$this->title,
				'body'=>$this->body,
				'tags'=>$this->tags,
				'created_at'=>strtotime('now'),
				'updated_at'=>''
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'question successfully inserted';
			$user_questions = $this->selectWhere('questions',[
				[
					'user_id'=>$user['id'],
					'cn'=>'='
				]
			]);
			foreach ($user_questions as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function updateQuestion()
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
			if (!isset($this->question_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'question id (question_id) is required';
				return $this->data;
			}
			$question = $this->selectWhere('questions',[
				[
					'id'=>$this->question_id,
					'cn'=>'='
				]
			]);
			if (!$question->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'question_id is invalid';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($user);
			$question = mysqli_fetch_assoc($question);
			if ($user['id'] != $question['user_id']) {
				$this->data['code'] = 403;	
				$this->data['message'] = 'Question does not belong to this user';
				return $this->data;
			}
			$title = isset($this->title) ? $this->title : $question['title'];
			$body = isset($this->body) ? $this->body : $question['body'];
			$tags = isset($this->tags) ? $this->tags : $question['tags'];
			$this->update('questions',[
				'title'=>$title,
				'body'=>$body,
				'tags'=>$tags,
				'updated_at'=>strtotime('now'),
			],[
				'id'=>$this->question_id,
				'cn'=>'='
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'Question successfully updated';
			$user_questions = $this->selectWhere('questions',[
				[
					'user_id'=>$question['user_id'],
					'cn'=>'='
				]
			]);
			foreach ($user_questions as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function deleteQuestion()
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
			if (!isset($this->question_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'question id (question_id) is required';
				return $this->data;
			}
			$question = $this->selectWhere('questions',[
				[
					'id'=>$this->question_id,
					'cn'=>'='
				]
			]);
			if (!$question->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'question_id is invalid';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($user);
			$question = mysqli_fetch_assoc($question);
			if ($user['id'] != $question['user_id']) {
				$this->data['code'] = 403;	
				$this->data['message'] = 'Question does not belong to this user';
				return $this->data;
			}
			$this->delete('questions',[
				[
					'id'=>$this->question_id,
					'cn'=>'='
				],
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 200;	
			$this->data['message'] = 'Question successfully deleted';
			$user_questions = $this->selectWhere('questions',[
				[
					'user_id'=>$question['user_id'],
					'cn'=>'='
				]
			]);
			foreach ($user_questions as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}
	}


?>