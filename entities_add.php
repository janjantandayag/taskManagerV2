<?php
if (session_status() == PHP_SESSION_NONE) {
  session_start();
}
if(isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true){
  include('includes/header.php');
  include('includes/sidebar.php');
  include('includes/top_navigation.php');
  include('database/dealgroup_functions.php');

  $isUpdate = false;
?>

<div class="right_col" role="main">
  <?php
    if(isset($_SESSION['action_error'])) {
      $alert['class_type'] = 'danger';
      $alert['text'] = 'ERROR';
      $message = $_SESSION['action_error'];
  ?>
  <div class="row">
    <div class="alert alert-<?= $alert['class_type'] ?> alert-dismissible fade in" role="alert" style="margin-top:70px">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">×</span>
            </button>
            <strong><?= $alert['text'] ?></strong> <?= $message; ?>
        </div>  
  </div>
  <?php } unset($_SESSION['action_error']); ?>
  <form class="form-horizontal form-label-left input_mask" method="POST" action="database/entity_functions.php" />
    <?php include('includes/entities/entity_form_details.php') ?>
    <?php include('includes/entities/entity_form_dealgroups.php') ?>

    <div class="row">
      <div class="col-xs-12" style="text-align: center">              
        <button class="btn btn-primary btn-xs" name="add_entity" type="submit"> Add Entity </button> 
      </div>              
    </div>
  </form>
</div>

<?php
  include('includes/footer.php');
} else {
	$_SESSION['login_error'] = 'Unauthorized access. Please login to continue!';
	header("Location: index.php");
}
       
