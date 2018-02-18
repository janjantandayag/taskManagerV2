<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('connection.php');
require_once('helpers.php');

// check if assign to task
function isAssignedToTask($id,$dealgroup_id){
	GLOBAL $connection;
	$id = removeSpecialChars($id);
	$dealgroup_id = removeSpecialChars($dealgroup_id);

	$sql = "SELECT * FROM tasks
			WHERE tasks.document_id = $id
			AND tasks.dealgroup_id = $dealgroup_id
	";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	$status = [];

	if($query->num_rows > 0){
		$status['count'] = $query->num_rows;
		$status['message'] = true;
	} else {
		$status['message'] = false;
	}

	return $status;
}
// GET ALL DOCUMENTS
function getAllDocuments(){
	GLOBAL $connection;
	$sql = "SELECT *
			FROM documents,users
			WHERE users.user_id = documents.created_by
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}
// Get document details
function getDocumentDetails($id){
	GLOBAL $connection;
	$id = removeSpecialChars($id);

	$sql = "SELECT *
			FROM documents,users
			WHERE documents.document_id = $id
			AND users.user_id = documents.created_by
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return mysqli_fetch_assoc($query);
}
// Add document 
function addDocument(){
	GLOBAL $connection;	
	$_SESSION['action_success'] = "";
	$_SESSION['action_error'] = "";

	$document_name = removeSpecialChars(strtolower($_POST['document_name']));
	$document_link = removeSpecialChars($_POST['document_link']);
	$effective_date = $_POST['effective_date'];
	$obscelence_date = $_POST['obscelence_date'];
	$type = removeSpecialChars(strtolower($_POST['type']));
	$date_created = $_POST['date_created'];
	$created_by = $_SESSION['user_id'];
	$document_description = removeSpecialChars(strtolower($_POST['document_description']));


	$sql = "INSERT INTO documents (created_by,document_name,document_link,effective_date,obscelence_date,type,date_created,document_description)
			VALUES ($created_by,'$document_name','$document_link','$effective_date','$obscelence_date','$type','$date_created','$document_description')
	";

	mysqli_query($connection,$sql) or die(mysqli_error($connection));


	if(empty($_SESSION['action_error'])){
		$_SESSION['action_success'] .= "Document successfully added!";
		unset($_SESSION['action_error']);
		header("Location: ../documents.php");
	} else {
		$_SESSION['action_error'] .= "Error on add!";		
		unset($_SESSION['action_success']);
		header("Location: ../documents_add.php");
	}
}
// Update document
function updateDocument(){
	GLOBAL $connection;	
	$_SESSION['action_success'] = "";
	$_SESSION['action_error'] = "";

	$document_name = removeSpecialChars(strtolower($_POST['document_name']));
	$document_link = removeSpecialChars($_POST['document_link']);
	$effective_date = $_POST['effective_date'];
	$obscelence_date = $_POST['obscelence_date'];
	$type = removeSpecialChars(strtolower($_POST['type']));
	$date_created = $_POST['date_created'];
	$created_by = removeSpecialChars($_POST['created_by']);
	$document_id = removeSpecialChars($_POST['document_id']);
	$document_description = removeSpecialChars(strtolower($_POST['document_description']));

	$sql = "UPDATE documents
			SET created_by = $created_by, document_name = '$document_name', document_link = '$document_link',
			    effective_date = '$effective_date', obscelence_date = '$obscelence_date', type = '$type', date_created = '$date_created',
			    document_description = '$document_description'
			WHERE
				document_id = $document_id
	";

	mysqli_query($connection,$sql) or die(mysqli_error($connection));


	if(empty($_SESSION['action_error'])){
		$_SESSION['action_success'] .= "Document successfully updated!";
		unset($_SESSION['action_error']);
	} else {
		$_SESSION['action_error'] .= "Error on add!";		
		unset($_SESSION['action_success']);
	}

	header("Location: ../documents_update.php?document_id=$document_id");
}
// delete document
function deleteDocument(){
	GLOBAL $connection;
	$id = removeSpecialChars($_POST['document_id']);

	$sql = "SELECT * FROM tasks
			WHERE tasks.document_id = $id
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	if($query->num_rows > 0){
		$count = $query->num_rows;
		$append_s = $count > 1 ? 's' : '';
		$message = "Document is assigned to a <strong>{$count} task{$append_s}</strong>!";
		echo json_encode([
			'status' => 'error',
			'message' => $message
		]);
		die;
	} else {
		$sql = "DELETE FROM dealgroup_document
				WHERE dealgroup_document.document_id = $id";
		$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

		$sql = "DELETE FROM documents
				WHERE documents.document_id = $id";
		$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

		echo json_encode([
			'status' => 'success',
			'message' => 'Document successfully deleted!',
			'after_action' => [
				'action' => 'delete',
				'target' => '#row_document_' . $id
			]
		]);
	}	
	die();
}

if($_POST) {

	if(isset($_POST['add_document'])){
		addDocument();
	}

	if(isset($_POST['edit_document'])){
		updateDocument();
	}

	if(isset($_POST['delete_document'])){
		deleteDocument();
	}
}

