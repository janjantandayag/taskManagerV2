<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once('connection.php');

// GET ALL DOCUMENTS
function getAllDocuments(){
	GLOBAL $connection;
	$sql = "SELECT *
			FROM documents
	";

	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}

