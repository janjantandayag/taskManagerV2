<?php
session_start();
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

function logout(){
	session_destroy();
	header('Location: ../index.php');
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