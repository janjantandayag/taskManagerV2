<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($connection)){
	include('connection.php');
}

// GET ALL TASKS
function getAllTasks($user_id = '', $dealgroup_id = ''){	
	GLOBAL $connection;

	$andWhere = " tasks.dealgroup_id = deal_groups.dealgroup_id 
			AND tasks.document_id = documents.document_id 
			ORDER BY tasks.due_date DESC";

	if (!empty($user_id) && !empty($dealgroup_id)){
		$sql = "SELECT * FROM tasks,deal_groups,documents 
				WHERE tasks.dealgroup_id = $dealgroup_id AND ";
		$sql.= $andWhere;
	} elseif (!empty($user_id) && empty($dealgroup_id)){		
		$sql = "SELECT *
			FROM positions,role_position,dealgroup_staffing,deal_groups,tasks,documents
			WHERE positions.position_title = 'Portfolio Management Analyst' 
			AND role_position.user_id = $user_id
			AND positions.position_id = role_position.position_id
			AND role_position.role_position_id = dealgroup_staffing.role_position_id
			AND dealgroup_staffing.dealgroup_id = deal_groups.dealgroup_id AND
		";
		$sql.= $andWhere;
	} else {
		$sql = "SELECT * FROM tasks,deal_groups,documents WHERE ";
		$sql .= $andWhere;
	}

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}

function getTaskDetails($task_id){
	GLOBAL $connection;
	$sql = "SELECT *
			FROM tasks,documents,deal_groups
			WHERE tasks.task_id = '$task_id'
			AND tasks.dealgroup_id = deal_groups.dealgroup_id
			AND tasks.document_id = documents.document_id 
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return mysqli_fetch_assoc($query);
}

function getTaskComments($task_id){
	GLOBAL $connection;
	$task_id = 't_' . $task_id;
	$sql = "SELECT *
			FROM comments,users
			WHERE comments.id = '$task_id'
			AND comments.status = 'Published'
			AND comments.comment_author = users.user_id
			ORDER BY comments.commented_date DESC
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}