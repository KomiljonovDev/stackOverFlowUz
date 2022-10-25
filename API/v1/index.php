<?php
	ini_set('display_errors', '1');
	ini_set('display_startup_errors', '1');
	error_reporting(E_ALL);

	header('Access-Control-Allow-Origin: *');
	header('Content-Type: application/json'); 
	$data = [];
	if ($_SERVER['REQUEST_METHOD'] === 'POST') {
		require_once 'helpers/classes.php';
		$db = new db_mysqli;
		$db->dbConnect();

		$post = new Post;

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