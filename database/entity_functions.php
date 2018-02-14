<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('connection.php');

// get all entities
function getEntities(){
	GLOBAL $connection;

	$sql = "SELECT * FROM entities ORDER BY entities.entity_legal_name";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	return $query;
}

// Get entity details by fields 
function getEntityDetailFields($id,$field){
	GLOBAL $connection;
	$sql = "SELECT * FROM entities 
			WHERE entities.entity_id='$id'
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	return mysqli_fetch_assoc($query)[$field];
}

// ADD ENTITY
function addEntity(){
	GLOBAL $connection;
	$_SESSION['action_success'] = "";
	$_SESSION['action_error'] = "";

	$legal_name = strtolower($_POST['legal_name']);
	$nick_name = strtolower($_POST['nick_name']);
	$street_address = strtolower($_POST['street_address']);
	$city = strtolower($_POST['city']);
	$state = strtolower($_POST['state']);
	$zipcode = strtolower($_POST['zip_code']);
	$country = strtolower($_POST['country']);
	$incorporation_state = strtolower($_POST['incorporation_state']);
    $dealgroups  = $_POST['deal_group'];
    $entitytypes = $_POST['entity_type'];	
    $start_dates = $_POST['start_date'];
    $end_dates = $_POST['end_date'];


	$sql = "INSERT INTO entities (entity_nickname,entity_legal_name,street_address,city,state,zipcode,country,incorporation_state)
			VALUES('$nick_name','$legal_name','$street_address','$city','$state','$zipcode','$country','$incorporation_state')
	";
	mysqli_query($connection, $sql) or die(mysqli_error($connection));
	$entity_id = mysqli_insert_id($connection);

	for($i=0;$i<count($dealgroups);$i++){
		if(!empty($dealgroups[$i])) {
			$dealgroup_id = $dealgroups[$i];
			$entity_type = $entitytypes[$i];
			$start_date = $start_dates[$i];
			$end_date = $end_dates[$i];

			$sql_checkexist = "SELECT * FROM dealgroup_entity_assignment
							   WHERE dealgroup_id = $dealgroup_id
							   AND entity_id = $entity_id
			";
			$query = mysqli_query($connection,$sql_checkexist) or die(mysqli_error($connection));
					
			if(!$query->num_rows > 0){
				$sql_insert = "INSERT INTO dealgroup_entity_assignment(dealgroup_id,entity_id,type,start_date,end_date)
					VALUES ($dealgroup_id,$entity_id,'$entity_type','$start_date','$end_date')";

				mysqli_query($connection,$sql_insert) or die(mysqli_error($connection));
			}
		}
	}    

	if(empty($_SESSION['action_error'])){
		$_SESSION['action_success'] .= "Entity succesfully added!";
		unset($_SESSION['action_error']);
		header("Location: ../entities_view.php?entity_id=$entity_id");
	} else {
		$_SESSION['action_error'] .= "Error on add!";		
		unset($_SESSION['action_success']);
		header("Location: ../entities_add.php");
	}

}

// FUNCTION UPDATE ENTITY
function updateEntity(){
	GLOBAL $connection;
	$_SESSION['action_success'] = "";
	$_SESSION['action_error'] = "";

	$legal_name = strtolower($_POST['legal_name']);
	$nick_name = strtolower($_POST['nick_name']);
	$street_address = strtolower($_POST['street_address']);
	$city = strtolower($_POST['city']);
	$state = strtolower($_POST['state']);
	$zipcode = strtolower($_POST['zip_code']);
	$country = strtolower($_POST['country']);
	$incorporation_state = strtolower($_POST['incorporation_state']);
	$entity_id = $_POST['entity_id'];
	$dealgroups = $_POST['deal_group'];
	$entitytypes = $_POST['entity_type'];
    $start_dates = $_POST['start_date'];
    $end_dates = $_POST['end_date'];

	$sql = "UPDATE entities SET entity_nickname = '$nick_name', entity_legal_name = '$legal_name', street_address = '$street_address',
			city = '$city', state = '$state', zipcode = '$zipcode', country = '$country', incorporation_state = '$incorporation_state'
			WHERE entities.entity_id = '$entity_id'";
	mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$previousDealGroups = getPreviousDealGroup($entity_id);

	$currentDealGroupIds = [];
	include('dealgroup_functions.php');
	for($i=0;$i<count($dealgroups);$i++){
		if(!empty($dealgroups[$i])) {
			$dealgroup_id = $dealgroups[$i];
			$entity_type = $entitytypes[$i];
			$start_date = $start_dates[$i];
			$end_date = $end_dates[$i];

			$sql = "SELECT * FROM dealgroup_entity_assignment 
				WHERE dealgroup_entity_assignment.entity_id = $entity_id
				AND dealgroup_entity_assignment.dealgroup_id = $dealgroup_id
			";

			$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

			if(!$query->num_rows > 0){
				$sql_insert = "INSERT INTO dealgroup_entity_assignment(entity_id,dealgroup_id,type,start_date,end_date)
							VALUES ($entity_id,$dealgroup_id,'$entity_type','$start_date','$end_date')";

				mysqli_query($connection,$sql_insert) or die(mysqli_error($connection));

				$inserted_id = mysqli_insert_id($connection);
				$currentDealGroupIds[] = $inserted_id;

				$detail = getDealGroupAssignmentDetails($inserted_id);
				$_SESSION['action_success'] .= "<li><strong>" . strtoupper($detail['group_name']) .' (' . strtoupper($detail['code_name']) .')' . "</strong> successfully added!</li>";
			} else {
				$id = mysqli_fetch_assoc($query)['dealgroup_entity_assignment_id'];
				$sql_update = "UPDATE dealgroup_entity_assignment 
							   SET type = '$entity_type',start_date = '$start_date',end_date = '$end_date'
							   WHERE dealgroup_entity_assignment_id = $id
				";

				mysqli_query($connection,$sql_update) or die(mysqli_error($connection));
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
				$_SESSION['action_error'] .= "<li><strong>" . strtoupper($detail['group_name']) .' (' . strtoupper($detail['code_name']) .')' . " can't be deleted! </strong><small> Assigned to a position.</small></li>";
			} else {
				$sql = "DELETE FROM dealgroup_entity_assignment 
						WHERE dealgroup_entity_assignment.dealgroup_entity_assignment_id = $id";
				mysqli_query($connection,$sql);

				$_SESSION['action_success'] .= "<li><strong>" . strtoupper($detail['group_name']) .' (' . strtoupper($detail['code_name']) .')' . "</strong> successfully removed!</li>";
			}
		}
	}

	if(empty($_SESSION['action_error'])){
		$_SESSION['action_success'] .= "<br/>Entity succesfully updated! ";
		unset($_SESSION['action_error']);
		header("Location: ../entities_update.php?entity_id=$entity_id");
	} else {
		unset($_SESSION['action_success']);
		header("Location: ../entities_update.php?entity_id=$entity_id");
	}
}

// GET ENTITY DETAILS
function getEntityDetails($entity_id,$page = ''){
	GLOBAL $connection;
	$sql = "SELECT * FROM entities
			WHERE entities.entity_id = $entity_id			
	";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	
	if($page === 'view'){
		return $query;
	}
	return mysqli_fetch_assoc($query);
}

// GET ASSIGNED DEAL GRUOPS
function getAssignedDealGroups($entity_id){
	GLOBAL $connection;

	$sql = "
		SELECT * FROM dealgroup_entity_assignment,deal_groups
		WHERE dealgroup_entity_assignment.entity_id = $entity_id
		AND dealgroup_entity_assignment.dealgroup_id = deal_groups.dealgroup_id
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}

function getPreviousDealGroup($id){
	GLOBAL $connection;

	$sql = "SELECT * FROM dealgroup_entity_assignment
			WHERE dealgroup_entity_assignment.entity_id = $id			
	";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	$id = [];

	while($row = mysqli_fetch_assoc($query)){
		$id[] = $row['dealgroup_entity_assignment_id'];
	}

	return $id;
}

function isAssignedToPosition($dealgroup_id,$entity_id){
	GLOBAL $connection;

	$sql = "SELECT * FROM role_position 
			WHERE role_position.dealgroup_id = $dealgroup_id
			AND role_position.entity_id = $entity_id";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	if($query->num_rows > 0){
		return true;
	} else {
		return false;
	}
}

function deleteEntity(){
	GLOBAL $connection;
	$id = $_POST['entity_id'];

	$sql = "SELECT * FROM role_position
			WHERE role_position.entity_id = $id
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	if($query->num_rows > 0){
		$count = $query->num_rows;
		$append_s = $count > 1 ? 's' : '';
		$message = "Entity is assigned to a <strong>{$count} position{$append_s}</strong>!";
		echo json_encode([
			'status' => 'error',
			'message' => $message
		]);
		die;
	} else {
		$sql = "DELETE FROM dealgroup_entity_assignment
				WHERE dealgroup_entity_assignment.entity_id = $id";
		$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

		$sql = "DELETE FROM entities
				WHERE entities.entity_id = $id";
		$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

		echo json_encode([
			'status' => 'success',
			'message' => 'Entity successfully deleted!',
			'after_action' => [
				'action' => 'redirect',
				'ref' => 'entities.php'
			]
		]);
	}	
	die();
}

// get position entity
function getEntityPeople($entity_id){
	GLOBAL $connection;

	$sql = "SELECT * FROM role_position,users
			WHERE role_position.entity_id = $entity_id
			AND role_position.user_id = users.user_id";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}
// assignment 
function getEntityAssignmentDetails($id){
	GLOBAL $connection;
	$sql = "SELECT * 
			FROM entities,dealgroup_entity_assignment
			WHERE dealgroup_entity_assignment.dealgroup_entity_assignment_id = $id
			AND dealgroup_entity_assignment.entity_id = entities.entity_id
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return mysqli_fetch_assoc($query);
}

// GET ASSIGNED DEAL GRUOPS - AJAX
function getAssignedDealGroupsAjax($toSelect = false){
	GLOBAL $connection;
	$entity_id = $_POST['id'];

	$sql = "
		SELECT * FROM dealgroup_entity_assignment,deal_groups
		WHERE dealgroup_entity_assignment.entity_id = $entity_id
		AND dealgroup_entity_assignment.dealgroup_id = deal_groups.dealgroup_id
	";

	$query = mysqli_query($connection,$sql) or die(json_encode([
		'status' => 'error',
		'message' => 'Error processing your request!'
	]));

	if($toSelect){
		include ('dealgroup_functions.php');

		$deal_groups = getDealGroups();
		$dealgroup_options = [];

		while($deal_group = mysqli_fetch_assoc($deal_groups)){
			$name = ucwords($deal_group['group_name'] . ' ( ' . $deal_group['code_name'] .')');

			$dealgroup_options[] = [
				'id' => $deal_group['dealgroup_id'],
				'text' => $name
			];
		}

		$options = [];
		$ids = [];
		while($result = mysqli_fetch_assoc($query)){
			$name = ucwords($result['group_name'] . ' ( ' . $result['code_name'] .')');
			$ids[] = $result['dealgroup_id'];
			$options[]= [
				'id' => $result['dealgroup_id'],
				'text' => $name
			];
		}



		echo json_encode([
			'status' => 'success',
			'selectedValues' => $ids,
			'options' => $dealgroup_options,
			'count' => $query->num_rows,
		]);
		die();
	
	} else {
		$options = "";
		while($result = mysqli_fetch_assoc($query)){
			$name = ucwords($result['group_name'] . ' ( ' . $result['code_name'] .')');
			$options .= "<option value='".ucwords($result['dealgroup_id'])."'> $name </option>";
		}
	}

	echo json_encode([		
		'status' => 'success',
		'count' => $query->num_rows,
		'after_action' => [
			'action' => 'append',
			'fragments' => [
				$options
			],
			'target' => '.input_assign_dealgroup'
		]
	]);

	// return $query;
}

function getFormEntityDealGroup(){
    // GET ENTITIES
    $entities_query = getEntities();
    $entity_options = "<option selected disabled>Select entity ...</option>";
    while($entity = mysqli_fetch_assoc($entities_query)) {    	
    	$name = ucwords($entity['entity_legal_name']);
    	$entity_options .= "<option value='" . $entity['entity_id'] . "'>" . $name . "</option>";
    }
	$form = <<< FORM
	<form action="#" id="entity_dealgroups_assign_form">
	    <div class="form-group">		    
			<div class="row">
				<div class="col-md-12">
					<div style="margin-bottom:20px">  <label for="entity_id">Entity</label>
						<select class="form-control input_required" id="entity_id" required name="entity_id">		
							{$entity_options}        
						</select>
					</div>   
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div style="margin-bottom:20px">  <label for="dealgroup_id">Deal Groups:</label>
						<select class="form-control input_assign_dealgroup" value="[]" multiple="" id="dealgroup_id" disabled placeholder="Select deal group ..." required name="dealgroup_id[]">		
						</select>
					</div> 
				</div>
			</div>
		</div>
		<input type="hidden" name="assignEntityDealGroup">
	  </form> 
FORM;

	echo json_encode([
		'status' => 'success',
		'message' => "{$form}"
	]);
}



if($_POST){
	if(isset($_POST['add_entity'])){
		addEntity();
	}

	if(isset($_POST['update_entity'])){
		updateEntity();
	}

	if(isset($_POST['delete_entity'])){
		deleteEntity();
	}

	if (isset($_POST['get_dealgroups_pos_page'])) {
		getAssignedDealGroupsAjax();
	}

	if (isset($_POST['get_dealgroups'])) {
		getAssignedDealGroupsAjax(true);
	}

	if(isset($_POST['get_form_ed'])){
		getFormEntityDealGroup();
	}
}