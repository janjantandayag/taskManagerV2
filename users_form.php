<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/user_functions.php');

  $isUpdate = false;
?>



<div class="right_col" role="main">	
	<?php 
	if(isset($_SESSION['add_user_error'])) {
		$alert['class_type'] = 'danger';
		$alert['text'] = 'ERROR!';
		$message = isset($_SESSION['add_user_error']) ? $_SESSION['add_user_error'] : '';
	?>
	<div class="row">
 		<div class="alert alert-<?= $alert['class_type'] ?> alert-dismissible fade in" role="alert" style="margin-top:70px">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <strong><?= $alert['text'] ?></strong> <?= $message; ?>
      	</div>  
	</div>
	<?php } unset($_SESSION['add_user_error']); ?>
	<div class="row">
		<div class="col-md-12 col-xs-12">			
			<br />
			<form class="form-horizontal form-label-left input_mask" method="POST" action="database/user_functions.php" onsubmit="return checkCoords();" enctype="multipart/form-data">
				<?php include('includes/users/user_details.php'); ?>			
				<?php include('includes/users/user_profile.php'); ?>			
				<?php include('includes/users/user_position.php'); ?>
				<div class="row">
					<div class="col-xs-12" style="text-align: center">							
						<button class="btn btn-primary btn-md" name="add_user" type="submit">Add User</button>
					</div>              
				</div>
			</form>
		</div>
	</div>
</div>


</div>
</div>
</div>
<?php

  include('includes/footer.php');



} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
