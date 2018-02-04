<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($connection)){
	include('connection.php');
}

// GET TASK DETAILS
function getDealGroups(){
	GLOBAL $connection;
	$sql = "SELECT *
			FROM deal_groups
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}
// GET deal group details
function getDealGroupAssignmentDetails($id){
	GLOBAL $connection;
	$sql = "SELECT * FROM deal_groups,dealgroup_entity_assignment
			WHERE dealgroup_entity_assignment.dealgroup_entity_assignment_id = $id
			AND dealgroup_entity_assignment.dealgroup_id = deal_groups.dealgroup_id
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return mysqli_fetch_assoc($query);
}
// GET ENTITIES
function getAssocEntities($dealgroup_id){
	GLOBAL $connection;
	$sql = "SELECT * FROM deal_groups,dealgroup_entity_assignment,entities
			WHERE deal_groups.dealgroup_id = $dealgroup_id
			AND deal_groups.dealgroup_id = dealgroup_entity_assignment.dealgroup_id
			AND dealgroup_entity_assignment.entity_id = entities.entity_id
			ORDER BY entities.entity_legal_name
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}
// GET STAFF ASSIGNMENT
function getEntityDealGroupStaff($dealgroup_id,$entity_id){
	GLOBAL $connection;
	$sql = "SELECT * FROM dealgroup_staffing,role_position,positions,users
			WHERE dealgroup_staffing.dealgroup_id = $dealgroup_id
			AND dealgroup_staffing.entity_id = $entity_id
			AND dealgroup_staffing.role_position_id = role_position.role_position_id
			AND role_position.position_id = positions.position_id
			AND role_position.user_id = users.user_id
			ORDER BY positions.position_title DESC
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}