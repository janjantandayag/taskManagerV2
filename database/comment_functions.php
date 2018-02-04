<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('connection.php');

function postComment(){
	GLOBAL $connection;

	$comment = nl2br(htmlentities($_POST['comment'], ENT_QUOTES, 'UTF-8'));
	$type = $_POST['type'];
	$author_id = $_SESSION['user_id'];
	$id = ($type==='task') ? 't_' . $_POST['id'] : 'b_' . $_POST['id'];

	if(empty($comment)){
		echo json_encode([
			'status' => 'error',
			'message' => 'Comment is empty!'
		]);
	} else {
		$sql = "INSERT INTO comments(comment,comment_author,commented_date,status,id)
				VALUES('$comment','$author_id',NOW(),'Published','$id')";

		$query = mysqli_query($connection,$sql) or die(json_encode([
			'status' => 'error',
			'message' => mysqli_error($connection)
		]));

		echo json_encode([
			'status' => 'success',
			'message' => 'Comment published!'
		]);
	}	
}

if($_POST){
	if($_POST['add_comment']){
		postComment();
	}
}