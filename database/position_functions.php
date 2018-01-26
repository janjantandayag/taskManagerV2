<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

function getPositions(){
	GLOBAL $connection;
	$sql = "SELECT * FROM positions ORDER BY position_title";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}

function getRolePositionDetails($id){
	GLOBAL $connection;
	$sql = "SELECT * FROM role_position,positions WHERE role_position.role_position_id = $id
			AND role_position.position_id = positions.position_id";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return mysqli_fetch_assoc($query);
}

if($_POST){
}

if($_GET){
}