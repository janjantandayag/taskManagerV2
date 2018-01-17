<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
	include('includes/header.php');
	include('includes/sidebar.php');
	include('includes/top_navigation.php');
	include('database/user_functions.php');

	$isUpdate = true;
?>



<div class="right_col" role="main">
	<div class="row">
		<div class="col-md-12 col-xs-12">			
			<br />
			<form class="form-horizontal form-label-left input_mask" method="POST" action="database/user_functions.php" onsubmit="return checkCoords();" enctype="multipart/form-data">
				<?php include('includes/users/user_details.php'); ?>			
				<?php include('includes/users/user_profile.php'); ?>			
				<?php include('includes/users/user_position.php'); ?>
				<div class="row">
					<div class="col-xs-12" style="text-align: center">			
						<input type="hidden" name="user_id" value="<?= $_GET['id']; ?>">				
						<button class="btn btn-primary btn-md" name="update_user" type="submit">Update User</button>
					</div>              
				</div>
			</form>
		</div>
	</div>
  </div>

<?php
  include('includes/footer.php');
} else {
  $_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
  header("Location: index.php");
}
       
