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

if($_POST){
}

if($_GET){
}