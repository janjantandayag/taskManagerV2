<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($connection)){
	require_once('connection.php');
	require_once('helpers.php');
}
// get form
function getFormDealGroupDocument(){
	include('dealgroup_functions.php');
    $dealgroups_query = getDealGroups();

    $dealgroup_options = "<option selected disabled>Select deal group ...</option>";
    while($dealgroup = mysqli_fetch_assoc($dealgroups_query)) {    	
    	$name = ucwords($dealgroup['group_name'] . ' ( ' . $dealgroup['code_name']. ' )');
    	$dealgroup_options .= "<option value='" . $dealgroup['dealgroup_id'] . "'>" . $name . "</option>";
    }

	$form = <<< FORM
	<form action="#" id="dealgroup_documents_form">
	    <div class="form-group">		    
			<div class="row">
				<div class="col-md-12">
					<div style="margin-bottom:20px">  <label for="dealgroup_id">Deal Group</label>
						<select class="form-control input_required" id="dealgroup_id" required name="dealgroup_id">	
							{$dealgroup_options}        
						</select>
					</div>   
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div style="margin-bottom:20px">  <label for="document_ids">Documents:</label>
						<select class="form-control input_assign_dealgroup" value="[]" multiple="" id="document_ids" disabled placeholder="Select documents ..." required name="document_ids[]">		
						</select>
					</div> 
				</div>
			</div>
		</div>
		<input type="hidden" name="assignDealGroupDocuments">
	  </form> 
FORM;

	echo json_encode([
		'status' => 'success',
		'message' => "{$form}"
	]);
}
// function get details dealgorup-document assignment
function getDealGroupDocumentDetails($id){
	GLOBAL $connection;
	$id = removeSpecialChars($id);

	$sql = "SELECT * FROM dealgroup_document,documents
			WHERE dealgroup_document.dealgroup_document_id = $id
			AND dealgroup_document.document_id = documents.document_id
	";

	$query = mysqli_query($connection, $sql) or die(mysqli_error($connection));
	return mysqli_fetch_assoc($query);
}
// assign documents
function assignDealGroupDocuments(){
	GLOBAL $connection;
	$message = [];

	$dealgroup_id = removeSpecialChars($_POST['dealgroup_id']);
	$document_ids = isset($_POST['document_ids']) ? removeSpecialChars($_POST['document_ids']) : [];

	include('dealgroup_functions.php');
	include('document_functions.php');
	$previousDocumentIds = getPreviousDocuments($dealgroup_id);

	$currentDocumentIds = [];
	for($i=0;$i<count($document_ids);$i++){
		if(!empty($document_ids[$i])) {
			$document_id = removeSpecialChars($document_ids[$i]);

			$sql = "SELECT * FROM dealgroup_document 
				WHERE dealgroup_document.document_id = $document_id
				AND dealgroup_document.dealgroup_id = $dealgroup_id
			";

			$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

			if(!$query->num_rows > 0){
				$sql_insert = "INSERT INTO dealgroup_document(document_id,dealgroup_id)
							VALUES ($document_id,$dealgroup_id)";

				mysqli_query($connection,$sql_insert) or die(mysqli_error($connection));

				$inserted_id = mysqli_insert_id($connection);
				$currentDocumentIds[] = $inserted_id;

				$detail = getDealGroupDocumentDetails($inserted_id);
				$message['success'][] = "<li><strong>" . ucwords($detail['document_name']) . "</strong> successfully added!</li>";
			} else {
				$id = mysqli_fetch_assoc($query)['dealgroup_document_id'];
				$currentDocumentIds[] = $id;
			}
		}
	}

	$ids = array_unique(array_merge($currentDocumentIds,$previousDocumentIds));

	foreach($ids as $id){		
		$detail = getDealGroupDocumentDetails($id);
		if( in_array($id, $previousDocumentIds) && !in_array($id, $currentDocumentIds) ){
			$is_assigned = isAssignedToTask($detail['document_id'],$detail['dealgroup_id']);
			if($is_assigned['message']){
				$message['error'][] = "<li><strong>" . ucwords($detail['document_name']) . " </strong> can't be remove! <small> Assigned to a <strong>" . $is_assigned['count'] ." task</strong></small></li>";
			} else {
				$sql = "DELETE FROM dealgroup_document
						WHERE dealgroup_document.dealgroup_document_id = $id";
				
				$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
				$message['success'][] = "<li><strong>" . ucwords($detail['document_name']) . "</strong> successfully removed!</li>";
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
			'ref' => 'assignment_document.php'
		]
	]);
}

if($_POST){
	if(isset($_POST['get_form_dealgroup_document'])){
		getFormDealGroupDocument();
	}

	if(isset($_POST['assignDealGroupDocuments'])){
		assignDealGroupDocuments();
	}
}