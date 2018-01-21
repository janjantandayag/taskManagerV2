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