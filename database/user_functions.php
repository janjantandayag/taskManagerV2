<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(!isset($connection)){
	include('connection.php');
}
// LOGGED-IN THE USER
function login($username,$password){
	GLOBAL $connection;
	$sql = "SELECT * FROM users WHERE email='$username' AND password=md5('$password') AND status='ACTIVE'";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

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
// GET ALL USERS' POSITIONS NOT SET
function fetchUserPositions($id){
	GLOBAL $connection;

	$sql = "SELECT positions.position_id AS id, positions.position_title as text FROM role_position,positions
			WHERE role_position.user_id = $id
			AND role_position.position_id = positions.position_id
	";	
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	$data = [];
	while($position = mysqli_fetch_assoc($query)){
		$data[] = $position['id'];
	}

	return $data;
}
// GET ALL USERS' POSITIONS NOT SET
function getPreviousPositions($id){
	GLOBAL $connection;

	$sql = "SELECT role_position.role_position_id
			FROM role_position
			WHERE role_position.user_id = $id
	";	
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

	$data = [];
	while($position = mysqli_fetch_assoc($query)){
		$data[] = $position['role_position_id'];
	}

	return $data;
}
// GET USER ROLES
function getUserRoles(){
	GLOBAL $connection;
	$id = $_SESSION['user_id'];

	$sql = "SELECT * FROM user_role,roles
			WHERE user_role.user_id = $id
			AND user_role.role_id = roles.role_id
	";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	$roles = [];
	while($role = mysqli_fetch_assoc($query)){
		$roles[$role['role_name']] = $role['role_name'];
	}
	return $roles;
}


// Logged out user
function logout(){
	session_destroy();
	header('Location: ../index.php');
}

// Get all users.
function getUsers(){	
	GLOBAL $connection;
	$sql = "SELECT * FROM users ORDER BY last_name, first_name";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}
// Get specific user using ID
function getUserDetails($id){	
	GLOBAL $connection;
	$sql = "SELECT * FROM users WHERE user_id='$id'";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}

function getActivePositions($id){
	GLOBAL $connection;
	$sql = "SELECT positions.position_title,positions.position_id,positions.position_description,role_position.start_date,role_position.end_date,role_position.status
		FROM users
		LEFT JOIN role_position ON users.user_id = role_position.user_id
		LEFT JOIN positions ON role_position.position_id = positions.position_id
		WHERE users.user_id = '$id' AND role_position.position_id IS NOT NULL 
		AND role_position.status = 'ACTIVE'
		ORDER BY role_position.status ASC, role_position.start_date DESC
		";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));	
	return $query;
}
// Get positions 
function getPositionHistory($id){
	GLOBAL $connection;
	$sql = "SELECT *
			FROM positions
			LEFT JOIN role_position ON positions.position_id = role_position.position_id
			LEFT JOIN dealgroup_staffing ON role_position.role_position_id = dealgroup_staffing.role_position_id
			WHERE role_position.user_id = '$id' 
			AND dealgroup_staffing.role_position_id IS NOT NULL
			ORDER BY dealgroup_staffing.status ASC, dealgroup_staffing.start_date DESC
	";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));	
	return $query;
}

// Get user positions
function getUserPositions($user_id){
	GLOBAL $connection;
	$sql = "SELECT * FROM role_position,positions,dealgroup_staffing 
			WHERE user_id = $user_id 
			AND role_position.position_id = positions.position_id
			AND role_position.role_position_id = dealgroup_staffing.role_position_id
			AND dealgroup_staffing.status = 'ACTIVE'";
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
	$sql = "SELECT * FROM role_position,positions,dealgroup_staffing 
			WHERE user_id = $user_id 
			AND role_position.position_id = positions.position_id
			AND role_position.role_position_id = dealgroup_staffing.role_position_id
			AND dealgroup_staffing.status = 'ACTIVE'
	";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	$count = mysqli_num_rows($query);
	return ($count > 1) ? 's' : '';
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
	$positions = $data['positions'];

	$query = "INSERT INTO users(first_name,last_name,email,office_phone,cell_phone,password,status) 
			VALUES ('$first_name','$last_name','$email','$office_phone','$cell_phone',md5('$password'),'ACTIVE')";
	mysqli_query($connection, $query) or die(mysqli_error($connection));
	$user_id = mysqli_insert_id($connection);

	$file_name_init = $user_id .'-'.$first_name.'-'.$last_name;

	uploadImage(str_replace(' ', '', $file_name_init),$user_id);

	foreach($positions as $position_id){
		if(!empty($position_id)){
			$query = "INSERT INTO role_position(user_id,position_id) 
				VALUES ($user_id,$position_id)";
			mysqli_query($connection, $query) or die(mysqli_error($connection));
		}
	}

	if($_SESSION['add_user_error'] === ""){
		$_SESSION['add_user_success'] = "Successfully added new user!";
		unset($_SESSION['add_user_error']);
		header("Location:../users_view.php?user_id=$user_id");	
	} else {
		header("Location:../users_form.php");	
	}
}

// Update User
function updateUser($data) {	
	GLOBAL $connection;	
	$_SESSION['update_user_error'] = "";
	$_SESSION['update_user_success'] = "";

	$user_id = $data['user_id'];
	$first_name = strtolower($data['first_name']);
	$last_name = strtolower($data['last_name']);
	$email = strtolower($data['email']);
	$office_phone = strtolower($data['office_phone']);
	$cell_phone = strtolower($data['cell_phone']);
	$password = strtolower($data['password']);
	$current = empty($data['positions']) ? [] : $data['positions'] ;
	$previous = explode(",", $data['previous_positions']);



	$query = "UPDATE users 
	        SET first_name = '$first_name',last_name = '$last_name',email = '$email', 
	          	  office_phone = '$office_phone' , cell_phone = '$cell_phone',
	              password = md5('$password'),status='ACTIVE' 
	        WHERE user_id = $user_id";


	mysqli_query($connection, $query) or die(mysqli_error($connection));

	$file_name_init = $user_id .'-'.$first_name.'-'.$last_name;

	uploadImage(str_replace(' ', '', $file_name_init),$user_id);

	$current_role_positions = [];

	if(!empty($current)) {
		foreach($current as $position_id){
			if(!empty($position_id)) {
				$sql = "SELECT role_position.role_position_id
						FROM role_position
						WHERE role_position.user_id = $user_id
						AND role_position.position_id = $position_id
				";

				$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
				
				if(!$query->num_rows > 0){
					$sql_insert = "INSERT INTO role_position(position_id,user_id)
						VALUES ($position_id,$user_id)";

					mysqli_query($connection,$sql_insert) or die(mysqli_error($connection));
					$inserted_role_position_id = mysqli_insert_id($connection);
					$current_role_positions[] = $inserted_role_position_id;
				} else {
					$current_role_positions[] = mysqli_fetch_assoc($query)['role_position_id'];
				}
			}
		}
	}

	$role_position_ids = array_unique(array_merge($previous,$current_role_positions));

	include('position_functions.php');
	foreach($role_position_ids as $role_position_id){
		$sql = "SELECT dealgroup_staffing.role_position_id
			   FROM dealgroup_staffing
			   WHERE dealgroup_staffing.role_position_id = $role_position_id
		";

		$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));

		if($query->num_rows > 0){
			if( in_array($role_position_id, $previous) && !in_array($role_position_id, $current_role_positions) ){
				$position_details = getRolePositionDetails($role_position_id);
				$_SESSION['update_user_error'] .= "<li>Cannot delete position title: <strong>" . strtoupper($position_details['position_title']) . "</strong></li>";
			}
		} else {
			if( in_array($role_position_id, $previous) && !in_array($role_position_id, $current_role_positions) ){
				$sql = "DELETE FROM role_position WHERE role_position_id=$role_position_id";
				$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
			}
		}
	}

	if(empty($_SESSION['update_user_error'])){
		$_SESSION['update_user_success'] .= "User successfully updated!";	
		unset($_SESSION['update_user_error']);
	} 

	header("Location:../users_update.php?id=$user_id&&form=update");
}

// Upload Image
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

// Reset Password
function resetPassword($id){
	GLOBAL $connection;
	$sql = "SELECT * FROM users WHERE users.user_id = $id";
	$query = mysqli_query($connection,$sql);

	if($query->num_rows != 0){
		$sql = "UPDATE users SET users.password = md5('password') WHERE users.user_id = $id";
		$query = mysqli_query($connection,$sql);

		echo json_encode([
			'status' => 'success',
			'message' => 'Successfully resetted password!'
		]);
	} else {
		echo json_encode([
			'status' => 'error',
			'message' => 'User not found in the system!'
		]);
	}
}
// modify status
function modifyStatus($id,$action){	
	GLOBAL $connection;

	$status = $action === 'activate' ? 'ACTIVE' : 'INACTIVE';
	$sql = "UPDATE users SET status='$status' WHERE users.user_id = $id";
	$query = mysqli_query($connection,$sql);
}

// get users that are portfolio management analyst
function getAllPMA(){	
	GLOBAL $connection;

	$sql = "SELECT users.first_name,users.user_id,users.last_name,role_position.role_position_id
			FROM positions
			LEFT JOIN role_position ON positions.position_id = role_position.position_id
			LEFT JOIN users ON role_position.user_id = users.user_id
			LEFT JOIN dealgroup_staffing ON role_position.role_position_id = dealgroup_staffing.role_position_id
			WHERE positions.position_title = 'Portfolio Management Analyst'	
			AND dealgroup_staffing.dealgroup_staffing_id IS NOT NULL		
	";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}

// get deal groups associated with PMA
function pmaDealGroups($role_position_id){
	GLOBAL $connection;

	$sql = "SELECT deal_groups.group_name, deal_groups.dealgroup_id
			FROM role_position,dealgroup_staffing,deal_groups
			WHERE role_position.role_position_id = $role_position_id
			AND role_position.role_position_id = dealgroup_staffing.role_position_id
			AND dealgroup_staffing.dealgroup_id = deal_groups.dealgroup_id";
	$query = mysqli_query($connection,$sql) or die(mysqli_error($connection));
	return $query;
}


if($_POST){
	if(isset($_POST['login'])){
		$username = $_POST['username'];
		$password = $_POST['password'];
		login($username,$password);
	}

	if(isset($_POST['update_user'])){
		updateUser($_POST);
	}	

	if(isset($_POST['add_user'])){
		addUser($_POST);
	}	

	if(isset($_POST['action']) == 'reset_password'){
		resetPassword($_POST['user_id']);
	}

	if(isset($_POST['modify_status'])){
		modifyStatus($_POST['user_id'],$_POST['modify_status']);
	}
}

if($_GET){
	if(isset($_GET['action']) == 'logout'){
		logout();
	}
}