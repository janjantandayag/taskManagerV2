<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($connection)){
	include('connection.php');
}

// get all entities
function getEntities(){
	GLOBAL $connection;

	$sql = "SELECT * FROM entities";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	return $query;
}