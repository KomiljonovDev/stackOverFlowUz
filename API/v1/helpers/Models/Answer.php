<?php

	class Answer extends db_mysqli
	{
		use Helper;

		public function __construct()
		{
			$this->dbConnect();
			$this->extract($_REQUEST);
		}

		public function getAnswersById()
		{
			if (!isset($this->question_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'question id (question_id) is required';
				return $this->data;
			}
			$post = $this->selectWhere('answers',[
				[
					'question_id'=>$this->question_id,
					'cn'=>'='
				],
			]);
			if ($post->num_rows) {
				$this->data['ok'] = true;	
				$this->data['code'] = 201;	
				$this->data['message'] = 'Answers of question gived successfully';
				foreach ($post as $key => $value) $this->data['result'][$key] = $value;
				return $this->data;
			}else{
				$this->data['code'] = 401;	
				$this->data['message'] = 'question_id is invalid';
				return $this->data;
			}
		}

		public function getAnswersByBetween()
		{
			if (!isset($this->question_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'question id (question_id) is required';
				return $this->data;
			}
			$from = isset($this->from) ? $this->from : 1; 
			$to = isset($this->to) ? $this->to : 10; 
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'Answers gived successfully';
			$question_answers = $this->selectWhere('answers',[
				[
					'id'=>$from,
					'cn'=>'>='
				],
				[
					'id'=>$to,
					'cn'=>'<='
				],
				[
					'question_id'=>$this->question_id,
					'cn'=>'<='
				],
			]);
			foreach ($question_answers as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function insertAnswer()
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
			$post = $this->selectWhere('questions',[
				[
					'id'=>$this->question_id,
					'cn'=>'='
				]
			]);
			if (!$post->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'question_id is invalid';
				return $this->data;
			}
			if (!isset($this->title) || !isset($this->body)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'title and body are required';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($user);
			$this->insertInto('answers',[
				'question_id'=>$this->question_id,
				'user_id'=>$user['id'],
				'title'=>$this->title,
				'body'=>$this->body,
				'is_true'=>false,
				'created_at'=>strtotime('now'),
				'updated_at'=>''
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'Answer successfully inserted';
			$question_answers = $this->selectWhere('answers',[
				[
					'question_id'=>$this->question_id,
					'cn'=>'='
				]
			]);
			foreach ($question_answers as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function updateAnswer()
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
			if (!isset($this->answer_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'answer id (answer_id) is required';
				return $this->data;
			}
			$answer = $this->selectWhere('answers',[
				[
					'id'=>$this->answer_id,
					'cn'=>'='
				]
			]);
			if (!$answer->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'answer_id is invalid';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($user);
			$answer = mysqli_fetch_assoc($answer);
			if ($user['id'] != $answer['user_id']) {
				$this->data['code'] = 403;	
				$this->data['message'] = 'Answer does not belong to this user';
				return $this->data;
			}
			$title = isset($this->title) ? $this->title : $answer['title'];
			$body = isset($this->body) ? $this->body : $answer['body'];
			$this->update('answers',[
				'title'=>$title,
				'body'=>$body,
				'updated_at'=>strtotime('now'),
			],[
				'id'=>$this->answer_id,
				'cn'=>'='
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 201;	
			$this->data['message'] = 'Answer successfully updated';
			$question_answers = $this->selectWhere('answers',[
				[
					'question_id'=>$answer['question_id'],
					'cn'=>'='
				]
			]);
			foreach ($question_answers as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}

		public function deleteAnswer()
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
			if (!isset($this->answer_id)) {
				$this->data['code'] = 400;	
				$this->data['message'] = 'answer id (answer_id) is required';
				return $this->data;
			}
			$answer = $this->selectWhere('answers',[
				[
					'id'=>$this->answer_id,
					'cn'=>'='
				]
			]);
			if (!$answer->num_rows) {
				$this->data['code'] = 401;	
				$this->data['message'] = 'answer_id is invalid';
				return $this->data;
			}
			$user = mysqli_fetch_assoc($user);
			$answer = mysqli_fetch_assoc($answer);
			if ($user['id'] != $answer['user_id']) {
				$this->data['code'] = 403;	
				$this->data['message'] = 'Answer does not belong to this user';
				return $this->data;
			}
			$this->delete('answers',[
				[
					'id'=>$this->answer_id,
					'cn'=>'='
				],
			]);
			$this->data['ok'] = true;	
			$this->data['code'] = 200;	
			$this->data['message'] = 'Answer successfully deleted';
			$question_answers = $this->selectWhere('answers',[
				[
					'question_id'=>$answer['question_id'],
					'cn'=>'='
				]
			]);
			foreach ($question_answers as $key => $value) $this->data['result'][$key] = $value;
			return $this->data;
		}
	}


?>