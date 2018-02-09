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
	$sql = "SELECT * 
			FROM deal_groups,dealgroup_entity_assignment
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

// get dealgroup details
function getDealGroupDetails($dealgroup_id , $page = ''){
	GLOBAL $connection;
	
	$sql = "SELECT *
		FROM deal_groups
		WHERE deal_groups.dealgroup_id = $dealgroup_id";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	if($page == 'view'){
		return $query;
	}
	return mysqli_fetch_assoc($query);
}

// function deal group
function addDealGroup(){
	GLOBAL $connection;
	$_SESSION['action_success'] = "";
	$_SESSION['action_error'] = "";

	$main_contact_id = $_POST['dealgroup_main_contact'];
	$group_name = strtolower($_POST['group_name']);
	$code_name = strtolower($_POST['code_name']);
	$sector = strtolower($_POST['sector']);
	$deal_type = strtolower($_POST['deal_type']);
	$club_syndicate = strtolower($_POST['club_syndicate']);
	$source = strtolower($_POST['source']);
	$business_description = strtolower($_POST['business_description']);
	$entities = $_POST['entity'];
	$start_dates = $_POST['start_date'];
	$end_dates = $_POST['end_date'];
	$types = $_POST['type'];

	$sql = "INSERT INTO deal_groups (main_contact_id,group_name,code_name,sector,business_description,deal_type,club_syndicate,source)
			VALUES ($main_contact_id,'$group_name','$code_name','$sector','$business_description','$deal_type','$club_syndicate','$source') 
	";

	mysqli_query($connection,$sql) or die(mysqli_error($connection));

	$id = mysqli_insert_id($connection);

	for($i=0;$i<count($entities);$i++){
		if($entities[$i]){
			$entity_id = $entities[$i];
			$start_date = $start_dates[$i];
			$end_date = $end_dates[$i];
			$type = $types[$i];

			$sql = "INSERT INTO dealgroup_entity_assignment (dealgroup_id,entity_id,start_date,end_date,type)
					VALUES ($id,$entity_id,'$start_date','$end_date','$type'); 
			";

			mysqli_query($connection,$sql) or die(mysqli_error($connection));
		}		
	}

	if(empty($_SESSION['action_error'])){
		$_SESSION['action_success'] .= "Deal group succesfully added!";
		unset($_SESSION['action_error']);
		header("Location: ../dealgroups_view.php?dealgroup_id=$id");
	} else {
		$_SESSION['action_error'] .= "Error on add!";		
		unset($_SESSION['action_success']);
		header("Location: ../dealgroups_add.php");
	}

    // if(count($_FILES['documents']['name']) > 0){
    //     for($i=0; $i<count($_FILES['upload']['name']); $i++) {
    //         $tmpFilePath = $_FILES['documents']['tmp_name'][$i];
    //         if($tmpFilePath != ""){
    //             //save the url and the file
    //             $filePath = "attachment/task/$id-" . date('d-m-Y-H-i-s').'-'.$_FILES['documents']['name'][$i];
    //             //Upload the file into the temp dir
    //             if(move_uploaded_file($tmpFilePath, $filePath)) {
    //                 $files[] = $shortname;
    //                 //insert into db 
    //                 //use $shortname for the filename
    //                 //use $filePath for the relative url to the file
    //             }
    //      	}
    //     }
    // }

}

if($_POST){
	if(isset($_POST['add_dealgroup'])){
		addDealGroup();
	}
}