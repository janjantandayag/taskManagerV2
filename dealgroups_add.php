<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/dealgroup_functions.php');
  include('database/user_functions.php');

  $isUpdate = false;
?>

<div class="right_col" role="main">
  <form class="form-horizontal form-label-left input_mask" method="POST" enctype="multipart/form-data" action="database/dealgroup_functions.php" />
    <?php include('includes/dealgroups/dealgroup_form.php'); ?>
  </form>
</div>

<?php
  include('includes/footer.php');
} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
