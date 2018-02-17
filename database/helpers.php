<?php
require_once('connection.php');

function removeSpecialChars($value){
	GLOBAL $connection;
	// escaped special characters, sql
	$escaped_string = mysqli_real_escape_string($connection,$value);
	// convert predefined characters to HTML entities
	$val = htmlspecialchars($escaped_string);

	return $val;
}