<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('connection.php');

// get all assignment 
function getEntityDealGroupAssignment(){
	GLOBAL $connection;

	$sql = "SELECT dealgroup_entity_assignment.entity_id 
			FROM dealgroup_entity_assignment
			GROUP BY dealgroup_entity_assignment.entity_id";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	return $query;
}
// assign dealgroup
function assignEntityDealGroup(){
	GLOBAL $connection;
	$message = [];

	$entity_id = $_POST['entity_id'];
	$dealgroups = isset($_POST['dealgroup_id']) ? $_POST['dealgroup_id'] : [];

	include('entity_functions.php');
	$previousDealGroups = getPreviousDealGroup($entity_id);

	$currentDealGroupIds = [];
	include('dealgroup_functions.php');
	for($i=0;$i<count($dealgroups);$i++){
		if(!empty($dealgroups[$i])) {
			$dealgroup_id = $dealgroups[$i];

			$sql = "SELECT * FROM dealgroup_entity_assignment 
				WHERE dealgroup_entity_assignment.entity_id = $entity_id
				AND dealgroup_entity_assignment.dealgroup_id = $dealgroup_id
			";

			$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

			if(!$query->num_rows > 0){
				$sql_insert = "INSERT INTO dealgroup_entity_assignment(entity_id,dealgroup_id,type,start_date,end_date)
							VALUES ($entity_id,$dealgroup_id,'','','')";

				mysqli_query($connection,$sql_insert) or die(mysqli_error($connection));

				$inserted_id = mysqli_insert_id($connection);
				$currentDealGroupIds[] = $inserted_id;

				$detail = getDealGroupAssignmentDetails($inserted_id);
				$message['success'][] = "<li><strong>" . strtoupper($detail['group_name']) .' (' . strtoupper($detail['code_name']) .')' . "</strong> successfully added!</li>";
			} else {
				$id = mysqli_fetch_assoc($query)['dealgroup_entity_assignment_id'];
				$currentDealGroupIds[] = $id;
			}
		}		
	}

	$ids = array_unique(array_merge($currentDealGroupIds,$previousDealGroups));

	foreach($ids as $id){		
		$detail = getDealGroupAssignmentDetails($id);
		if( in_array($id, $previousDealGroups) && !in_array($id, $currentDealGroupIds) ){
			// add additional if set to a user_position
			if(isAssignedToPosition($detail['dealgroup_id'],$detail['entity_id'])) {				
				$message['error'][] = "<li><strong>" . strtoupper($detail['group_name']) .' (' . strtoupper($detail['code_name']) .')' . " can't be remove! </strong><small> Assigned to a position.</small></li>";
			} else {
				$sql = "DELETE FROM dealgroup_entity_assignment 
						WHERE dealgroup_entity_assignment.dealgroup_entity_assignment_id = $id";
				mysqli_query($connection,$sql);

				$message['success'][] = "<li><strong>" . strtoupper($detail['group_name']) .' (' . strtoupper($detail['code_name']) .')' . "</strong> successfully removed!</li>";
			}
		}
	}


	$key = key($message);

	echo json_encode([
		'status' => 'success',
		'last_prompt' => $key,
		'message' => $message,
		'after_action' => [
			'action' => 'redirect',
			'ref' => 'assignment_dealgroup.php'
		]
	]);
}

// get documents ajax
function getDocumentsAjax(){
	GLOBAL $connection;
	$dealgroup_id = $_POST['id'];

	$sql = "SELECT * FROM dealgroup_document,documents
			WHERE dealgroup_document.dealgroup_id = $dealgroup_id
			AND dealgroup_document.document_id = documents.document_id";

	$query = mysqli_query($connection,$sql) or die(json_encode([
		'status' => 'error',
		'message' => 'Error processing your request!'
	]));

	include('document_functions.php');
	$documents = getAllDocuments();
	$document_options = [];

	while($document = mysqli_fetch_assoc($documents)){
		$name = ucwords($document['document_name']);

		$document_options[] = [
			'id' => $document['document_id'],
			'text' => $name
		];
	}

	$options = [];
	$ids = [];
	while($result = mysqli_fetch_assoc($query)){
		$ids[] = $result['document_id'];
	}

	echo json_encode([
		'status' => 'success',
		'selectedValues' => $ids,
		'options' => $document_options,
		'count' => $query->num_rows,
	]);
	die();
}

if($_POST){
	if(isset($_POST['assignEntityDealGroup'])){
		assignEntityDealGroup();
	}

	if(isset($_POST['get_documents'])){
		getDocumentsAjax();
	}
}