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

// Add New User
function addUser($data){
	GLOBAL $connection;	
	$first_name = strtolower($data['first_name']);
	$last_name = strtolower($data['last_name']);
	$email = strtolower($data['email']);
	$office_phone = strtolower($data['office_phone']);
	$cell_phone = strtolower($data['cell_phone']);
	$password = strtolower($data['password']);

	$positions = $data['position_id'];
	$start_dates = $data['start_date'];
	$end_dates = $data['end_date'];

	$query = "INSERT INTO users(first_name,last_name,email,office_phone,cell_phone,password,status) 
			VALUES ('$first_name','$last_name','$email','$office_phone','$cell_phone',md5('$password'),'ACTIVE')";
	mysqli_query($connection, $query);
	$user_id = mysqli_insert_id($connection);

	$file_name_init = $user_id .'-'.$first_name.'-'.$last_name;

	uploadImage($file_name_init,$user_id);


	for ($i=0;$i<count($positions);$i++){
		GLOBAL $connection;
		$position_id = $positions[$i];
		$start_date =  $start_dates[$i];
		$end_date = $end_dates[$i];

		$query = "INSERT INTO role_position(user_id,position_id,start_date,end_date) 
			VALUES ($user_id,$position_id,'$start_date','$end_date')";
		mysqli_query($connection, $query);
	}


	if($_SESSION['add_user_error'] === ""){
		$_SESSION['add_user_success'] = "Successfully added new user!";
		unset($_SESSION['add_user_error']);
	}

	header("Location:../users_form.php");	
}

function uploadImage($file_name_init,$user_id){
	// Modify file name
	GLOBAL $connection;
	$db_file_name = basename($_FILES["image"]["name"]);
    $file = $_FILES["image"]["name"];        
    $ext = pathinfo($file, PATHINFO_EXTENSION);
	$db_file_name = $file_name_init. '.' . $ext;

	if (empty($_FILES['image']['size'])){
		$db_file_name = 'user-default.png';
	}

	$query = "UPDATE users SET profile_image = '$db_file_name' WHERE user_id = $user_id";
	mysqli_query($connection, $query) or die(mysqli_error());

	//get the file information
    $fileName = basename($_FILES["image"]["name"]);
    $fileTmp = $_FILES["image"]["tmp_name"];
    $fileType = $_FILES["image"]["type"];
    $fileSize = $_FILES["image"]["size"];
    $fileExt = substr($fileName, strrpos($fileName, ".") + 1);
    
    //specify image upload directory
    $largeImageLoc = 'attachment/images/'.$db_file_name;
    $thumbImageLoc = 'attachment/images/profile/'.$db_file_name;
 
    //check file extension
    $_SESSION['add_user_error'] = "";
    if((!empty($_FILES["image"])) && ($_FILES["image"]["error"] == 0)){
        if($fileExt != "jpg" && $fileExt != "jpeg" && $fileExt != "png"){
            $_SESSION['add_user_error'] .= "Sorry, only JPG, JPEG & PNG files are allowed. <br/>";
        }
    }
    
    //if everything is ok, try to upload file
    if(strlen($_SESSION['add_user_error']) == 0 && !empty($fileName)){
        if(move_uploaded_file($fileTmp, $largeImageLoc)){
            //file permission
            chmod ($largeImageLoc, 0777);
            
            //get dimensions of the original image
            list($width_org, $height_org) = getimagesize($largeImageLoc);
            
            //get image coords
            $x = (int) $_POST['x'];
            $y = (int) $_POST['y'];
            $width = (int) $_POST['w'];
            $height = (int) $_POST['h'];

            //define the final size of the cropped image
            $width_new = $width;
            $height_new = $height;
            
            //crop and resize image
            $newImage = imagecreatetruecolor($width_new,$height_new);
            
            switch($fileType) {
                case "image/gif":
                    $source = imagecreatefromgif($largeImageLoc); 
                    break;
                case "image/pjpeg":
                case "image/jpeg":
                case "image/jpg":
                    $source = imagecreatefromjpeg($largeImageLoc); 
                    break;
                case "image/png":
                case "image/x-png":
                    $source = imagecreatefrompng($largeImageLoc); 
                    break;
            }
            
            imagecopyresampled($newImage,$source,0,0,$x,$y,$width_new,$height_new,$width,$height);

            switch($fileType) {
                case "image/gif":
                    imagegif($newImage,$thumbImageLoc); 
                    break;
                case "image/pjpeg":
                case "image/jpeg":
                case "image/jpg":
                    imagejpeg($newImage,$thumbImageLoc,90); 
                    break;
                case "image/png":
                case "image/x-png":
                    imagepng($newImage,$thumbImageLoc);  
                    break;
            }
            imagedestroy($newImage);            
            //remove large image
            unlink($largeImageLoc);
        }else{
            $_SESSION['add_user_error'] .= "Sorry, there was an error uploading your file.";
        }
    }
}


if($_POST){
	if(isset($_POST['login'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		login($username,$password);
	}
	if(isset($_POST['add_user'])){
		addUser($_POST);
	}
}

if($_GET){
	if(isset($_GET['action']) == 'logout'){
		logout();
	}
}