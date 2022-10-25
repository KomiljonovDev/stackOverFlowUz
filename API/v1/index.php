<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json'); 
	$data = [];
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		require_once 'helpers/Post.php';
		require_once 'helpers/Comment.php';
		$db = new db_mysqli;
		$db->dbConnect();

		$post = new Post;
		$comment = new Comment;

		$action = strtolower(trim(getenv('ORIG_PATH_INFO') ? : getenv('PATH_INFO'), '/'));
		if ($action == 'insertpost') {
			$data = $post->insertPost();
		}else if($action == 'getposts'){
			$data = $post->getAllPost();
		}else if($action == 'getpostsbybetween'){
			$data = $post->getPostsByBetween();
		}else if($action == 'getpostsbyseencount'){
			$data = $post->getPostsBySeenCount();
		}else if($action == 'getpostbyid'){
			$data = $post->getPostById();
		}else if($action == 'updatepost'){
			$data = $post->updatePost();
		}else if($action == 'deletepost'){
			$data = $post->deletePost();
		}else if($action == 'viewpost'){
			$data = $post->viewPost();
		}else if($action == 'insertcomment'){
			$data = $comment->insertComment();
		}else if($action == 'getcommentsbyid'){
			$data = $comment->getCommentsById();
		}else if($action == 'getcommentsbybetween'){
			$data = $comment->getCommentsByBetween();
		}else if($action == 'updatecomment'){
			$data = $comment->updateComment();
		}else if($action == 'deletecomment'){
			$data = $comment->deleteComment();
		}else if(false){

		}else{
			$data['ok'] = false;
			$data['code'] = 404;
			$data['message'] = 'Method not found';
		}
	}else{
		$data['ok'] = false;
		$data['code'] = 405;
		$data['message'] = 'Method not allowed. Allowed Method: POST';
	}
		echo json_encode($data,JSON_PRETTY_PRINT);
?>