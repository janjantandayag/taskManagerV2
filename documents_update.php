<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/user_functions.php');
  include('database/document_functions.php');

  $isUpdate = true;
?>

<div class="right_col" role="main">   
    <?php
      if(isset($_SESSION['action_success'])) {
        $alert['class_type'] = 'success';
        $alert['text'] = 'SUCCESS';
        $message = $_SESSION['action_success'];
    ?>
    <div class="row">
      <div class="alert alert-<?= $alert['class_type'] ?> alert-dismissible fade in" role="alert" style="margin-top:70px;margin-bottom: 0px">
              <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
              </button>
              <strong><?= $alert['text'] ?></strong> <?= $message; ?>
          </div>  
    </div>
    <?php } unset($_SESSION['action_success']); ?>
  <div class="row">
    <form class="form-horizontal form-label-left input_mask" method="POST" action="database/document_functions.php" />
      <?php include('includes/documents/documents_form.php'); ?>
    </form>
  </div>  
</div>

<?php
  include('includes/footer.php');
} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
