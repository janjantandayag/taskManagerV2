<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
include('connection.php');

// LOGGED-IN THE USER
function login($username,$password){
	GLOBAL $connection;
	$sql = "SELECT * FROM users WHERE email='$username' AND password=md5('$password')";
	$query = mysqli_query($connection,$sql);

	$result = mysqli_fetch_assoc($query);

	if($result){		
		$_SESSION['logged_in'] = true;
		$_SESSION['user_id'] = $result['user_id'];
		$_SESSION['first_name'] = $result['first_name'];
		$_SESSION['last_name'] = $result['last_name'];
		$_SESSION['profile_image'] = $result['profile_image'];

		header('Location: ../dashboard.php');
	} else {
		$_SESSION['login_error'] = 'Username or password is incorrect!';
		header('Location: ../index.php');
	}
}

// Logged out user
function logout(){
	session_destroy();
	header('Location: ../index.php');
}

// Get all users.
function getUsers(){	
	GLOBAL $connection;
	$sql = "SELECT * FROM users ORDER BY first_name ASC";
	$query = mysqli_query($connection,$sql);
	return $query;
}

// Get user positions
function getUserPositions($user_id){
	GLOBAL $connection;
	$sql = "SELECT * FROM role_position,positions WHERE user_id = $user_id AND role_position.position_id = positions.position_id";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	$value = [];

	while($result = mysqli_fetch_assoc($query)){
		$value[] = $result['position_title'];
	}

	return $value ? implode(', ',$value) : "<span class='label label-danger'>Position Not Set</span>";
}

// Count number of positions
function countPosition($user_id){
	GLOBAL $connection;
	$sql = "SELECT * FROM role_position,positions WHERE user_id = $user_id AND role_position.position_id = positions.position_id";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	$count = mysqli_num_rows($query);
	return ($count > 0 && $count != 1) ? 's' : '';
}


if($_POST){
	if($_POST['login']){
		$username = $_POST['username'];
		$password = $_POST['password'];
		login($username,$password);
	}
}

if($_GET){
	if($_GET['action'] == 'logout'){
		logout();
	}
}