<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

require_once('connection.php');
require_once('helpers.php');

function getPositions(){
	GLOBAL $connection;
	$sql = "SELECT * 
			FROM positions 
			ORDER BY positions.position_title
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}

function getRolePositions(){
	GLOBAL $connection;

	$sql = "SELECT * FROM role_position
			ORDER BY position_title";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));	

	return $query;
}

function getRolePositionDetails($id){
	GLOBAL $connection;
	$id = removeSpecialChars($id);

	$sql = "SELECT * 
			FROM role_position,positions 
			WHERE role_position.role_position_id = '$id'
				AND role_position.position_id = positions.position_id";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return mysqli_fetch_assoc($query);
}

function getAssignedPositionForm(){
	// GET USERS
	include('user_functions.php');
    $users_query = getActiveUsers();
    $user_options = "<option selected disabled>Select user ...</option>";
    while($user = mysqli_fetch_assoc($users_query)) {    	
    	$name = ucwords($user['first_name']) . ' ' . ucwords($user['last_name']);
    	$user_options .= "<option value='" . $user['user_id'] . "'>" . $name . "</option>";
    }

    // GET ENTITIES
	include('entity_functions.php');
    $entities_query = getEntities();
    $entity_options = "<option selected disabled>Select entity ...</option>";
    while($entity = mysqli_fetch_assoc($entities_query)) {    	
    	$name = ucwords($entity['entity_legal_name']);
    	$entity_options .= "<option value='" . $entity['entity_id'] . "'>" . $name . "</option>";
    }

	$form = <<< FORM
	<form action="#" id="assign_position_form">
	    <div class="form-group">		    
			<div class="row">
				<div class="col-md-6">
					<div style="margin-bottom:20px">  <label for="user_id">Assigned To:</label>
						<select class="form-control input_required" id="user_id" placeholder="Select user ..." required name="user_id">		
							{$user_options}        
						</select>
					</div>   
				</div>
				<div class="col-md-6">
					<div style="margin-bottom:20px">  <label for="supervisor_id">Supervisor:</label>
						<select class="form-control input_required" id="supervisor_id" placeholder="Select supervisor ..." required name="supervisor_id">		
							{$user_options}        
						</select>
					</div> 
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div style="margin-bottom:20px"><label for="position_title">Position Title:</label>
						<input type="text" class="form-control input_required" id="position_title" required placeholder="Position Title... " name="position_title"/>
					</div>
				</div>
				<div class="col-md-6">
					<div style="margin-bottom:20px"><label for="position_description">Position Description:</label>
						<textarea type="text" class="form-control input_required" id="position_description" required placeholder="Position description... " name="position_description"/></textarea>
					</div>  
				</div>  
			</div>	    	
			<div class="row">
				<div class="col-md-6">
					<div style="margin-bottom:20px">  
						<label for="entity_inputform">Entity:</label>
						<select class="form-control input_required input_assign_entity" id="entity_inputform" placeholder="Select entity ..." required name="entity_inputform">
							{$entity_options}
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div style="margin-bottom:20px">  
						<label for="deal_group">Deal Group:</label>
						<select class="form-control input_required input_assign_dealgroup" id="deal_group" disabled placeholder="Select deal group ..." required name="deal_group">
						</select>
					</div>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-6">
					<div style="margin-bottom:20px"><label for="start_date">Start Date:</label>
						<input type="date" class="form-control" id="start_date" required placeholder="Select starting date..." name="start_date"/>
					</div>
				</div>
				<div class="col-md-6">
					<div style="margin-bottom:20px"><label for="end_date">End Date:</label>
						<input type="date" class="form-control"  id="end_date" placeholder="Select end date..." name="end_date"/>
					</div>
				</div>
				<input type="hidden" name="assignPosition" value="" >  
			</div>   
		</div>
	  </form> 
FORM;

	echo json_encode([
		'status' => 'success',
		'message' => "{$form}"
	]);
}

// assign position
function assignPosition(){
	GLOBAL $connection;

	$user_id = removeSpecialChars($_POST['user_id']);
	$position_title = removeSpecialChars(strtolower($_POST['position_title']));
	$position_description = removeSpecialChars(strtolower($_POST['position_description']));
	$entity_id = removeSpecialChars($_POST['entity_inputform']);
	$dealgroup_id = removeSpecialChars($_POST['deal_group']);
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$supervisor_id = removeSpecialChars($_POST['supervisor_id']);
	$status = empty($end_date) ? 'ACTIVE' : 'INACTIVE';

	$sql = "INSERT INTO role_position (user_id,supervisor_id,entity_id,dealgroup_id,start_date,end_date,status,position_title,position_description)
			VALUES ($user_id,$supervisor_id,$entity_id,$dealgroup_id,'$start_date','$end_date','$status','$position_title','$position_description')
	";

	mysqli_query($connection,$sql) or die(json_encode([
		'status' => 'error',
		'message' => mysqli_error($connection)
	]));

	echo json_encode([
		'status' => 'success',
		'message' => 'New position has been successfully added!',
		'after_action' => [
			'action' => 'redirect',
			'ref' => 'assignment_position.php'
		]
	]);
}

function getUserAssignedPositionForm(){
	$user_id = removeSpecialChars($_POST['user_id']);

	// GET USERS
	include('user_functions.php');
    $users_query = getActiveUsersExcept($user_id);
    $user_options = "<option selected disabled>Select supervisor ...</option>";
    while($user = mysqli_fetch_assoc($users_query)) {    	
    	$name = ucwords($user['first_name']) . ' ' . ucwords($user['last_name']);
    	$user_options .= "<option value='" . $user['user_id'] . "'>" . $name . "</option>";
    }
    // GET ENTITIES
	include('entity_functions.php');
    $entities_query = getEntities();
    $entity_options = "<option selected disabled>Select entity ...</option>";
    while($entity = mysqli_fetch_assoc($entities_query)) {    	
    	$name = ucwords($entity['entity_legal_name']);
    	$entity_options .= "<option value='" . $entity['entity_id'] . "'>" . $name . "</option>";
    }

	$form = <<< FORM
	<form action="#" id="assign_userposition_form">
	    <div class="form-group">		    
			<div class="row">
				<div class="col-md-6">
					<div style="margin-bottom:20px"><label for="position_title">Position Title:</label>
						<input type="text" class="form-control input_required" id="position_title" required placeholder="Position Title... " name="position_title"/>
					</div>
				</div>
				<div class="col-md-6">
					<div style="margin-bottom:20px"><label for="position_description">Position Description:</label>
						<textarea type="text" class="form-control input_required" id="position_description" required placeholder="Position description... " name="position_description"/></textarea>
					</div>  
				</div>  
			</div>	    	
			<div class="row">
				<div class="col-md-6">
					<div style="margin-bottom:20px">  
						<label for="entity_inputform">Entity:</label>
						<select class="form-control input_required input_assign_entity" id="entity_inputform" placeholder="Select entity ..." required name="entity_inputform">
							{$entity_options}
						</select>
					</div>
				</div>
				<div class="col-md-6">
					<div style="margin-bottom:20px">  
						<label for="deal_group">Deal Group:</label>
						<select class="form-control input_required input_assign_dealgroup" id="deal_group" disabled placeholder="Select deal group ..." required name="deal_group">
						</select>
					</div>
				</div>	
			</div>
			<div class="row">
				<div class="col-md-6">
					<div style="margin-bottom:20px"><label for="start_date">Start Date:</label>
						<input type="date" class="form-control" id="start_date" required placeholder="Select starting date..." name="start_date"/>
					</div>
				</div>
				<div class="col-md-6">
					<div style="margin-bottom:20px"><label for="end_date">End Date:</label>
						<input type="date" class="form-control"  id="end_date" placeholder="Select end date..." name="end_date"/>
					</div>
				</div>
			</div>   			
			<div class="row">
				<div class="col-md-12">
					<div style="margin-bottom:20px">  <label for="supervisor_id">Supervisor:</label>
						<select class="form-control input_required" id="supervisor_id" placeholder="Select supervisor ..." required name="supervisor_id">		
							{$user_options}        
						</select>
					</div> 
				</div>
			</div>
			<input type="hidden" name="user_id" value={$user_id} />
			<input type="hidden" name="assignUserPosition" />  
		</div>
	  </form> 
FORM;

	echo json_encode([
		'status' => 'success',
		'message' => "{$form}"
	]);
}

function assignUserPosition(){
	GLOBAL $connection;
	$position_title = removeSpecialChars($_POST['position_title']);
	$position_description = removeSpecialChars($_POST['position_description']);
	$entity_id = removeSpecialChars($_POST['entity_inputform']);
	$dealgroup_id = removeSpecialChars($_POST['deal_group']);
	$start_date = $_POST['start_date'];
	$end_date = $_POST['end_date'];
	$supervisor_id = removeSpecialChars($_POST['supervisor_id']);
	$user_id = removeSpecialChars($_POST['user_id']);
	$status = empty($end_date) ? 'ACTIVE' : 'INACTIVE';

	$sql = "INSERT INTO role_position (user_id,supervisor_id,entity_id,dealgroup_id,start_date,end_date,status,position_title,position_description)
			VALUES ($user_id,$supervisor_id,$entity_id,$dealgroup_id,'$start_date','$end_date','$status','$position_title','$position_description')
	";

	mysqli_query($connection,$sql) or die(json_encode([
		'status' => 'error',
		'message' => mysqli_error($connection)
	]));

	echo json_encode([
		'status' => 'success',
		'message' => 'New position successfully set!',
		'after_action' => [
			'action' => 'redirect',
			'ref' => 'users_view.php?user_id=' . $user_id
		]
	]);
}

if($_POST){
	if(isset($_POST['assign_position'])){
		getAssignedPositionForm();
	}

	if(isset(($_POST['user_assign_position']))){
		getUserAssignedPositionForm();
	}

	if(isset($_POST['assignPosition'])){
		assignPosition();
	}

	if(isset($_POST['assignUserPosition'])){
		assignUserPosition();
	}
}

if($_GET){

}