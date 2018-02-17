<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('connection.php');
require_once('helpers.php');

// get all vacation requests
function getVacationRequests() {
	GLOBAL $connection;

	$sql = "SELECT *
			FROM vacations
			WHERE vacations.status <> 'DELETED'
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}
// get all pending for approval
function getPendingVacationRequests() {
	GLOBAL $connection;

	$sql = "SELECT *
			FROM vacations
			WHERE vacations.status = 'PENDING'
			ORDER BY vacations.start_date ASC
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}
// get my vacation requests
function getMyVacationRequests($user_id) {
	GLOBAL $connection;

	$sql = "SELECT *
			FROM vacations
			WHERE vacations.requester_id = $user_id
			AND vacations.status <> 'DELETED'
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}
// send vacation request
function sendRequest(){
	date_default_timezone_set("Asia/Manila");
	$_SESSION['action_success'] = "";
	GLOBAL $connection;

	$title = removeSpecialChars(strtolower($_POST['vacation_title']));
	$type = removeSpecialChars(strtolower($_POST['vacation_type']));
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$description = removeSpecialChars(strtolower($_POST['description']));
	$requested_by = $_SESSION['user_id'];
	$status = 'PENDING';
	$submitted_date = date('Y-m-d H:i:s');

	$sql = "INSERT INTO vacations(requester_id,title,type,start_date,end_date,description,status,submitted_date)
			VALUES($requested_by,'$title','$type','$start_date','$end_date','$description','$status','$submitted_date')
	";

	mysqli_query($connection,$sql) or die(mysqli_error($connection));
	$id = mysqli_insert_id($connection);

	$_SESSION['action_success'] = "Request successfully sent!";
	header("Location: ../vacations_view.php?vacation_id=$id");
}
// get vacation detail
function getVacationDetails($id){
	GLOBAL $connection;
	$id = removeSpecialChars($id);

	$sql = "SELECT * FROM vacations
			WHERE vacations.vacation_id = '$id'
			AND vacations.status <> 'DELETED'
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return mysqli_fetch_assoc($query);
}
// approve vacation request
function confirmVacationRequest($status){
	date_default_timezone_set("Asia/Manila");
	GLOBAL $connection;

	$v_id = removeSpecialChars($_POST['v_id']);
	$approved_by = $_SESSION['user_id'];
	$approved_date = date('Y-m-d H:i:s');

	$sql = "UPDATE vacations
			SET vacations.approved_by = $approved_by,
				vacations.approved_date = '$approved_date',
				vacations.status = '$status'
			WHERE
				vacations.vacation_id = $v_id
	";

	mysqli_query($connection,$sql) or die(json_encode([
		'status' => 'error',
		'message' => mysqli_error($connection)
	]));


	echo json_encode([
		'status' => 'success',
		'message' => 'Vacation request successfully ' . strtolower($status) . '!',
		'after_action' => [
			'action' => 'redirect',
			'ref' => 'vacations_pending.php'
		]
	]);
}
// update requets
function updateRequest(){
	date_default_timezone_set("Asia/Manila");
	$_SESSION['action_success'] = "";
	GLOBAL $connection;

	$title = removeSpecialChars(strtolower($_POST['vacation_title']));
	$type = removeSpecialChars(strtolower($_POST['vacation_type']));
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$description = removeSpecialChars(strtolower($_POST['description']));
	$vacation_id = removeSpecialChars($_POST['vacation_id']);

	$sql = "UPDATE vacations 
			SET 
				title = '$title', type = '$type', start_date = '$start_date', end_date = '$end_date', description = '$description'
			WHERE
				vacations.vacation_id = $vacation_id
	";

	mysqli_query($connection,$sql) or die(mysqli_error($connection));

	$_SESSION['action_success'] = "Request successfully updated!";
	header("Location: ../vacations_update.php?vacation_id=$vacation_id");
}
// delete vacation request
function deleteVacation(){
	GLOBAL $connection;

	$vacation_id = removeSpecialChars($_POST['vacation_id']);

	$sql = "UPDATE vacations 
			SET 
				vacations.status = 'DELETED'
			WHERE
				vacations.vacation_id = '$vacation_id'
	";

	mysqli_query($connection,$sql) or die(json_encode([
		'status' => 'error',
		'message' => mysqli_error($connection)
	]));

	echo json_encode([
		'status' => 'success',
		'message' => 'Vacation request successfully deleted!',
		'after_action' => [
			'action' => 'redirect',
			'ref' => 'vacations.php'
		]
	]);
}


if($_POST) {
	if(isset($_POST['send_request'])){
		sendRequest();
	}

	if(isset($_POST['update_request'])){
		updateRequest();
	}

	if(isset($_POST['approveVacationRequest'])){
		confirmVacationRequest('APPROVED');
	}

	if(isset($_POST['rejectVacationRequest'])){
		confirmVacationRequest('REJECTED');
	}

	if(isset($_POST['delete_vacation'])){
		deleteVacation();
	}
}