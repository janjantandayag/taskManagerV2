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

	$sql = "INSERT INTO entities (entity_nickname,entity_legal_name,street_address,city,state,zipcode,country,incorporation_state)
			VALUES('$nick_name','$legal_name','$street_address','$city','$state','$zipcode','$country','$incorporation_state')
	";
	mysqli_query($connection, $sql) or die(mysqli_error($connection));
	$entity_id = mysqli_insert_id($connection);

	for($i=0;$i<count($dealgroups);$i++){
		$dealgroup_id = $dealgroups[$i];
		$entity_type = $entitytypes[$i];

		$sql_checkexist = "SELECT * FROM dealgroup_entity_assignment
						   WHERE dealgroup_id = $dealgroup_id
						   AND entity_id = $entity_id
		";
		$query = mysqli_query($connection,$sql_checkexist) or die(mysqli_error($connection));
				
		if(!$query->num_rows > 0){
			$sql_insert = "INSERT INTO dealgroup_entity_assignment(dealgroup_id,entity_id,type)
				VALUES ($dealgroup_id,$entity_id,'$entity_type')";

			mysqli_query($connection,$sql_insert) or die(mysqli_error($connection));
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

	$sql = "UPDATE entities SET entity_nickname = '$nick_name', entity_legal_name = '$legal_name', street_address = '$street_address',
			city = '$city', state = '$state', zipcode = '$zipcode', country = '$country', incorporation_state = '$incorporation_state'
			WHERE entities.entity_id = '$entity_id'";
	mysqli_query($connection, $sql) or die(mysqli_error($connection));

	$previousDealGroups = getPreviousDealGroup($entity_id);
	$currentDealGroupIds = [];

	for($i=0;$i<count($dealgroups);$i++){
		$dealgroup_id = $dealgroups[$i];
		$entity_type = $entitytypes[$i];

		$sql = "SELECT * FROM dealgroup_entity_assignment 
			WHERE dealgroup_entity_assignment.entity_id = $entity_id
			AND dealgroup_entity_assignment.dealgroup_id = $dealgroup_id
		";

		$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

		if(!$query->num_rows > 0){
			$sql_insert = "INSERT INTO dealgroup_entity_assignment(entity_id,dealgroup_id,type)
						VALUES ($entity_id,$dealgroup_id,'$entity_type')";

			mysqli_query($connection,$sql_insert) or die(mysqli_error($connection));
		} else {
			$id = mysqli_fetch_assoc($query)['dealgroup_entity_assignment_id'];
			$sql_insert = "UPDATE dealgroup_entity_assignment SET type = '$entity_type'
							WHERE dealgroup_entity_assignment_id = $id
			";

			mysqli_query($connection,$sql_insert) or die(mysqli_error($connection));
			$currentDealGroupIds[] = $id;
		}
	}

	$ids = array_unique(array_merge($currentDealGroupIds,$previousDealGroups));
	include('dealgroup_functions.php');
	foreach($ids as $id){		
		$detail = getDealGroupAssignmentDetails($id);
		if( in_array($id, $previousDealGroups) && !in_array($id, $currentDealGroupIds) ){			
			$_SESSION['action_error'] .= "<li>Cannot delete position title: <strong>" . strtoupper($detail['group_name']) .' (' . strtoupper($detail['code_name']) .')' . "</strong> <small> Assigned to a deal group</small></li>";
		}
	}
	if(empty($_SESSION['action_error'])){
		$_SESSION['action_success'] .= "Entity succesfully updated!";
		unset($_SESSION['action_error']);
		header("Location: ../entities_update.php?entity_id=$entity_id");
	} else {
		unset($_SESSION['action_success']);
		header("Location: ../entities_update.php?entity_id=$entity_id");
	}

	var_dump($previousDealGroups);
	var_dump($currentDealGroupIds);
	die();
	// var_dump($_POST);
}

// GET ENTITY DETAILS
function getEntityDetails($entity_id){
	GLOBAL $connection;
	$sql = "SELECT * FROM entities
			WHERE entities.entity_id = $entity_id			
	";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	return mysqli_fetch_assoc($query);
}

// GET ASSIGNED DEAL GRUOPS
function getAssignedDealGroups($entity_id){
	GLOBAL $connection;

	$sql = "
		SELECT deal_groups.dealgroup_id,group_name,code_name,dealgroup_entity_assignment.type FROM dealgroup_entity_assignment,deal_groups
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


if($_POST){
	if(isset($_POST['add_entity'])){
		addEntity();
	}

	if(isset($_POST['update_entity'])){
		updateEntity();
	}
}