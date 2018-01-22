<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($connection)){
	include('connection.php');
}
date_default_timezone_set('Asia/Manila');
// UPDATE task status
function getStatus($start_date,$due_date){
	$status = '';

	if($start_date > date('Y-m-d')){
		$status = 'UPCOMING';
  	}

  	if( ($start_date <= date('Y-m-d')) && ($due_date >= date('Y-m-d'))){
		$status = 'IN PROGRESS';
  	}

  	if($due_date < date('Y-m-d') ){
		$status = 'PAST DUE';
  	}

  	return $status;
}
// UPDATE task status
function updateStatus(){
	GLOBAL $connection;

	$sql = "SELECT * FROM tasks
			WHERE tasks.status NOT IN ('DELETED','FINISHED')";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	while($task = mysqli_fetch_assoc($query)){
		$status = getStatus($task['start_date'],$task['due_date']);
		$id = $task['task_id'];

		$sql = "UPDATE tasks SET tasks.status = '$status' WHERE tasks.task_id = $id";
		$query_update = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	}
}
// GET ALL TASKS
function getAllTasks($user_id = '', $dealgroup_id = ''){	
	GLOBAL $connection;	
	updateStatus();

	$andWhere = " tasks.dealgroup_id = deal_groups.dealgroup_id 
			AND tasks.document_id = documents.document_id 
			AND tasks.status <> 'DELETED'
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

// GET TASK - IN PROGRESS
function filterTask($filter){
	GLOBAL $connection;	
	updateStatus();

	$sql = "SELECT * FROM tasks,deal_groups,documents WHERE  tasks.dealgroup_id = deal_groups.dealgroup_id 
			AND tasks.document_id = documents.document_id 
			AND tasks.status = '$filter'
			ORDER BY tasks.due_date DESC";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}

// GET TASK DETAILS
function getTaskDetails($task_id){
	GLOBAL $connection;
	$sql = "SELECT *,tasks.type AS task_type
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

// Add Task
function addTask(){
	GLOBAL $connection;
	updateStatus();

	$task_title = $_POST['task_title'];
	$due_date = $_POST['due_date'];
	$start_date = $_POST['start_date'];
	$deal_group = $_POST['deal_group'];
	$document = $_POST['document'];
	$task_ref = $_POST['task_ref'];
	$link_to_support = $_POST['link_to_support'];
	$task_type = $_POST['task_type'];
	$task_language = nl2br(htmlentities($_POST['task_language'], ENT_QUOTES, 'UTF-8'));
	$task_note = nl2br(htmlentities($_POST['task_note'], ENT_QUOTES, 'UTF-8'));

	$query = "INSERT INTO tasks(title,dealgroup_id,document_id,reference,language,due_date,start_date,status,type,note,link_to_support) 
			VALUES ('$task_title','$deal_group','$document','$task_ref','$task_language','$due_date','$start_date','IN PROGRESS','$task_type','$task_note','$link_to_support')";
	mysqli_query($connection, $query) or die(mysqli_error($connection));
	$task_id = mysqli_insert_id($connection);

	header("Location: ../task_view.php?task_id=$task_id");
}

// Edit Task
function editTask(){
	GLOBAL $connection;	

	$task_title = $_POST['task_title'];
	$due_date = $_POST['due_date'];
	$start_date = $_POST['start_date'];
	$deal_group = $_POST['deal_group'];
	$document = $_POST['document'];
	$task_ref = $_POST['task_ref'];
	$link_to_support = $_POST['link_to_support'];
	$task_type = $_POST['task_type'];
	$status = $_POST['task_status'];

	$task_language = nl2br(htmlentities($_POST['task_language'], ENT_QUOTES, 'UTF-8'));
	$task_note = nl2br(htmlentities($_POST['task_note'], ENT_QUOTES, 'UTF-8'));
	$task_id = $_POST['task_id'];

	$query = "UPDATE tasks SET title='$task_title',dealgroup_id='$deal_group',document_id='$document',reference='$task_ref',
			language='$task_language',start_date = '$start_date',due_date='$due_date',type='$task_type',note='$task_note',link_to_support='$link_to_support',
			status = '$status'
			WHERE tasks.task_id=$task_id
	";
	mysqli_query($connection, $query) or die(mysqli_error($connection));

	$_SESSION['update_task_error'] = ' Task successfully updated! ';	
	updateStatus();

	header("Location: ../tasks_update.php?task_id=$task_id");
}

// DELETE A TASK
function deleteTask(){
	GLOBAL $connection;
	$task_id = $_POST['task_id'];

	$query = "UPDATE tasks SET status = 'DELETED'
			WHERE tasks.task_id=$task_id
	";
	mysqli_query($connection, $query) or die(json_encode([
		'status' => 'error',
		'message' => mysqli_error($connection)
	]));

	echo json_encode([
		'status' => 'success',
		'message' => 'Task successfully deleted!'
	]);
}

// SET TO COMPLETE
function setToComplete(){
	// if action == complete
	// SET status to complete
	// else
	// SET status = 'IN PROGRESS' 
	// RUN the update status
}

if($_POST){
	if(isset($_POST['add_task'])){
		addTask();
	}

	if(isset($_POST['edit_task'])){
		editTask();
	}

	if(isset($_POST['delete_task'])){
		deleteTask();
	}
}