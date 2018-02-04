<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('connection.php');

function assignedPosition(){
	GLOBAL $connection;	
	$position_id = $_POST['position_id'];
	$user_id = $_POST['user_id'];
	$dealgroup_id = $_POST['dealgroup_id'];
	$entity_id = $_POST['entity_id'];
	$start_date = $_POST['start_date'];
	$end_date = empty($_POST['end_date']) ? NULL : $_POST['end_date'];
	$status = is_null($end_date) ? 'ACTIVE' : '';

	$sql = "SELECT role_position_id FROM role_position 
			WHERE position_id=$position_id AND user_id=$user_id";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	// @true
	if($query->num_rows > 0 ){
		$role_position_id = mysqli_fetch_assoc($query)['role_position_id'];
		$sql = "SELECT * FROM dealgroup_staffing
				WHERE dealgroup_staffing.role_position_id = $role_position_id
				AND dealgroup_staffing.entity_id = $entity_id
				AND dealgroup_staffing.dealgroup_id = $dealgroup_id
		";
		$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
		// @true
		if($query->num_rows > 0){
			echo json_encode([
				'status' => 'error',
				'message' => 'The user you are trying to add is already assigned!',
			]);
			die();
		}
	} else {
		$sql = "INSERT INTO role_position(position_id,user_id) 
				VALUES($position_id,$user_id)
		";
		mysqli_query($connection,$sql) or die(mysqli_error($connection));
		$role_position_id = mysqli_insert_id($connection);
	}

	$sql = "INSERT INTO dealgroup_staffing(dealgroup_id,role_position_id,entity_id,start_date,end_date,status)
			VALUES ($dealgroup_id,$role_position_id,$entity_id,'$start_date','$end_date','$status');
	";


	mysqli_query($connection,$sql) or die(mysqli_error($connection));	
	$id = mysqli_insert_id($connection);

	$query_details = getDealGroupStaffingDetailsById($id);
	$positionassignment = mysqli_fetch_assoc($query_details);

	$staff_id = $positionassignment['dealgroup_staffing_id'];
	$title = ucwords($positionassignment['position_title']);
	$user =  ucwords($positionassignment['first_name']) . ' ' . ucwords($positionassignment['last_name']);
	$start_date = date('F d, Y | l', strtotime($positionassignment['start_date']));
	$end_date = $positionassignment['end_date'] == 0 ? 'ACTIVE' : date('F d, Y | l', strtotime($positionassignment['end_date']));

	$row = <<< ROW
	<tr id="rowdealgroup_$staff_id">
        <td id="position_title">$title</td>
        <td id="assigned_to">$user</td>
        <td id="start_date">$start_date</td>
        <td id="end_date">$end_date</td>
        <td>                                    
          <a href="javascript:void(0);" class="update_assigned_position_btn btn-xs btn-warning" data-id="$staff_id"> UPDATE </a>&nbsp;
          <a href="javascript:void(0);" class="delete_assigned_position_btn btn-xs btn-danger" data-id="$staff_id" data-user="$user" data-position="$title" data-tr="#rowdealgroup_$staff_id"> REMOVE </a> 
    	</td>
  	</tr>
ROW;	

	echo json_encode([
		'status' => 'success',
		'message' => 'Successfully assigned!',
		'after_action' => [
			'action' => 'append',
			'target' => '#table_' . $dealgroup_id . '_' . $entity_id,
			'fragments' => [
				'row' => $row
			]
		]
	]);
}

function getDealGroupStaffingDetailsById($id){
	GLOBAL $connection;
	$sql = "SELECT users.first_name,users.last_name,positions.position_title,dealgroup_staffing.dealgroup_id,dealgroup_staffing.entity_id, role_position.user_id,role_position.role_position_id,role_position.position_id,dealgroup_staffing.dealgroup_staffing_id,dealgroup_staffing.start_date,dealgroup_staffing.end_date FROM dealgroup_staffing,role_position,positions,users 
		WHERE dealgroup_staffing.dealgroup_staffing_id = $id
		AND dealgroup_staffing.role_position_id = role_position.role_position_id
		AND role_position.position_id = positions.position_id
		AND role_position.user_id = users.user_id";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));	
	return $query;
}

function getAssignedPositionForm($staffing_detail = [],$id="",$action,$data){
	$selected_first = $action == 'update' ? '' : 'selected';

	// GET USERS
	include('user_functions.php');
    $users_query = getActiveUsers();
    $user_options = "<option disabled $selected_first>Select users ...</option>";
    while($user = mysqli_fetch_assoc($users_query)) {    	
    	$selected = "";
    	if($action == 'update') {
    		$selected = $staffing_detail['user_id'] === $user['user_id'] ? 'selected' : '';
    	}
    	$name = ucwords($user['first_name']) . ' ' . ucwords($user['last_name']);
    	$user_options .= "<option value='" . $user['user_id'] . "' $selected>" . $name . "</option>";
    }

    // GET POSITIONS
	include('position_functions.php');
    $position_query = getPositions();
    $position_options = "<option disabled  $selected_first>Select position ...</option>";
    while($position = mysqli_fetch_assoc($position_query)) {
    	$selected = "";
	   	if($action == 'update') {
    		$selected = $staffing_detail['position_id'] === $position['position_id'] ? 'selected' : '';
    	}
    	$position_name = ucwords($position['position_title']);
    	$position_options .= "<option value='" . $position['position_id'] . "' $selected>". $position_name ."</option>";
    }

    $disabled = $action == 'update' ? 'disabled' : '';

	$form = <<< FORM
		 <form action="#" id="update_staffing_form">
		    <div class="form-group">
		      <div style="margin-bottom:20px"><label for="position_title">Position Title:</label>
  		      <select $disabled class="form-control input_required" id="position_title" placeholder="Select position ..." required name="position_id">		
  		      {$position_options}
  		      </select>
  		      </div>
	    	  <div style="margin-bottom:20px">  <label for="user_id">Assigned To:</label>
		      <select $disabled class="form-control input_required" id="user_id" placeholder="Select user ..." required name="user_id">		        
		      {$user_options}		    
		      </select></div>       		 
FORM;
	if($action == 'update'){
		$form .= <<< APPEND_FORM
			  <div style="margin-bottom:20px"><label for="start_date">Start Date:</label>
       		  <input type="date" class="form-control" id="start_date" required value="{$staffing_detail['start_date']}" placeholder="Select starting date..." name="start_date"/></div>
       		  <div style="margin-bottom:20px"><label for="end_date">End Date:</label>
       		  <input type="date" class="form-control"  id="end_date" value="{$staffing_detail['end_date']}" placeholder="Select end date..." name="end_date"/></div>
		      <input type="hidden" name="dealgroup_staffing_id" value="{$staffing_detail['dealgroup_staffing_id']}" >	 
		      <input type="hidden" name="role_position_id" value="{$staffing_detail['role_position_id']}" >
		      <input type="hidden" name="entity_id" value="{$staffing_detail['entity_id']}" >
		      <input type="hidden" name="dealgroup_id" value="{$staffing_detail['dealgroup_id']}" >
		      <input type="hidden" name="position_id" value="{$staffing_detail['position_id']}" >
		      <input type="hidden" name="user_id" value="{$staffing_detail['user_id']}" >
		      <input type="hidden" name="updatedealstaff" value="" >         
		  </form> 
APPEND_FORM;
	} else if($action == 'assigned'){
		$form .= <<< APPEND_ASSIGNED
			  <div style="margin-bottom:20px"><label for="start_date">Start Date:</label>
       		  <input type="date" class="form-control input_required" id="start_date" required placeholder="Select starting date..." name="start_date"/></div>
       		  <div style="margin-bottom:20px"><label for="end_date">End Date:</label>
       		  <input type="date" class="form-control" id="end_date" placeholder="Select end date..." name="end_date"/></div>
		      <input type="hidden" name="entity_id" value="{$data['entity_id']}" >
		      <input type="hidden" name="dealgroup_id" value="{$data['dealgroup_id']}" >
		      <input type="hidden" name="assignedstaff" value="" >         
		  </form> 
APPEND_ASSIGNED;
	}

	return $form;
}

function updateDealStaffingForm(){
	GLOBAL $connection;
	$id = $_POST['id'];

	$query = getDealGroupStaffingDetailsById($id);

	$staffing_detail  = mysqli_fetch_assoc($query);

	$form = getAssignedPositionForm($staffing_detail,$id,'update',[]);
	
	echo json_encode([
		'status' => 'success',
		'message' => "{$form}"
	]);
}

function assignedDealStaffForm(){
	$entity_id = $_POST['dealgroup_id'];
	$dealgroup_id = $_POST['dealgroup_id'];

	$form = getAssignedPositionForm([],'','assigned',$_POST);
	
	echo json_encode([
		'status' => 'success',
		'message' => "{$form}"
	]);
}

function updateDealStaffPosition(){
	GLOBAL $connection;	

	$position_id = $_GET['position_id'];
	$user_id = $_GET['user_id'];
	$start_date = $_GET['start_date'];
	$end_date = empty($_GET['end_date']) ? NULL : $_GET['end_date'];
	$status = is_null($end_date) ? 'ACTIVE' : '';
	$role_position_id = $_GET['role_position_id'];
	$dealgroup_staffing_id = $_GET['dealgroup_staffing_id'];
	$entity_id = $_GET['entity_id'];
	$dealgroup_id = $_GET['dealgroup_id'];
	
	$sql = "SELECT role_position_id FROM role_position 
			WHERE position_id=$position_id AND user_id=$user_id";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	// @true
	if($query->num_rows > 0 ){
		$role_position_id = mysqli_fetch_assoc($query)['role_position_id'];
		$sql = "SELECT * FROM dealgroup_staffing
				WHERE dealgroup_staffing.role_position_id = $role_position_id
				AND dealgroup_staffing.entity_id = $entity_id
				AND dealgroup_staffing.dealgroup_id = $dealgroup_id
		";
		$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
		// @true
		if($query->num_rows > 0){
			$id = mysqli_fetch_assoc($query)['dealgroup_staffing_id'];
			$sql = "UPDATE dealgroup_staffing SET end_date = ";
			$sql .= is_null($end_date) ? 'NULL' .',' : "'$end_date',";
			$sql .= "start_date='$start_date', status = '$status'
					WHERE dealgroup_staffing_id = $id";

			$query = mysqli_query($connection,$sql) or die(json_encode([
				'status' => 'error',
				'message' => 'There was an error processing your request!',
			]));

			$query = getDealGroupStaffingDetailsById($id);
			$dealgroup_detail = mysqli_fetch_assoc($query);

			$title = ucwords($dealgroup_detail['position_title']); 
			$assigned_to = ucwords($dealgroup_detail['first_name']) . ' ' . ucwords($dealgroup_detail['last_name']);
			$start_date = date('F d, Y | l', strtotime($dealgroup_detail['start_date']));
			$end_date = is_null($dealgroup_detail['end_date']) ? 'ACTIVE' : date('F d, Y | l', strtotime($dealgroup_detail['end_date']));

			echo json_encode([
				'status' => 'success',
				'message' => 'Successfully updated!',
				'after_action' => [
					'action' => 'replace',
					'target' => '#rowdealgroup_' . $dealgroup_staffing_id,
					'fragments' => [
						'#position_title' => $title,
						'#assigned_to' => $assigned_to,
						'#start_date' => $start_date,
						'#end_date' => $end_date
					]
				]
			]);
		}
	}
} 
// REMOVE
function dealStaffRemove(){
	GLOBAL $connection;
	$id = $_POST['dealgroup_staff_id'];
	$target = $_POST['target_row'];

	$sql = "DELETE FROM dealgroup_staffing WHERE dealgroup_staffing.dealgroup_staffing_id=$id";
	mysqli_query($connection,$sql) or die(json_encode([
		'status' => 'error',
		'message' => 'There was an error processing your request!',
	]));	

	echo json_encode([
		'status' => 'success',
		'message' => 'Successfully removed!',
		'after_action' => [
			'action' => 'delete',
			'target' => $target,
		]
	]);
}

if($_POST){
	if(isset($_POST['updateDealStaffingForm'])){
		updateDealStaffingForm();
	}

	if(isset($_POST['update_assigned_position']) == 'update_assigned_position') {
		updateAssignedPosition();
	}

	if(isset($_POST['dealgroup_assigned_position'])){
		assignedDealStaffForm();
	}

	if(isset($_POST['dealgroup_staff_remove'])){
		dealStaffRemove();
	}

	if(isset($_POST['assignedstaff'])){
		assignedPosition();
	}
}

if($_GET){
	if(isset($_GET['updatedealstaff'])){
		updateDealStaffPosition();
	}
}