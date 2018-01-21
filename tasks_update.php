<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/task_functions.php');
  include('database/dealgroup_functions.php');
  include('database/document_functions.php');

  $isUpdate = true;
?>

<div class="right_col" role="main">
  <?php if(isset($_SESSION['update_task_error'])) : ?>
  <div class="row">
    <div class="alert alert-success ?> alert-dismissible fade in" role="alert" style="margin-top:70px">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <strong>SUCCESS</strong> <?= $_SESSION['update_task_error']; ?>
        </div>  
  </div>
  <?php endif; unset($_SESSION['update_task_error']); ?>
  <form class="form-horizontal form-label-left input_mask" method="POST" action="database/task_functions.php" />
    <?php include('includes/tasks/task_form.php'); ?>
  </form>
</div>

<?php
  include('includes/footer.php');
} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
